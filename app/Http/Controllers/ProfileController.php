<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ProfileSettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Get user statistics
        $stats = [
            'accounts' => $user->accounts()->count(),
            'transactions' => $user->transactions()->count(),
            'categories' => $user->categories()->count(),
            'budgets' => $user->budgets()->count(),
            'debts' => $user->debts()->count(),
            'assets' => $user->assets()->count(),
        ];
        
        return view('profile.edit', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Update basic profile fields
        $user->fill($request->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update additional profile fields in settings
        $settings = $user->settings ?? [];
        $settings = array_merge($settings, $request->only(['phone', 'birth_date', 'bio']));
        $user->settings = $settings;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile settings.
     */
    public function updateSettings(ProfileSettingsRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Update settings
        $settings = $user->settings ?? [];
        $settings = array_merge($settings, $request->validated());
        $user->settings = $settings;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'settings-updated');
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'], // 2MB max
        ]);

        $user = $request->user();
        
        // Delete old avatar if exists
        if ($user->settings['avatar'] ?? false) {
            Storage::disk('public')->delete($user->settings['avatar']);
        }
        
        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        
        // Update user settings
        $settings = $user->settings ?? [];
        $settings['avatar'] = $path;
        $user->settings = $settings;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if ($user->settings['avatar'] ?? false) {
            Storage::disk('public')->delete($user->settings['avatar']);
            
            $settings = $user->settings;
            unset($settings['avatar']);
            $user->settings = $settings;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'avatar-deleted');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete user's avatar if exists
        if ($user->settings['avatar'] ?? false) {
            Storage::disk('public')->delete($user->settings['avatar']);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
