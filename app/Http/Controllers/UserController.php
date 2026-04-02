<?php

namespace App\Http\Controllers;

use App\Models\Thesis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);

        $theses = Thesis::where('user_id', $user->id)
            ->with(['status:id,name', 'section:id,name'])
            ->get();

        $sections = $user->sections->map(function ($section) {
            return array_merge($section->toArray(), [
                'status' => $section->status->value,
                'status_label' => $section->status->label(),
            ]);
        });

        return Inertia::render('UserProfile', [
            'theses' => $theses,
            'sections' => $sections,
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Проверяем, является ли текущий пользователь владельцем профиля
        if (Auth::user()->id !== $user->id) {
            abort(403, 'У вас нет прав для редактирования этого профиля.');
        }

        return Inertia::render('EditUserProfile', [
            'user_data' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|alpha|max:255',
                'last_name' => 'required|string|alpha|max:255',
                'patronymic' => 'nullable|string|alpha|max:255',
            ]);
            $user = User::findOrFail($id);
            $user->update($validated);

            return redirect()->route('user.show', ['id' => $user->id]);
        } catch (ValidationException $e) {
            return Inertia::render('EditUserProfile', [
                'errors' => $e->validator->errors(),
            ]);
        }
    }

    public function switchUser($userId)
    {
        $user = User::find($userId);

        if ($user) {
            Auth::login($user);

            return redirect()->back()->with('success', 'Вы успешно переключились на пользователя: '.$user->name);
        }

        return redirect()->back()->with('error', 'Пользователь не найден.');
    }
}
