<?php

namespace App\Http\Controllers;

use App\Models\Rumah;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RumahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rumahs = Rumah::latest()->paginate(10);
        return Inertia::render('Rumah/Index', [
            'rumahs' => $rumahs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Rumah/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_rumah' => 'required|string|unique:rumahs',
            'perumahan' => 'required|string',
            'jalan' => 'required|string',
            'blok' => 'required|string',
            'nomor' => 'required|string',
        ]);

        $id_rumah = strtoupper(
            $validated['perumahan'] .
                $validated['jalan'] .
                $validated['blok'] .
                str_pad($validated['nomor'], 2, '0', STR_PAD_LEFT)
        );
        Rumah::create([
            'id_rumah'  => $id_rumah,
            'perumahan' => strtoupper($validated['perumahan']),
            'jalan'     => $validated['jalan'],
            'blok'      => strtoupper($validated['blok']),
            'nomor'     => $validated['nomor'],
        ]);

        return redirect()->route('rumah.index')->with('success', 'Data rumah berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lokasi = Rumah::find($id);
        return Inertia::render('lokasi/Edit', [
            'lokasi' => $lokasi
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rumah = Rumah::findOrFail($id);
        return Inertia::render('Rumah/Edit', [
            'rumah' => $rumah
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rumah = Rumah::findOrFail($id);
        $validated = $request->validate([
            'perumahan' => 'required|string',
            'jalan' => 'required|string',
            'blok' => 'required|string',
            'nomor' => 'required|string',
        ]);
        
        $id_rumah = strtoupper(
            $validated['perumahan'] .
                $validated['jalan'] .
                $validated['blok'] .
                str_pad($validated['nomor'], 2, '0', STR_PAD_LEFT)
        );
        Rumah::update([
            'id_rumah'  => $id_rumah,
            'perumahan' => strtoupper($validated['perumahan']),
            'jalan'     => $validated['jalan'],
            'blok'      => strtoupper($validated['blok']),
            'nomor'     => $validated['nomor'],
        ]);

        return redirect()->route('rumah.index')->with('success', 'Data rumah berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Rumah::destroy($id);
        return redirect()->route('rumah.index')->with('success', 'Data rumah berhasil dihapus');
    }
}
