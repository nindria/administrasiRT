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
        $wargas = DataWarga::with('rumah')->latest()->paginate(10);

        $wargas->through(function ($warga) {
            if ($warga->rumah && $warga->rumah->count() > 0) {
                // Mengambil semua blok rumah dan menggabungkannya menjadi string
                $warga->blok = $warga->rumah->map(function ($rumah) {
                    return  $rumah->blok . $rumah->nomor;
                })->implode(', ');
            } else {
                $warga->blok = '-';
            }
            return $warga;
        });

        return Inertia::render('DataWarga/Index', ['wargas' => $wargas]);
    }

    public function create()
    {
        return Inertia::render('DataWarga/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|unique:data_wargas',
            'full_name' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string',
            'status' => 'required|in:anak,istri,kepala keluarga',
        ]);

        DataWarga::create($validated);

        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $warga = DataWarga::findOrFail($id);
        $kks = KartuKeluarga::all();
        $rumahs = Rumah::all();
        return Inertia::render('DataWarga/Edit', [
            'warga' => $warga,
            'kks' => $kks,
            'noRumahs' => $rumahs
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
        ]);

        $warga->update($validated);

        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        DataWarga::destroy($id);
        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil dihapus');
    }
}
