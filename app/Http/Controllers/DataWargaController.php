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
        // Validasi data warga
        $validatedWarga = $request->validate([
            'nik' => 'required|numeric|digits:16|unique:data_wargas',
            'full_name' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string',
            'status' => 'required|in:kepala keluarga,istri,anak',
        ]);

        // Validasi data kartu keluarga jika status kepala keluarga
        if ($request->status === 'kepala keluarga') {
            $validatedKK = $request->validate([
                'no_kk' => 'required|string|unique:kartu_keluargas',
                'jumlah_anggota' => 'required|integer|min:1',
                'foto_ktp_kepala_keluarga' => 'nullable|string',
            ]);
        }

        // Validasi data rumah
        $validatedRumah = $request->validate([
            'perumahan' => 'required|string',
            'jalan' => 'required|string',
            'blok' => 'required|string',
            'nomor' => 'required|string',
        ]);

        // Simpan data warga
        $warga = DataWarga::create($validatedWarga);

        // Simpan data kartu keluarga jika status kepala keluarga
        if ($request->status === 'kepala keluarga' && isset($validatedKK)) {
            $validatedKK['nik'] = $warga->nik;
            KartuKeluarga::create($validatedKK);
        }

        // Generate ID rumah
        $id_rumah = strtoupper(
            $validatedRumah['perumahan'] .
                $validatedRumah['jalan'] .
                $validatedRumah['blok'] .
                str_pad($validatedRumah['nomor'], 2, '0', STR_PAD_LEFT)
        );

        // Cek apakah ID rumah sudah ada
        $existingRumah = Rumah::find($id_rumah);
        if ($existingRumah) {
            // Jika ID rumah sudah ada, tambahkan suffix untuk membuat ID unik
            $counter = 1;
            $originalId = $id_rumah;
            while (Rumah::find($id_rumah)) {
                $id_rumah = $originalId . '-' . $counter;
                $counter++;
            }
        }

        // Simpan data rumah
        Rumah::create([
            'id_rumah'  => $id_rumah,
            'nik'       => $warga->nik,
            'perumahan' => strtoupper($validatedRumah['perumahan']),
            'jalan'     => $validatedRumah['jalan'],
            'blok'      => strtoupper($validatedRumah['blok']),
            'nomor'     => $validatedRumah['nomor'],
        ]);

        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $warga = DataWarga::findOrFail($id);
        $kk = null;
        $rumah = null;

        // Jika status kepala keluarga, ambil data KK
        if (strtolower($warga->status) === 'kepala keluarga') {
            $kk = KartuKeluarga::where('nik', $warga->nik)->first();
        }

        // Ambil data rumah
        $rumah = Rumah::where('nik', $warga->nik)->first();

        return Inertia::render('DataWarga/Edit', [
            'warga' => $warga,
            'kk' => $kk,
            'rumah' => $rumah
        ]);
    }

    public function update(Request $request, string $id)
    {
        $warga = DataWarga::findOrFail($id);

        // Validasi data warga
        $validatedWarga = $request->validate([
            'full_name' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string',
            'status' => 'required|in:kepala keluarga,istri,anak',
        ]);

        // Update data warga
        $warga->update($validatedWarga);

        // Update data kartu keluarga jika status kepala keluarga
        if (strtolower($request->status) === 'kepala keluarga') {
            $validatedKK = $request->validate([
                'no_kk' => 'required|string',
                'jumlah_anggota' => 'required|integer|min:1',
                'foto_ktp_kepala_keluarga' => 'nullable|string',
            ]);

            $kk = KartuKeluarga::where('nik', $warga->nik)->first();
            if ($kk) {
                $kk->update([
                    'jumlah_anggota' => $validatedKK['jumlah_anggota'],
                    'foto_ktp_kepala_keluarga' => $validatedKK['foto_ktp_kepala_keluarga'],
                ]);
            } else {
                KartuKeluarga::create([
                    'no_kk' => $validatedKK['no_kk'],
                    'nik' => $warga->nik,
                    'jumlah_anggota' => $validatedKK['jumlah_anggota'],
                    'foto_ktp_kepala_keluarga' => $validatedKK['foto_ktp_kepala_keluarga'],
                ]);
            }
        }

        // Update data rumah
        $validatedRumah = $request->validate([
            'perumahan' => 'required|string',
            'jalan' => 'required|string',
            'blok' => 'required|string',
            'nomor' => 'required|string',
        ]);

        // Generate ID rumah
        $id_rumah = strtoupper(
            $validatedRumah['perumahan'] .
                $validatedRumah['jalan'] .
                $validatedRumah['blok'] .
                str_pad($validatedRumah['nomor'], 2, '0', STR_PAD_LEFT)
        );

        $rumah = Rumah::where('nik', $warga->nik)->first();
        if ($rumah) {
            // Jika ID rumah baru berbeda dengan ID rumah lama dan ID rumah baru sudah ada di database
            if ($rumah->id_rumah !== $id_rumah && Rumah::where('id_rumah', $id_rumah)->exists()) {
                // Tambahkan suffix untuk membuat ID unik
                $counter = 1;
                $originalId = $id_rumah;
                while (Rumah::where('id_rumah', $id_rumah)->where('id_rumah', '!=', $rumah->id_rumah)->exists()) {
                    $id_rumah = $originalId . '-' . $counter;
                    $counter++;
                }
            }

            $rumah->update([
                'id_rumah'  => $id_rumah,
                'perumahan' => strtoupper($validatedRumah['perumahan']),
                'jalan'     => $validatedRumah['jalan'],
                'blok'      => strtoupper($validatedRumah['blok']),
                'nomor'     => $validatedRumah['nomor'],
            ]);
        } else {
            // Cek apakah ID rumah sudah ada
            if (Rumah::where('id_rumah', $id_rumah)->exists()) {
                // Tambahkan suffix untuk membuat ID unik
                $counter = 1;
                $originalId = $id_rumah;
                while (Rumah::where('id_rumah', $id_rumah)->exists()) {
                    $id_rumah = $originalId . '-' . $counter;
                    $counter++;
                }
            }

            Rumah::create([
                'id_rumah'  => $id_rumah,
                'nik'       => $warga->nik,
                'perumahan' => strtoupper($validatedRumah['perumahan']),
                'jalan'     => $validatedRumah['jalan'],
                'blok'      => strtoupper($validatedRumah['blok']),
                'nomor'     => $validatedRumah['nomor'],
            ]);
        }

        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        DataWarga::destroy($id);
        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil dihapus');
    }
}
