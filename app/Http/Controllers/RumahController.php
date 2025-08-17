<?php

namespace App\Http\Controllers;

use App\Models\DataWarga;
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
        $wargas = DataWarga::where('status', 'Kepala Keluarga')->get();
        return Inertia::render('Rumah/Create', compact('wargas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'perumahan' => 'required|string',
            'nik'       => 'required|string|exists:data_wargas,nik',
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
            'nik'       => $validated['nik'],
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
    public function show(string $id_rumah)
    {
        $rumah = Rumah::with(['kepalaKeluarga', 'penghuni'])->findOrFail($id_rumah);
        return Inertia::render('Rumah/Show', compact('rumah'));
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
            'nik' => 'required|string|exists:data_wargas,nik',
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

        $rumah->update([
            'id_rumah'  => $id_rumah,
            'nik'       => $validated['nik'],
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
