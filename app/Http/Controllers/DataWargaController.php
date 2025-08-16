<?php

namespace App\Http\Controllers;

use App\Models\DataWarga;
use App\Models\KartuKeluarga;
use App\Models\Rumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DataWargaController extends Controller
{
    public function index()
    {
        $wargas = DataWarga::with(['kartuKeluarga', 'rumah'])->latest()->paginate(10);
        return Inertia::render('DataWarga/Index', [
            'wargas' => $wargas
        ]);
    }

    public function create()
    {
        $kks = KartuKeluarga::all();
        $rumahs = Rumah::all();
        return Inertia::render('DataWarga/Create', [
            'kks' => $kks,
            'rumahs' => $rumahs
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|unique:data_wargas',
            'full_name' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string',
            'status' => 'required|in:anak,istri,kepala keluarga',
            'no_kk' => 'required|string|exists:kartu_keluargas,no_kk',
            'id_rumah' => 'required|string|exists:rumahs,id_rumah'
        ]);

        DataWarga::create($validated);

        return redirect()->route('warga.index')->with('success', 'Data warga berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $warga = DataWarga::findOrFail($id);
        $kks = KartuKeluarga::all();
        $rumahs = Rumah::all();
        return Inertia::render('DataWarga/Edit', [
            'warga' => $warga,
            'kks' => $kks,
            'rumahs' => $rumahs
        ]);
    }

    public function update(Request $request, string $id)
    {
        $warga = DataWarga::findOrFail($id);
        $validated = $request->validate([
            'full_name' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string',
            'status' => 'required|in:anak,istri,kepala keluarga',
            'no_kk' => 'required|string|exists:kartu_keluargas,no_kk',
            'id_rumah' => 'required|string|exists:rumahs,id_rumah'
        ]);

        $warga->update($validated);

        return redirect()->route('warga.index')->with('success', 'Data warga berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        DataWarga::destroy($id);
        return redirect()->route('warga.index')->with('success', 'Data warga berhasil dihapus');
    }

    
}
