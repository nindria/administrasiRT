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
        $kartukeluarga = KartuKeluarga::with('dataWarga')->latest()->paginate(10);
        return Inertia::render('KartuKeluarga/Index', [
            'kartukeluarga' => $kartukeluarga
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
            'nik' => 'required|exists:data_wargas,nik',
            'jumlah_anggota' => 'required|integer|min:1',
            'foto_ktp_kepala_keluarga' => 'nullable|string',
        ]);


        KartuKeluarga::create($validated);

        return redirect()->route('kartukeluarga.index')->with('success', 'Kartu Keluarga berhasil ditambahkan');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $no_kk)
    {
        $kartukeluarga = KartuKeluarga::with(['dataWarga', 'datawarga'])->findOrFail($no_kk);
        return Inertia::render('KartuKeluarga/Show', compact('kartukeluarga'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kartukeluarga = KartuKeluarga::findOrFail($id);
        $rumahs = Rumah::all();
        return Inertia::render('KartuKeluarga/Edit', [
            'kartukeluarga' => $kartukeluarga,
            'rumahs' => $rumahs
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kartukeluarga = KartuKeluarga::findOrFail($id);
        $validated = $request->validate([
            'nik' => 'required|exists:data_wargas,nik',
            'jumlah_anggota' => 'required|integer|min:1',
            'foto_ktp_kepala_keluarga' => 'nullable|string',
        ]);

        $kartukeluarga->update($validated);

        return redirect()->route('kartukeluarga.index')->with('success', 'Kartu Keluarga berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        KartuKeluarga::destroy($id);
        return redirect()->route('kartukeluarga.index')->with('success', 'Kartu Keluarga berhasil dihapus');
    }
}
