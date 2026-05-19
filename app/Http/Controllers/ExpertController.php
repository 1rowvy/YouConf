<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Status;
use App\Models\Thesis;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ExpertController extends Controller
{
    public function dashboard()
    {
        $user = User::findOrFail(Auth::user()->id);

        if (!$user->hasRole('expert')) {
            abort(403);
        }

        $sections = $user->sections->map(function ($section) {
            return array_merge($section->toArray(), [
                'status' => $section->status->value,
                'status_label' => $section->status->label(),
            ]);
        });

        return inertia('Expert/Dashboard', [
            'sections' => $sections
        ]);
    }

    public function theses()
    {
        $user = User::findOrFail(Auth::user()->id);

        if (!$user->hasRole('expert')) {
            abort(403);
        }

        $sectionIds = $user->sections()->pluck('sections.id');
        $theses = Thesis::whereIn('section_id', $sectionIds)
            ->with(['section', 'user', 'status'])
            ->get();

        return inertia('Expert/Theses', [
            'theses' => $theses,
        ]);
    }

    public function review($id)
    {
        $user = User::findOrFail(Auth::user()->id);

        if (!$user->hasRole('expert')) {
            abort(403);
        }

        $thesis = Thesis::with(['section', 'user', 'status'])->findOrFail($id);

        $expertSectionIds = $user->sections()->pluck('sections.id');
        if (!$expertSectionIds->contains($thesis->section_id)) {
            abort(403);
        }

        $statuses = Status::all();
        $mediaFiles = $thesis->getMedia('attachments');

        return inertia('Expert/ThesisReview', [
            'thesis' => $thesis,
            'statuses' => $statuses,
            'mediaFiles' => $mediaFiles,
        ]);
    }

    public function participants()
    {
        $user = User::findOrFail(Auth::user()->id);

        if (!$user->hasRole('expert')) {
            abort(403);
        }

        $sectionIds = $user->sections()->pluck('sections.id');

        $sections = Section::whereIn('id', $sectionIds)
            ->with(['users' => fn($q) => $q->role('participant')])
            ->get();

        $sections = $sections->map(function ($section) {
            $section->setRelation('users', $section->users->map(function ($participant) {
                $rawTopics = $participant->pivot->topics;
                $topics = $rawTopics ? json_decode($rawTopics, true) : null;
                if (!$topics) {
                    $topics = $participant->pivot->topic
                        ? [['topic' => $participant->pivot->topic, 'description' => $participant->pivot->description]]
                        : [];
                }

                return [
                    'id'         => $participant->id,
                    'first_name' => $participant->first_name,
                    'last_name'  => $participant->last_name,
                    'patronymic' => $participant->patronymic,
                    'email'      => $participant->email,
                    'topics'       => $topics,
                    'supervisor'   => $participant->pivot->supervisor,
                    'co_author'    => $participant->pivot->co_author,
                    'degree_type'  => $participant->pivot->degree_type,
                    'course'       => $participant->pivot->course,
                    'group_number' => $participant->pivot->group_number,
                    'phone_number' => $participant->pivot->phone_number,
                ];
            }));
            return $section;
        });

        return inertia('Expert/Participants', [
            'sections' => $sections,
        ]);
    }

    public function sections()
    {
        $user = User::findOrFail(Auth::user()->id);

        if (!$user->hasRole('expert')) {
            abort(403);
        }



        return inertia('Expert/Sections');
    }
}
