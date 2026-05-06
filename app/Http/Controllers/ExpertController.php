<?php

namespace App\Http\Controllers;

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

        return inertia('Expert/Dashboard');
    }
}
