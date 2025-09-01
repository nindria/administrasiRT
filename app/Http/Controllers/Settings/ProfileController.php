<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        $wargaData = null;

        if ($user->hasRole('warga')) {
            $wargaData = $user->wargaData;
        }

        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'wargaData' => $wargaData,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update warga data if user has warga role and wargaData is present in request
        if ($user->hasRole('warga')) {
            $wargaData = $request->validate([
                'wargaData.nik' => 'required|string|size:16',
                'wargaData.full_name' => 'required|string|max:255',
                'wargaData.tempat_lahir' => 'nullable|string|max:255',
                'wargaData.tanggal_lahir' => 'nullable|date',
                'wargaData.status' => 'nullable|string|max:255',
            ]);

            $warga = \App\Models\DataWarga::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nik' => $wargaData['wargaData']['nik'],
                    'full_name' => $wargaData['wargaData']['full_name'],
                    'tempat_lahir' => $wargaData['wargaData']['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $wargaData['wargaData']['tanggal_lahir'] ?? null,
                    'status' => $wargaData['wargaData']['status'] ?? null,
                    'is_warga' => true,
                ]
            );
        }

        return to_route('profile.edit');
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
