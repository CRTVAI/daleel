<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile-settings', [
            'user' => $request->user(),
            'learningGoals' => \App\Models\LearningGoal::orderBy('title')->get(),
            'experienceLevels' => \App\Models\ExperienceLevel::orderBy('title')->get(),
            'tools' => \App\Models\Tool::where('status', 'active')->orderBy('name')->get(),
            'interestsList' => \App\Models\Content::distinct()->whereNotNull('category')->pluck('category')->toArray(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['interests'])) {
            // Check if it's Tagify JSON format or comma-separated string
            $decoded = json_decode($data['interests'], true);
            if (is_array($decoded)) {
                $data['interests'] = array_map(fn($item) => $item['value'], $decoded);
            } else {
                $data['interests'] = array_filter(array_map('trim', explode(',', $data['interests'])));
            }
        }

        if (isset($data['connections'])) {
            $decoded = json_decode($data['connections'], true);
            if (is_array($decoded)) {
                $data['connections'] = array_map(fn($item) => $item['value'], $decoded);
            } else {
                $data['connections'] = array_filter(array_map('trim', explode(',', $data['connections'])));
            }
        }

        if (isset($data['password'])) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $request->user()->fill($data);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        // GitHub-style confirmation: check if typed email matches user email
        if ($request->email_confirmation !== $user->email) {
            return back()->withErrors(['email_confirmation' => 'The email address you typed does not match your current email.'], 'userDeletion');
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
