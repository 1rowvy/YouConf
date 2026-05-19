<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Status;
use App\Models\Thesis;
use App\Models\User;
use App\Notifications\ThesisStatusChanged;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ThesisController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::user()->id);
        $statuses = Status::all();
        $userSections = $user->sections()->get();

        if ($user->hasRole('participant')) {
            $theses = Thesis::where('user_id', $user->id)
                ->with(['section', 'user', 'status'])
                ->get();
        } elseif ($user->hasRole('expert')) {
            // Если пользователь эксперт, показываем заявки только из его секций
            $sectionIds = $user->sections()->pluck('sections.id');
            $theses = Thesis::whereIn('section_id', $sectionIds)
                ->with(['section', 'user', 'status'])
                ->get();
        }

        return inertia('Theses/Index', [
            'theses' => $theses,
            'statuses' => $statuses,
        ]);
    }

    public function thesisReview(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:statuses,id',
            'review_comment' => 'nullable|string|max:5000',
            'redirect_to' => 'nullable|string',
        ]);

        $user = Auth::user();
        if (!$user->hasRole('expert')) {
            return response()->json(['message' => 'Доступ запрещен.'], 403);
        }

        $thesis = Thesis::with(['status', 'user', 'section'])->findOrFail($id);
        $oldStatus = $thesis->status;
        $thesis->status_id = $request->status_id;
        $thesis->review_comment = $request->review_comment;
        $thesis->save();

        $newStatus = $thesis->fresh()->status;

        if ($newStatus->id == 2) {
            DB::beginTransaction();
            try {
                $section = $thesis->section;
                $sectionStartTime = Carbon::createFromFormat('H:i:s', $section->start_time);
                $sectionEndTime = Carbon::createFromFormat('H:i:s', $section->end_time);

                $lastSchedule = Schedule::where('section_id', $thesis->section_id)
                    ->orderBy('date', 'desc')
                    ->orderBy('end_time', 'desc')
                    ->first();

                $duration = $section->presentation_duration;

                if ($lastSchedule) {
                    $currentDate = Carbon::parse($lastSchedule->date);
                    $lastEndTime = Carbon::parse($lastSchedule->end_time);

                    $potentialEndTime = $lastEndTime->copy()->addMinutes($duration);

                    if ($potentialEndTime->format('H:i') > $sectionEndTime->format('H:i')) {
                        $newDate = $currentDate->addDay();
                        $newStartTime = $sectionStartTime;
                    } else {
                        $newDate = $currentDate;
                        $newStartTime = $lastSchedule->end_time;
                    }
                } else {
                    $newDate = Carbon::parse($section->start_date);
                    $newStartTime = $sectionStartTime;
                }

                $sectionEndDate = Carbon::parse($section->end_date);
                if ($newDate->gt($sectionEndDate)) {
                    throw new \Exception('Невозможно назначить время: все дни секции заполнены.');
                }

                $newEndTime = Carbon::parse($newStartTime)->addMinutes($duration)->format('H:i');

                Schedule::create([
                    'thesis_id'   => $thesis->id,
                    'section_id'  => $thesis->section_id,
                    'date'        => $newDate->format('Y-m-d'),
                    'start_time'  => $newStartTime,
                    'duration'    => $duration,
                    'end_time'    => $newEndTime,
                    'event_type'  => 'thesis',
                    'title'       => $thesis->title,
                    'description' => $thesis->description
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Schedule creation error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Ошибка распределения времени: ' . $e->getMessage());
            }
        }

        $thesis->user->notify(new ThesisStatusChanged($thesis, $oldStatus, $newStatus));

        $redirectTo = $request->input('redirect_to');
        if ($redirectTo) {
            return redirect($redirectTo)->with('success', 'Статус обновлен');
        }

        return redirect()->back()->with('success', 'Статус обновлен');
    }

    public function apply(Request $request, $section_id)
    {

        DB::transaction(function () use ($request, $section_id) {
            $thesis = Thesis::create([
                'title' => $request->title,
                'description' => $request->description,
                'co_authors' => $request->co_authors,
                'user_id' => Auth::id(),
                'status_id' => 1, // Статус "на рассмотрении"
                'section_id' => $section_id,
            ]);
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $thesis->addMedia($file)->toMediaCollection('attachments');
                }
            }

            Chat::create([
                'chatable_type' => Thesis::class, // Указываем тип модели
                'chatable_id' => $thesis->id,
            ]);
        });

        return redirect()->intended(route('user.show', ['id' => Auth::user()->id]));
    }

    public function update(Request $request, $id)
    {
        $thesis = Thesis::with('section')->findOrFail($id);

        if ($thesis->status_id !== 3) {
            return response()->json(['error' => 'Отправка правки невозможна: тезис не находится в статусе «На доработку».'], 403);
        }

        $section = $thesis->section;
        if ($thesis->revision_count >= $section->revision_limit) {
            return response()->json(['error' => 'Лимит правок исчерпан.'], 403);
        }

        DB::transaction(function () use ($request, $thesis) {
            $thesis->update([
                'title' => $request->title,
                'description' => $request->description,
                'co_authors' => $request->co_authors,
            ]);
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $thesis->addMedia($file)->toMediaCollection('attachments');
                }
            }
            $thesis->increment('revision_count');
            $thesis->status_id = 1;
            $thesis->review_comment = null;
            $thesis->save();
        });

        return redirect()->intended(route('user.show', ['id' => Auth::user()->id]));
    }

    public function deleteMedia($thesisId, $mediaId)
    {
        $thesis = Thesis::findOrFail($thesisId);
        $media = $thesis->getMedia('attachments')->find($mediaId);

        if ($media) {
            $media->delete();
            return response()->json(['success' => 'Файл успешно удален.']);
        }

        return redirect()->back();
    }

    public function show($id)
    {
        $thesis = Thesis::with(['section', 'user', 'status', 'chat.messages.user'])->findOrFail($id);

        // Получаем сообщения из чата
        $chat = $thesis->chat;
        $messages = $chat ? $chat->messages : [];
        $mediaFiles = $thesis->getMedia('attachments');
        $statuses = Status::all();

        return inertia('Theses/Show', [
            'thesis' => $thesis,
            'messages' => $messages,
            'mediaFiles' => $mediaFiles,
            'statuses' => $statuses,
            'revision_limit' => $thesis->section->revision_limit,
        ]);
    }


    public function create($section_id = null)
    {
        $section = Section::findOrFail($section_id);

        if (!$section->canCreateThesis()) {
            return back()->with('error', 'Section not on going');
        }

        return inertia('Theses/ThesisFormPage', [
            'section_id' => $section_id,
            'section_name' => $section->name,
        ]);
    }


    public function edit($id)
    {
        $thesis = Thesis::with(['section', 'user', 'status',])->findOrFail($id);
        $mediaFiles = $thesis->getMedia('attachments');

        return inertia('Theses/ThesisFormPage', [
            'thesis' => $thesis,
            'mediaFiles' => $mediaFiles,
        ]);
    }
}
