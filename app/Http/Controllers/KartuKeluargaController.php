<?php

namespace App\Http\Controllers;

use App\Models\DataWarga;
use App\Models\KartuKeluarga;
use App\Models\Rumah;
use App\Services\CloudinaryService;
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
            'kartukeluarga' => $kartukeluarga,
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
            'foto_ktp_kepala_keluarga' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'no_kk' => $validated['no_kk'],
            'nik' => $validated['nik'],
            'jumlah_anggota' => $validated['jumlah_anggota'],
            'foto_ktp_kepala_keluarga' => null,
            'public_id' => null,
        ];

        if ($request->hasFile('foto_ktp_kepala_keluarga')) {
            $cloudinaryService = new CloudinaryService;
            $uploadResult = $cloudinaryService->uploadImage($request->file('foto_ktp_kepala_keluarga'), 'kartu-keluarga-images');

            // Check if upload was successful
            if (! $uploadResult['url']) {
                return redirect()->back()->with('error', 'Gagal mengunggah foto KTP ke Cloudinary. Silakan coba lagi.');
            }

            $data['foto_ktp_kepala_keluarga'] = $uploadResult['url'];
            $data['public_id'] = $uploadResult['public_id'];
        }

        KartuKeluarga::create($data);

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
            'rumahs' => $rumahs,
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
            'foto_ktp_kepala_keluarga' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'nik' => $validated['nik'],
            'jumlah_anggota' => $validated['jumlah_anggota'],
        ];

        if ($request->hasFile('foto_ktp_kepala_keluarga')) {
            // Delete old image from Cloudinary if exists
            if ($kartukeluarga->public_id) {
                $cloudinaryService = new CloudinaryService;
                $cloudinaryService->deleteImage($kartukeluarga->public_id);
            }

            // Upload new image to Cloudinary
            $cloudinaryService = new CloudinaryService;
            $uploadResult = $cloudinaryService->uploadImage($request->file('foto_ktp_kepala_keluarga'), 'kartu-keluarga-images');

            // Check if upload was successful
            if (! $uploadResult['url']) {
                return redirect()->back()->with('error', 'Gagal mengunggah foto KTP ke Cloudinary. Silakan coba lagi.');
            }

            $data['foto_ktp_kepala_keluarga'] = $uploadResult['url'];
            $data['public_id'] = $uploadResult['public_id'];
        }

        $kartukeluarga->update($data);

        return redirect()->route('kartukeluarga.index')->with('success', 'Kartu Keluarga berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kartukeluarga = KartuKeluarga::findOrFail($id);

        // Delete image from Cloudinary if exists
        if ($kartukeluarga->public_id) {
            $cloudinaryService = new CloudinaryService;
            $cloudinaryService->deleteImage($kartukeluarga->public_id);
        }

        $kartukeluarga->delete();

        return redirect()->route('kartukeluarga.index')->with('success', 'Kartu Keluarga berhasil dihapus');
    }
}
