<?php

namespace App\Http\Controllers;

use App\Models\DataWarga;
use App\Models\NoRumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DataWargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('DataWarga/Index', [
            'datawarga' => DataWarga::with('noRumah')->latest()->get(),
            'noRumahs' => NoRumah::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('DataWarga/Create', [
            'noRumahs' => NoRumah::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'family_card_number' => 'required|string|digits:16',
            'no_rumah_id' => 'required|exists:no_rumahs,id',
            'husband_name' => 'nullable|string|max:255',
            'husband_birth_place' => 'nullable|string|max:255',
            'husband_birth_date' => 'nullable|date',
            'wife_name' => 'nullable|string|max:255',
            'wife_birth_place' => 'nullable|string|max:255',
            'wife_birth_date' => 'nullable|date',
            'children_count' => 'nullable|integer|min:0',
            'children_data' => 'nullable|array',
            'other_family_members' => 'nullable|array',
            'other_family_members.*' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
            'residence_status' => 'required|string|max:255',
            'document' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
        ]);
        
        // Process children data
        $childrenData = [];
        if ($request->children_count > 0) {
            for ($i = 1; $i <= $request->children_count; $i++) {
                if ($request->input('child_name_'.$i) && $request->input('child_birth_date_'.$i)) {
                    $childrenData[] = [
                        'name' => $request->input('child_name_'.$i),
                        'birth_place' => $request->input('child_birth_place_'.$i),
                        'birth_date' => $request->input('child_birth_date_'.$i)
                    ];
                }
            }
        }
        $validated['children_data'] = $childrenData;
        
        // Process family members
        $validated['other_family_members'] = $request->input('other_family_members')
            ? array_filter($request->input('other_family_members'))
            : null;
        
        // Handle file upload
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('public/documents');
            $validated['document_path'] = Storage::url($path);
        }
        
        DataWarga::create($validated);
        
        return redirect()->route('datawarga.index')->with('success', 'Data warga berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
