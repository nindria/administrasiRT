<?php

namespace App\Http\Controllers;

use App\Models\DataIpl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class VerifikasiIplController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('VerifikasiIpl/Index', [
            'pendingIpls' => DataIpl::with(['noRumah', 'recordedBy'])
                ->pending()
                ->latest()
                ->get(),
            'verificationStatuses' => [
                'pending' => 'Menunggu Verifikasi',
                'verified' => 'Terverifikasi',
                'rejected' => 'Ditolak'
            ]
        ]);
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $ipl = DataIpl::with(['noRumah', 'recordedBy', 'verifiedBy'])
            ->findOrFail($id);

        return Inertia::render('VerifikasiIpl/Show', [
            'ipl' => $ipl,
            'verificationStatuses' => [
                'pending' => 'Menunggu Verifikasi',
                'verified' => 'Terverifikasi',
                'rejected' => 'Ditolak'
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function verify(Request $request, DataIpl $ipl)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,verified,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:255'
        ]);

        $updateData = [
            'status' => $validated['status'],
            'verified_at' => $validated['status'] === 'verified' ? now() : null,
            'verified_by' => $validated['status'] === 'verified' ? Auth::id() : null,
            'rejection_reason' => $validated['status'] === 'rejected' ? $validated['rejection_reason'] : null
        ];

        $ipl->update($updateData);

        return redirect()->route('verifikasiipl.index')
            ->with('success', 'Status verifikasi berhasil diperbarui');
    }
}
