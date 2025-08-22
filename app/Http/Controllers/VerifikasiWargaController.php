<?php

namespace App\Http\Controllers;

use App\Models\DataWarga;
use App\Models\VerifikasiDatawarga;
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
        $wargas = DataWarga::where('verification_status', 'pending')->get();
        return Inertia::render('VerifikasiWarga/Index', [
            'wargas' => $wargas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('VerifikasiWarga/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:20|unique:data_wargas,nik',
            'full_name' => 'required|string|max:255',
            'verification_status' => 'in:pending,verified,rejected'
        ]);

        $warga = DataWarga::create($validated);

        return redirect()->route('verifikasiwarga.index')
            ->with('success', 'Data warga berhasil ditambahkan.');
    }

    public function approve(DataWarga  $warga)
    {
        $warga->update([
            'is_warga' => true,
            'verification_status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Data berhasil disetujui.');
    }
    public function reject(DataWarga $warga, Request $request)
    {
        $warga->update([
            'verification_status' => 'rejected',
            'rejection_reason' => $request->catatan,
        ]);

        return back()->with('error', 'Data ditolak');
    }



    /**
     * Display the specified resource.
     */
    public function show(VerifikasiDatawarga $verifikasi)
    {
        return Inertia::render('VerifikasiWarga/Show', [
            'item' => $verifikasi,
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
    public function update(Request $request, $nik)
    {
        $warga = DataWarga::findOrFail($nik);
        $warga->is_warga = true;
        $warga->save();

        return redirect()->route('verifikasiwarga.index')->with('success', 'Warga berhasil diverifikasi!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $nik)
    {
        $warga = DataWarga::findOrFail($nik);
        $warga->delete();

        return redirect()->route('verifikasiwarga.index')->with('success', 'Data warga ditolak dan dihapus!');
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
