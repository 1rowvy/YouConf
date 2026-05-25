<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ExpertMailing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class MailingController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->hasRole('expert')) {
            abort(403);
        }

        $sections = $user->sections()->get(['sections.id', 'sections.name']);

        $participantIds = $user->sections()
            ->with(['users' => fn($q) => $q->role('participant')])
            ->get()
            ->flatMap(fn($s) => $s->users)
            ->unique('id')
            ->values();

        $participants = $participantIds->map(fn($p) => [
            'id'         => $p->id,
            'first_name' => $p->first_name,
            'last_name'  => $p->last_name,
            'email'      => $p->email,
        ]);

        return inertia('Expert/Mailings', [
            'sections'     => $sections,
            'participants' => $participants,
        ]);
    }

    public function send(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->hasRole('expert')) {
            abort(403);
        }

        $request->validate([
            'type'        => 'required|in:all,sections,selected',
            'subject'     => 'required|string|max:255',
            'body'        => 'required|string|max:10000',
            'section_ids' => 'array|required_if:type,sections',
            'section_ids.*' => 'integer|exists:sections,id',
            'user_ids'    => 'array|required_if:type,selected',
            'user_ids.*'  => 'integer|exists:users,id',
        ]);

        $recipients = match ($request->type) {
            'all' => User::role('participant')->get(),
            'sections' => User::role('participant')
                ->whereHas('sections', fn($q) => $q->whereIn('sections.id', $request->section_ids))
                ->get(),
            'selected' => User::whereIn('id', $request->user_ids)->get(),
        };

        Notification::send($recipients, new ExpertMailing($request->subject, $request->body));

        return redirect('/expert/mailings')->with('success', 'Рассылка отправлена');
    }
}
