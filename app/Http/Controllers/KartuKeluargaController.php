<?php

namespace App\Http\Controllers;


use App\Models\DataWarga;
use App\Models\KartuKeluarga;
use App\Models\Rumah;
use Illuminate\Http\Request;
use Inertia\Inertia;


class KartuKeluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kk = KartuKeluarga::with('rumah')->latest()->paginate(10);
        return Inertia::render('KartuKeluarga/Index', [
            'kk' => $kk
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wargas = DataWarga::all(); // untuk pilih kepala keluarga
        return Inertia::render('KartuKeluarga/Create', compact('wargas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_kk' => 'required|string|unique:kartu_keluargas',
            'nik_kepala' => 'required|string|exists:data_wargas,nik',
            'nama_kepala_keluarga' => 'required|string',
            'jumlah_anggota' => 'required|integer|min:1',
            'foto_ktp_kepala_keluarga' => 'nullable|string',
            'id_rumah' => 'required|string|exists:rumahs,id_rumah'
        ]);

        KartuKeluarga::create($validated);

        return redirect()->route('kk.index')->with('success', 'Kartu Keluarga berhasil ditambahkan');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $no_kk)
    {
        $kk = KartuKeluarga::with(['kepalaKeluarga', 'anggota'])->findOrFail($no_kk);
        return Inertia::render('KartuKeluarga/Show', compact('kk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kk = KartuKeluarga::findOrFail($id);
        $rumahs = Rumah::all();
        return Inertia::render('KartuKeluarga/Edit', [
            'kk' => $kk,
            'rumahs' => $rumahs
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kk = KartuKeluarga::findOrFail($id);
        $validated = $request->validate([
            'nik_kepala_keluarga' => 'required|string',
            'nama_kepala_keluarga' => 'required|string',
            'jumlah_anggota' => 'required|integer|min:1',
            'foto_ktp_kepala_keluarga' => 'nullable|string',
            'id_rumah' => 'required|string|exists:rumahs,id_rumah'
        ]);

        $kk->update($validated);

        return redirect()->route('kk.index')->with('success', 'Kartu Keluarga berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        KartuKeluarga::destroy($id);
        return redirect()->route('kk.index')->with('success', 'Kartu Keluarga berhasil dihapus');
    }
}
