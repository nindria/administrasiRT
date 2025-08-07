<?php

namespace App\Http\Controllers;


use App\Models\KartuKeluarga;
use Illuminate\Http\Request;
use Inertia\Inertia;


class KartuKeluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kartukeluarga = KartuKeluarga::get();
        return Inertia::render('KartuKeluarga/Index',[
            'kartukeluarga' => $kartukeluarga
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('NoRumah/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        KartuKeluarga::create([
            'name' => $request->name,
        ]);

        return redirect()->route('norumah.index');
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
        $rumah = KartuKeluarga::find($id);
        return Inertia::render('NoRumah/Edit',[
            'rumah' => $rumah
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $rumah = KartuKeluarga::find($id);
        $rumah->name = $request->name;
        $rumah->save();
        return redirect()->route('norumah.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rumah = KartuKeluarga::find($id);
        $rumah->delete();
        return redirect()->route('norumah.index');
    }
}
