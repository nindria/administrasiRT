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
    // Handle multiple wargas
    if ($request->has('wargas')) {
        $validated = $request->validate([
            'wargas' => 'required|array',
            'wargas.*.nik' => 'required|string|max:20|unique:data_wargas,nik',
            'wargas.*.full_name' => 'required|string|max:255',
            'wargas.*.tempat_lahir' => 'nullable|string|max:255',
            'wargas.*.tanggal_lahir' => 'nullable|date',
            'wargas.*.status' => 'nullable|string|max:255',
            'wargas.*.verification_status' => 'in:pending,verified,rejected'
        ]);

        foreach ($validated['wargas'] as $wargaData) {
            DataWarga::create($wargaData);
        }
    } 
    // Handle single warga
    else {
        $validated = $request->validate([
            'nik' => 'required|string|max:20|unique:data_wargas,nik',
            'full_name' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'status' => 'nullable|string|max:255',
            'verification_status' => 'in:pending,verified,rejected'
        ]);

        DataWarga::create($validated);
    }

    return redirect()->route('datawarga.index')
        ->with('success', 'Data warga berhasil ditambahkan.');
}

    public function storeMultiple(Request $request)
    {
        $validated = $request->validate([
            'warga' => 'required|array',
            'warga.*.nik' => 'required|string|unique:data_wargas,nik',
            'warga.*.full_name' => 'required|string',
            'warga.*.tanggal_lahir' => 'required|date',
            'warga.*.tempat_lahir' => 'required|string',
            'warga.*.status' => 'required|in:anak,istri,kepala keluarga',
            'kk' => 'sometimes|array',
            'kk.no_kk' => 'sometimes|string',
            'kk.jumlah_anggota' => 'sometimes|integer|min:1',
            'kk.foto_ktp_kepala_keluarga' => 'sometimes|string',
            'rumah' => 'sometimes|array',
            'rumah.perumahan' => 'sometimes|string',
            'rumah.jalan' => 'sometimes|string',
            'rumah.blok' => 'sometimes|string',
            'rumah.nomor' => 'sometimes|string',
        ]);

        // Create all warga entries
        $wargaEntries = [];
        foreach ($validated['warga'] as $wargaData) {
            $warga = DataWarga::create($wargaData);
            $wargaEntries[] = $warga;
        }

        // Handle KK creation if provided
        if (isset($validated['kk']) && !empty($validated['kk']['no_kk'])) {
            $kepalaKeluarga = collect($wargaEntries)->firstWhere('status', 'kepala keluarga');
            if ($kepalaKeluarga) {
                KartuKeluarga::create([
                    'no_kk' => $validated['kk']['no_kk'],
                    'nik' => $kepalaKeluarga->nik,
                    'jumlah_anggota' => $validated['kk']['jumlah_anggota'] ?? 1,
                    'foto_ktp_kepala_keluarga' => $validated['kk']['foto_ktp_kepala_keluarga'] ?? null,
                ]);
            }
        }

        // Handle Rumah creation if provided
        if (isset($validated['rumah']) && !empty($validated['rumah']['jalan'])) {
            $kepalaKeluarga = collect($wargaEntries)->firstWhere('status', 'kepala keluarga');
            if ($kepalaKeluarga) {
                Rumah::create([
                    'nik' => $kepalaKeluarga->nik,
                    'perumahan' => $validated['rumah']['perumahan'] ?? 'GBJ2',
                    'jalan' => $validated['rumah']['jalan'],
                    'blok' => $validated['rumah']['blok'] ?? null,
                    'nomor' => $validated['rumah']['nomor'] ?? null,
                ]);
            }
        }

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
