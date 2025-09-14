<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use App\Services\CloudinaryService;
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
        $kartuKeluargaData = null;
        $rumahData = null;

        if ($user->hasRole('warga')) {
            // Get warga data from database using the relationship
            $wargaData = $user->wargaData;
            // If no warga data exists but user has NIK, try to find by NIK
            if (! $wargaData && $user->nik) {
                $wargaData = \App\Models\DataWarga::where('nik', $user->nik)->first();

                if ($wargaData) {
                    $wargaData->update(['user_id' => $user->id]);
                }
            }

            // If still no  a basic structure for form initialization
            if (! $wargaData && $user->nik) {
                $wargaData = (object) [
                    'nik' => $user->nik,
                    'full_name' => $user->name,
                    'tempat_lahir' => null,
                    'tanggal_lahir' => null,
                    'status' => null,
                ];
            }

            // Get kartu keluarga data if user is kepala keluarga
            if ($wargaData && strtolower($wargaData->status) === 'kepala keluarga') {
                $kartuKeluargaData = \App\Models\KartuKeluarga::where('nik', $wargaData->nik)->first();
                $rumahData = \App\Models\Rumah::where('nik', $wargaData->nik)->first();
            }
        }

        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'wargaData' => $wargaData,
            'kartuKeluargaData' => $kartuKeluargaData,
            'rumahData' => $rumahData,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Only update user fields that are present in the request
        $userFields = $request->only(['name', 'email']);
        $user->fill($userFields);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update warga data if user has warga role and wargaData is present in request
        if ($user->hasRole('warga') && $request->has('wargaData')) {
            $wargaData = $request->input('wargaData', []);

            // Update if at least NIK is present (allow partial updates)
            if (! empty($wargaData['nik'])) {
                // Use NIK as primary key for data warga
                $warga = \App\Models\DataWarga::updateOrCreate(
                    ['nik' => $wargaData['nik']],
                    [
                        'user_id' => $user->id,
                        'full_name' => $wargaData['full_name'] ?? $user->name,
                        'tempat_lahir' => $wargaData['tempat_lahir'] ?? null,
                        'tanggal_lahir' => $wargaData['tanggal_lahir'] ?? null,
                        'status' => $wargaData['status'] ?? null,
                        'is_warga' => true,
                    ]
                );

                // Update NIK in user table for synchronization
                $user->update(['nik' => $wargaData['nik']]);

                // Handle Kartu Keluarga data for Kepala Keluarga
                if ($request->has('kartuKeluargaData') && strtolower($wargaData['status']) === 'kepala keluarga') {
                    $kartuKeluargaData = $request->input('kartuKeluargaData', []);

                    // Always process kartu keluarga data for Kepala Keluarga
                    $kkData = [
                        'nik' => $warga->nik,
                        'jumlah_anggota' => $kartuKeluargaData['jumlah_anggota'] ?? 1,
                    ];

                    // Only add no_kk if it's provided and not empty
                    if (!empty($kartuKeluargaData['no_kk'])) {
                        $kkData['no_kk'] = $kartuKeluargaData['no_kk'];
                    }

                    // Handle photo upload if provided
                    if ($request->hasFile('kartuKeluargaData.foto_ktp_kepala_keluarga')) {
                        $existingKK = \App\Models\KartuKeluarga::where('nik', $warga->nik)->first();

                        // Delete old image from Cloudinary if exists
                        if ($existingKK && $existingKK->public_id) {
                            $cloudinaryService = new CloudinaryService;
                            $cloudinaryService->deleteImage($existingKK->public_id);
                        }

                        // Upload new image to Cloudinary
                        $cloudinaryService = new CloudinaryService;
                        $uploadResult = $cloudinaryService->uploadImage(
                            $request->file('kartuKeluargaData.foto_ktp_kepala_keluarga'),
                            'kartu-keluarga-images'
                        );

                        if ($uploadResult['url']) {
                            $kkData['foto_ktp_kepala_keluarga'] = $uploadResult['url'];
                            $kkData['public_id'] = $uploadResult['public_id'];
                        }
                    }

                    // Always use NIK as the primary key for finding/updating Kartu Keluarga
                    $existingKK = \App\Models\KartuKeluarga::where('nik', $warga->nik)->first();
                    
                    if ($existingKK) {
                        // Update existing record, preserve existing no_kk if new one is empty
                        if (empty($kartuKeluargaData['no_kk'])) {
                            unset($kkData['no_kk']); // Don't update no_kk if it's empty
                        }
                        $existingKK->update($kkData);
                    } else {
                        // Create new record
                        if (empty($kartuKeluargaData['no_kk'])) {
                            // Generate a temporary no_kk if not provided
                            $kkData['no_kk'] = 'TEMP_' . $warga->nik . '_' . time();
                        }
                        \App\Models\KartuKeluarga::create($kkData);
                    }
                }

                // Handle Rumah data for Kepala Keluarga
                if ($request->has('rumahData') && strtolower($wargaData['status']) === 'kepala keluarga') {
                    $rumahData = $request->input('rumahData', []);

                    // Process rumah data for Kepala Keluarga
                    \App\Models\Rumah::updateOrCreate(
                        ['nik' => $warga->nik],
                        [
                            'perumahan' => $rumahData['perumahan'] ?? 'GBJ2',
                            'jalan' => $rumahData['jalan'] ?? '',
                            'blok' => $rumahData['blok'] ?? null,
                            'nomor' => $rumahData['nomor'] ?? null,
                        ]
                    );
                }
            }
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
