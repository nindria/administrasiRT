<?php

namespace App\Http\Controllers;

use App\Models\DataWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class VerifikasiWargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('VerifikasiWarga/Index', [
            'allWargas' => DataWarga::with(['noRumah:id,name', 'verifier:id,name'])
                ->orderByRaw("FIELD(verification_status, 'pending', 'verified', 'rejected')")
                ->latest()
                ->get(),
            'verificationStatuses' => [
                'pending' => 'Pending',
                'verified' => 'Verified',
                'rejected' => 'Rejected'
                ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
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
    public function show(string $id)
    {
        $warga = DataWarga::with(['noRumah:id,name', 'verifier:id,name'])->findOrFail($id);

        return Inertia::render('VerifikasiWarga/Show', [
            'warga' => $warga,
            'verificationStatuses' => [
                'pending' => 'Pending',
                'verified' => 'Verified',
                'rejected' => 'Rejected'
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

    public function verify(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:verified,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:255'
        ]);

        $dataWarga = DataWarga::findOrFail($id);

        $dataWarga->update([
            'verification_status' => $validated['status'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $validated['status'] === 'rejected'
                ? $validated['rejection_reason']
                : null
        ]);

        return redirect()
            ->route('verifikasiwarga.index') // Pastikan nama route konsisten
            ->with('toast', [
                'message' => 'status telah diperbarui',
                'type' => 'success'
            ]);
    }

    public function bulkVerify(Request $request)
    {
       $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:data_wargas,id',
            'status' => 'required|in:verified,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:255'
        ]);

        DataWarga::whereIn('id', $validated['ids'])->update([
            'verification_status' => $validated['status'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $validated['status'] === 'rejected'
                ? $validated['rejection_reason']
                : null
        ]);

        return response()->json([
            'message' => 'Verifikasi massal berhasil dilakukan',
            'success' => true
        ]);
    }
}
