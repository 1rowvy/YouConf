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
            'topic'        => 'nullable|string|max:255',
            'supervisor'   => 'nullable|string|max:255',
            'co_author'    => 'nullable|string|max:1000',
            'degree_type'  => 'nullable|in:bachelor,magistrant,schoolboy,postgraduate',
            'course'       => 'nullable|integer|min:1|max:5',
            'group_number' => 'nullable|string|max:50',
            'description'  => 'nullable|string|max:1000',
            'phone_number' => 'nullable|string|max:12'
        ]);

        $section->users()->attach($user->id, [
            'topic'        => $request->topic,
            'supervisor'   => $request->supervisor,
            'co_author'    => $request->co_author,
            'degree_type'  => $request->degree_type,
            'course'       => $request->course,
            'group_number' => $request->group_number,
            'description'  => $request->description,
            'phone_number' => $request->phone_number,
        ]);

        return back()->with('success', 'Вы зарегистрированы');
    }
}
