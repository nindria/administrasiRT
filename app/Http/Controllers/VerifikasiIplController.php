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
            'allIpls' => DataIpl::with(['noRumah:id,name', 'verifiedBy:id,name'])
                ->orderByRaw("FIELD(verification_status, 'pending', 'ok', 'rejected')")
                ->latest()
                ->get(),
            'verificationStatuses' => [
                'pending' => 'Pending',
                'ok' => 'ok',
                'rejected' => 'Rejected'
            ]
        ]);
    }

    // In your VerifikasiIplController.php
    public function verify(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:ok,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:255'
        ]);

        $ipl = DataIpl::findOrFail($id);

        $ipl->update([
            'verification_status' => $validated['status'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $validated['status'] === 'rejected'
                ? $validated['rejection_reason']
                : null
        ]);

        return redirect()
            ->route('verifikasiipl.index')
            ->with('toast', [
                'message' => 'Status verifikasi IPL diperbarui',
                'type' => 'success'
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
    public function show(DataIpl $id)
    {
        $ipl = DataIpl::with(['noRumah:id,name', 'verifiedBy:id,name'])->findOrFail($id);

        return Inertia::render('VerifikasiIpl/Show', [
            'ipl' => $ipl,
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
}
