<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thesis;
use App\Models\Chat;
use App\Models\File;
use App\Models\Section;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\ThesisStatusChanged;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:statuses,id',
        ]);

        $user = Auth::user();
        if (!$user->hasRole('expert')) {
            return response()->json(['message' => 'Доступ запрещен.'], 403);
        }

        $thesis = Thesis::with(['status', 'user', 'section'])->findOrFail($id);
        $oldStatus = $thesis->status;
        $thesis->status_id = $request->status_id;
        $thesis->save();

        $newStatus = $thesis->fresh()->status;

        $thesis->user->notify(new ThesisStatusChanged($thesis, $oldStatus, $newStatus));

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
        $thesis = Thesis::findOrFail($id);
        if (in_array($thesis->status_id, [2, 4])) {
            return response()->json(['error' => 'Редактирование выступления невозможно, так как статус не позволяет это.'], 403);
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
            'messages' => $messages, // Передаем сообщения в представление
            'mediaFiles' => $mediaFiles,
            'statuses' => $statuses,
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
