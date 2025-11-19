<?php

namespace App\Http\Controllers;

use App\Models\DataWarga;
use App\Models\KartuKeluarga;
use App\Models\Rumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DataWargaController extends Controller
{
    public function index()
    {
        $query = DataWarga::with('rumah');

        // Filter berdasarkan NIK user jika role warga
        $user = Auth::user();
        if ($user && $user->role === \App\UserRole::Warga && $user->nik) {
            $query->where('nik', $user->nik);
        }

        $wargas = $query->latest()->paginate(10);

        $wargas->through(function ($warga) {
            if ($warga->rumah && $warga->rumah->count() > 0) {
                // Mengambil semua blok rumah dan menggabungkannya menjadi string
                $warga->blok = $warga->rumah->map(function ($rumah) {
                    return $rumah->blok.$rumah->nomor;
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
                'wargas.*.status_kependukan' => 'nullable|in:tetap,tidak tetap',
                'wargas.*.verification_status' => 'in:pending,verified,rejected',
            ]);

            foreach ($validated['wargas'] as $wargaData) {
                $warga = DataWarga::create($wargaData);
                // Create rumah if provided
                if (isset($wargaData['rumah']) && !empty($wargaData['rumah']['jalan'])) {
                    Rumah::create([
                        'nik' => $warga->nik,
                        'perumahan' => $wargaData['rumah']['perumahan'] ?? 'GBJ2',
                        'jalan' => $wargaData['rumah']['jalan'],
                        'blok' => $wargaData['rumah']['blok'] ?? null,
                        'nomor' => $wargaData['rumah']['nomor'] ?? null,
                    ]);
                }
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
                'status_kependukan' => 'nullable|in:tetap,tidak tetap',
                'verification_status' => 'in:pending,verified,rejected',
                'rumah' => 'nullable|array',
                'rumah.perumahan' => 'nullable|string',
                'rumah.jalan' => 'nullable|string',
                'rumah.blok' => 'nullable|string',
                'rumah.nomor' => 'nullable|string',
            ]);

            $warga = DataWarga::create($validated);

            // Create rumah if provided
            if (isset($validated['rumah']) && !empty($validated['rumah']['jalan'])) {
                Rumah::create([
                    'nik' => $warga->nik,
                    'perumahan' => $validated['rumah']['perumahan'] ?? 'GBJ2',
                    'jalan' => $validated['rumah']['jalan'],
                    'blok' => $validated['rumah']['blok'] ?? null,
                    'nomor' => $validated['rumah']['nomor'] ?? null,
                ]);
            }
        }

        return redirect()->route('datawarga.index')
            ->with('success', 'Data warga berhasil ditambahkan.');
    }

    public function storeMultiple(Request $request)
    {
        $validated = $request->validate([
            'wargas' => 'required|array',
            'wargas.*.nik' => 'required|string|unique:data_wargas,nik',
            'wargas.*.full_name' => 'required|string',
            'wargas.*.tanggal_lahir' => 'required|date',
            'wargas.*.tempat_lahir' => 'required|string',
            'wargas.*.status' => 'required|in:kepala keluarga,istri,anak',
            'wargas.*.status_kependukan' => 'nullable|in:tetap,tidak tetap',
            'kk' => 'nullable|array',
            'kk.no_kk' => 'nullable|string',
            'kk.jumlah_anggota' => 'nullable|integer|min:1',
            'kk.foto_ktp_kepala_keluarga' => 'nullable|file|image|max:2048',
            'rumah' => 'nullable|array',
            'rumah.perumahan' => 'nullable|string',
            'rumah.jalan' => 'nullable|string',
            'rumah.blok' => 'nullable|string',
            'rumah.nomor' => 'nullable|string',
        ]);

        // Create all warga entries
        $wargaEntries = [];
        foreach ($validated['wargas'] as $wargaData) {
            $warga = DataWarga::create([
                'nik' => $wargaData['nik'],
                'full_name' => $wargaData['full_name'],
                'tempat_lahir' => $wargaData['tempat_lahir'],
                'tanggal_lahir' => $wargaData['tanggal_lahir'],
                'status' => $wargaData['status'],
                'status_kependukan' => $wargaData['status_kependukan'] ?? null,
                'verification_status' => $wargaData['verification_status'] ?? 'pending',
            ]);
            $wargaEntries[] = $warga;
        }

        // Handle KK creation if provided
        if (isset($validated['kk']) && ! empty($validated['kk']['no_kk'])) {
            $kepalaKeluarga = collect($wargaEntries)->firstWhere('status', 'kepala keluarga');
            if ($kepalaKeluarga) {
                // Check if KK already exists
                $existingKK = KartuKeluarga::where('no_kk', $validated['kk']['no_kk'])->first();

                if (!$existingKK) {
                    $kkData = [
                        'no_kk' => $validated['kk']['no_kk'],
                        'nik' => $kepalaKeluarga->nik,
                        'jumlah_anggota' => $validated['kk']['jumlah_anggota'] ?? 1,
                    ];

                    // Handle file upload for foto_ktp_kepala_keluarga
                    if (isset($validated['kk']['foto_ktp_kepala_keluarga']) && $validated['kk']['foto_ktp_kepala_keluarga']) {
                        // Upload to Cloudinary using the model's trait method
                        $kkModel = new KartuKeluarga();
                        $uploadResult = $kkModel->uploadToCloudinary($validated['kk']['foto_ktp_kepala_keluarga'], 'kk-photos', 'foto_ktp_kepala_keluarga');

                        $kkData['foto_ktp_kepala_keluarga'] = $uploadResult['url'] ?? null;
                        $kkData['public_id'] = $uploadResult['public_id'] ?? null;
                    }

                    KartuKeluarga::create($kkData);
                }
            }
        }

        // Handle Rumah creation if provided
        if (isset($validated['rumah']) && ! empty($validated['rumah']['jalan'])) {
            $kepalaKeluarga = collect($wargaEntries)->firstWhere('status', 'kepala keluarga');
            if ($kepalaKeluarga) {
                // Check if rumah already exists for this NIK
                $existingRumah = Rumah::where('nik', $kepalaKeluarga->nik)->first();

                if (!$existingRumah) {
                    $rumahData = [
                        'nik' => $kepalaKeluarga->nik,
                        'perumahan' => $validated['rumah']['perumahan'] ?? 'GBJ2',
                        'jalan' => $validated['rumah']['jalan'],
                        'blok' => $validated['rumah']['blok'] ?? null,
                        'nomor' => $validated['rumah']['nomor'] ?? null,
                    ];

                    Rumah::create($rumahData);
                }
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
            'noRumahs' => $rumahs,
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
            'status_kependukan' => 'nullable|in:tetap,tidak tetap',
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
