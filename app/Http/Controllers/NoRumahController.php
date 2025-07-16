<?php

namespace App\Http\Controllers;

use App\Models\NoRumah;
use Illuminate\Http\Request;
use Inertia\Inertia;


class NoRumahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $norumah = NoRumah::get();
        return Inertia::render('NoRumah/Index',[
            'norumah' => $norumah
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

        NoRumah::create([
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
        $rumah = NoRumah::find($id);
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

        $rumah = NoRumah::find($id);
        $rumah->name = $request->name;
        $rumah->save();
        return redirect()->route('norumah.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rumah = NoRumah::find($id);
        $rumah->delete();
        return redirect()->route('norumah.index');
    }
}
