<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    public function index()
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
                return [
                    'id'         => $participant->id,
                    'first_name' => $participant->first_name,
                    'last_name'  => $participant->last_name,
                    'email'      => $participant->email,
                    'topic'        => $participant->pivot->topic,
                    'supervisor'   => $participant->pivot->supervisor,
                    'co_author'    => $participant->pivot->co_author,
                    'degree_type'  => $participant->pivot->degree_type,
                    'course'       => $participant->pivot->course,
                    'group_number' => $participant->pivot->group_number,
                ];
            }));
            return $section;
        });

        return inertia('Participants/Index', [
            'sections' => $sections,
        ]);
    }
}
