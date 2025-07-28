<?php

namespace App\Http\Controllers;

use App\Models\DataIpl;
use App\Models\NoRumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DataIplController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('DataIpl/Index', [
            'iplPayments' => DataIpl::with(['noRumah', 'recordedBy'])
                ->latest()
                ->get(),
            'noRumahs' => NoRumah::all()

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('DataIpl/Create', [
            'noRumahs' => NoRumah::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_rumah_id' => 'required|exists:no_rumahs,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,transfer',
            'receipt_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string',
            'status' => 'required|in:ok,reject,pending'
        ]);

        if ($request->hasFile('receipt_path')) {
            $path = $request->file('receipt_path')->store('public/receipts');
            $validated['receipt_path'] = Storage::url($path);
        }

        $validated['recorded_by'] = Auth::id();
        DataIpl::create($validated);

        return redirect()->route('dataipl.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dataipl = DataIpl::with(['noRumah', 'recordedBy'])->findOrFail($id);

        return Inertia::render('DataIpl/Show', [
            'dataIpl' => $dataipl
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataIpl $dataipl)
    {
        $noRumahs = NoRumah::select('id', 'name')->get();

        return Inertia::render('DataIpl/Edit', [
            'dataipl' => $dataipl,
            'noRumahs' => $noRumahs
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataIpl $dataipl)
    {
        $validated = $request->validate([
            'no_rumah_id' => 'required|exists:no_rumahs,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,transfer',
            'receipt_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string',
            'status' => 'required|in:ok,reject,pending',
        ]);

        if ($request->hasFile('receipt_path')) {
            if ($dataipl->receipt_path && Storage::exists($dataipl->receipt_path)) {
                Storage::delete($dataipl->receipt_path);
            }

            $validated['receipt_path'] = $request->hasFile('receipt_path') ?
                Storage::url($request->file('receipt_path')->store('public/receipts')) :
                $dataipl->receipt_path;
        }

        $dataipl->update($validated);
        return redirect()->route('dataipl.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
