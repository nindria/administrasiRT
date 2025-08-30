<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $event = Event::get();

        return Inertia::render('Event/Index', [
            'event' => $event,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Event/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'required',
            'description' => 'required',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'image' => null,
            'public_id' => null,
        ];

        if ($request->hasFile('image')) {
            $cloudinaryService = new CloudinaryService;
            $uploadResult = $cloudinaryService->uploadImage($request->file('image'), 'event-images');

            // Check if upload was successful
            if (! $uploadResult['url']) {
                return redirect()->back()->with('error', 'Gagal mengunggah gambar ke Cloudinary. Silakan coba lagi.');
            }

            $data['image'] = $uploadResult['url'];
            $data['public_id'] = $uploadResult['public_id'];
        }

        Event::create($data);

        return redirect()->route('events.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::findOrFail($id);

        return Inertia::render('Event/Show', [
            'event' => $event,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = Event::findOrFail($id);

        return Inertia::render('Event/Edit', [
            'event' => $event,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'required',
            'description' => 'required',
        ]);

        $event = Event::findOrFail($id);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            // Delete old image from Cloudinary if exists
            if ($event->public_id) {
                $cloudinaryService = new CloudinaryService;
                $cloudinaryService->deleteImage($event->public_id);
            }

            // Upload new image to Cloudinary
            $cloudinaryService = new CloudinaryService;
            $uploadResult = $cloudinaryService->uploadImage($request->file('image'), 'event-images');

            // Check if upload was successful
            if (! $uploadResult['url']) {
                return redirect()->back()->with('error', 'Gagal mengunggah gambar ke Cloudinary. Silakan coba lagi.');
            }

            $data['image'] = $uploadResult['url'];
            $data['public_id'] = $uploadResult['public_id'];
        }

        $event->update($data);

        return redirect()->route('events.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);

        // Delete image from Cloudinary if exists
        if ($event->public_id) {
            $cloudinaryService = new CloudinaryService;
            $cloudinaryService->deleteImage($event->public_id);
        }

        $event->delete();

        return redirect()->route('events.index');
    }
}
