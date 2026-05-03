<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;


class SectionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $sections = Section::with('locations')->get()->map(function ($section) use ($user) {
            return array_merge($section->toArray(), [
                'status' => $section->status->value,
                'status_label' => $section->status->label(),
                'can_registration' => $section->canRegistration(),
                'is_joined' => $section->hasParticipant($user),
                'can_create_thesis' => $section->canCreateThesis(),
                'start_date' => $section->start_date?->format('d.m.Y'),
                'end_date' => $section->end_date?->format('d.m.Y'),
                'location_names' => $section->locations->pluck('name')->join(', '),
            ]);
        });

        return Inertia::render('Sections/Index', [
            'sections' => $sections,
            'user' => $user,
        ]);
    }

    public function show(Section $section)
    {
        $user = Auth::user();
        $section->load('locations');

        $sectionData = array_merge($section->toArray(), [
            'status' => $section->status->value,
            'status_label' => $section->status->label(),
            'can_registration' => $section->canRegistration(),
            'can_create_thesis' => $section->canCreateThesis(),
            'is_joined' => $section->hasParticipant($user),
            'start_date' => $section->start_date?->format('d.m.Y'),
            'end_date' => $section->end_date?->format('d.m.Y'),
            'location_names' => $section->locations->pluck('name')->join(', '),
        ]);

        $schedules = Schedule::with(['thesis.user'])
            ->where('section_id', $section->id)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $groupedSchedules = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'date' => $schedule->date ? $schedule->date->format('Y-m-d') : null,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'duration' => $schedule->duration,
                'thesis_title' => $schedule->thesis ? $schedule->thesis->title : $schedule->title,
                'section_id' => $schedule->section_id,
                'user' => $schedule->thesis ? [
                    'first_name' => $schedule->thesis->user->first_name,
                    'last_name' => $schedule->thesis->user->last_name,
                ] : null,
            ];
        })->groupBy('date');

        return Inertia::render('Sections/Show', [
            'section' => $sectionData,
            'user' => $user,
            'schedules' => $groupedSchedules
        ]);
    }
}
