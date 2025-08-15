<?php

namespace App\Http\Controllers;

use App\Models\DataWarga;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DataWargaController extends Controller
{
    public function index()
    {
        return Inertia::render('DataWarga/Index', [
            'datawarga' => DataWarga::with('Lokasi')->latest()->get(),
            'Lokasis' => Lokasi::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('DataWarga/Create', [
            'Lokasis' => Lokasi::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            // Lokasi
            'kode_perumahan' => 'required|string', // GBJ2
            'jalan' => 'required|string',
            'blok' => 'required|string',
            'nomor' => 'required|string',
            'name' => 'required|string', // Pemilik rumah

            // Data Warga
            'nik' => 'required|string|unique:data_wargas,nik',
            'full_name' => 'required|string',
            'family_card_number' => 'required|string',
            'residence_status' => 'required|string',
        ]);

        // Generate ID Rumah unik
        $id_rumah = strtoupper($request->kode_perumahan . $request->jalan . $request->blok . $request->nomor);

        // Simpan ke tabel lokasi (lokasis)
        $lokasi = Lokasi::firstOrCreate(
            ['id_rumah' => $id_rumah],
            [
                'kode_perumahan' => strtoupper($request->kode_perumahan),
                'jalan' => $request->jalan,
                'blok' => strtoupper($request->blok),
                'nomor' => $request->nomor,
                'name' => $request->name,
            ]
        );

        // Simpan ke tabel data warga
        DataWarga::create([
            'nik' => $request->nik,
            'full_name' => $request->full_name,
            'family_card_number' => $request->family_card_number,
            'no_rumah_id' => $id_rumah,
            'residence_status' => $request->residence_status,
        ]);

        return redirect()->route('datawarga.index')->with('success', 'Data warga dan lokasi berhasil disimpan.');
    }

    public function edit(string $nik)
    {
        $dataWarga = DataWarga::findOrFail($nik);

        $warga = DataWarga::with('lokasi')->where('nik', $nik)->firstOrFail();
        return Inertia::render('DataWarga/Edit', [
            'warga' => $warga,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $this->validateForm($request);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('public/documents');
            $validated['document_path'] = Storage::url($path);
        }
        $validated['children_count'] = count($validated['children_data'] ?? []);
        // Ubah array menjadi JSON
        $validated['children_data'] = json_encode($validated['children_data'] ?? []);
        $validated['other_family_members'] = json_encode($validated['other_family_members'] ?? []);


        $dataWarga = DataWarga::findOrFail($id);
        $dataWarga->update($validated);

        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $dataWarga = DataWarga::findOrFail($id);

        try {
            // More robust document path handling
            if ($dataWarga->getRawOriginal('document_path')) {
                $path = str_replace('/storage/', 'public/', $dataWarga->getRawOriginal('document_path'));
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }

            $dataWarga->delete();

            return redirect()->route('datawarga.index')
                ->with('success', 'Data warga berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data warga: ' . $e->getMessage());
        }
    }

    private function validateForm(Request $request): array
    {
        return $request->validate([
            'full_name' => 'required|string|max:255',
            'family_card_number' => 'required|string|digits:16',
            'no_rumah_id' => 'required|exists:no_rumahs,id',
            'husband_birth_place' => 'nullable|string|max:255',
            'husband_birth_date' => 'nullable|date',
            'married_status' => 'required|in:menikah,belum_menikah',
            'wife_name' => 'nullable|required_if:married_status,menikah|string|max:255',
            'wife_birth_place' => 'nullable|required_if:married_status,menikah|string|max:255',
            'wife_birth_date' => 'nullable|required_if:married_status,menikah|date',
            'children_data' => 'nullable|array',
            'children_data.*.name' => 'required_with:children_data|string|max:255',
            'children_data.*.birth_place' => 'required_with:children_data|string|max:255',
            'children_data.*.birth_date' => 'required_with:children_data|date',
            'other_family_members' => 'nullable|array',
            'other_family_members.*.name' => 'required_with:other_family_members|string|max:255',
            'other_family_members.*.status' => 'required_with:other_family_members|string|max:255',
            'residence_status' => 'required|in:tetap,tidak_tetap',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);
    }
}
