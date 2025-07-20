<?php

namespace App\Http\Controllers;

use App\Models\DataWarga;
use App\Models\NoRumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DataWargaController extends Controller
{
    public function index()
    {
        return Inertia::render('DataWarga/Index', [
            'datawarga' => DataWarga::with('noRumah')->latest()->get(),
            'noRumahs' => NoRumah::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('DataWarga/Create', [
            'noRumahs' => NoRumah::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateForm($request);

        // Handle file upload
        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents');
        }

        // Format data untuk disimpan
        $data = [
            'full_name' => $validated['full_name'],
            'family_card_number' => $validated['family_card_number'],
            'no_rumah_id' => $validated['no_rumah_id'],
            'husband_birth_place' => $validated['husband_birth_place'] ?? null,
            'husband_birth_date' => $validated['husband_birth_date'] ?? null,
            'married_status' => $validated['married_status'],
            'wife_name' => $validated['married_status'] === 'menikah' ? $validated['wife_name'] : null,
            'wife_birth_place' => $validated['married_status'] === 'menikah' ? $validated['wife_birth_place'] : null,
            'wife_birth_date' => $validated['married_status'] === 'menikah' ? $validated['wife_birth_date'] : null,
            'children_count' => count($validated['children_data'] ?? []),
            'children_data' => json_encode($validated['children_data'] ?? []),
            'other_family_members' => json_encode($validated['other_family_members'] ?? []),
            'residence_status' => $validated['residence_status'],
            'document_path' => $documentPath
        ];

        DataWarga::create($data);

        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil disimpan!');
    }   

    public function edit(string $id)
    {
        $dataWarga = DataWarga::findOrFail($id);

        return Inertia::render('DataWarga/Edit', [
            'warga' => $dataWarga,
            'noRumahs' => NoRumah::all()
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $this->validateForm($request);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('public/documents');
            $validated['document_path'] = Storage::url($path);
        }

        $dataWarga = DataWarga::findOrFail($id);
        $dataWarga->update($validated);

        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $dataWarga = DataWarga::findOrFail($id);

        if ($dataWarga->document_path) {
            Storage::delete(str_replace('/storage/', 'public/', $dataWarga->document_path));
        }

        $dataWarga->delete();

        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil dihapus!');
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
