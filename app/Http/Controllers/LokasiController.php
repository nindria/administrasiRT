<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lokasi = Lokasi::get();
        return Inertia::render('Lokasi/Index', [
            'lokasi' => $lokasi
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Lokasi/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'perumahan' => 'required|string', // contoh: GBJ2
            'jalan' => 'required|string',     // contoh: 1
            'blok' => 'required|string',      // contoh: A1
            'nomor' => 'required|string',     // contoh: 01
            'name' => 'required|string', // contoh: Jalan GBJ2 No. 1 Blok A1
        ]);
        $id_rumah = $request->perumahan . $request->jalan . $request->blok . $request->nomor;

        Lokasi::create([
            'id_rumah'   => $id_rumah,
            'perumahan' => strtoupper($request->perumahan),
            'jalan'     => $request->jalan,
            'blok'      => strtoupper($request->blok),
            'nomor'     => $request->nomor,
            'name'      => $request->name,
        ]);

        return redirect()->route('lokasi.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lokasi = Lokasi::find($id);
        return Inertia::render('lokasi/Edit', [
            'lokasi' => $lokasi
        ]);
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
        $request->validate([
            'perumahan' => 'required|string', // contoh: GBJ2
            'jalan' => 'required|string',     // contoh: 1
            'blok' => 'required|string',      // contoh: A1
            'nomor' => 'required|string',     // contoh: 01
            'name' => 'required|string',
        ]);

        $lokasi = Lokasi::find($id);
        $lokasi->name = $request->name;
        $lokasi->save();
        return redirect()->route('lokasi.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lokasi = Lokasi::find($id);
        $lokasi->delete();
        return redirect()->route('lokasi.index');
    }
}
