<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Section;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function show()
    {
        $sections = Section::all();

        return Inertia::render('Schedules/Show', [
            'sections' => $sections,
        ]);
    }

    public function getThesesBySection($sectionId)
    {
        $section = Section::findOrFail($sectionId);

        $schedules = Schedule::with(['thesis.user'])
            ->where('section_id', $sectionId)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $groupedSchedules = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'date' => $schedule->date->format('Y-m-d'),
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'duration' => $schedule->duration,
                'thesis_title' => $schedule->thesis->title,
                'section_id' => $schedule->section_id,
                'user' => [
                    'first_name' => $schedule->thesis->user->first_name,
                    'last_name' => $schedule->thesis->user->last_name,
                ],
            ];
        })->groupBy('date');

        return Inertia::render('Schedules/SectionSchedule', [
            'section' => $section,
            'schedules' => $groupedSchedules,
        ]);
    }
}
