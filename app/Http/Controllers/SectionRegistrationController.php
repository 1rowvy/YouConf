<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionRegistrationController extends Controller
{
    public function toggle(Section $section, Request $request)
    {
        $user = Auth::user();

        if (!$section->canRegistration()) {
            return back()->with('error', 'Регистрация закрыта');
        }

        if ($section->hasParticipant($user)) {
            $section->users()->detach($user->id);
            return back()->with('success', 'Вы отменили участие');
        }

        $request->validate([
            'topics'               => 'nullable|array|max:5',
            'topics.*.topic'       => 'nullable|string|max:255',
            'topics.*.description' => 'nullable|string|max:1000',
            'supervisor'           => 'nullable|string|max:255',
            'co_author'            => 'nullable|string|max:1000',
            'degree_type'          => 'nullable|in:bachelor,magistrant,schoolboy,postgraduate',
            'course'               => 'nullable|integer|min:1|max:5',
            'group_number'         => 'nullable|string|max:50',
            'phone_number'         => 'nullable|string|max:12',
        ]);

        $topics = $request->topics ?? [];

        $section->users()->attach($user->id, [
            'topic'        => $topics[0]['topic'] ?? null,
            'description'  => $topics[0]['description'] ?? null,
            'topics'       => json_encode($topics),
            'supervisor'   => $request->supervisor,
            'co_author'    => $request->co_author,
            'degree_type'  => $request->degree_type,
            'course'       => $request->course,
            'group_number' => $request->group_number,
            'phone_number' => $request->phone_number,
        ]);

        return back()->with('success', 'Вы зарегистрированы');
    }
}
