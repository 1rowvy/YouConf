<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VerificationController extends Controller
{
    public function notice(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.show', ['id' => $request->user()->id]);
        }

        return Inertia::render('Auth/VerifyEmail', [
            'email' => $request->user()->email,
            'status' => session('status'),
        ]);
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.show', ['id' => $request->user()->id]);
        }

        $request->fulfill();

        return redirect()->route('user.show', ['id' => $request->user()->id]);
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.show', ['id' => $request->user()->id]);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
