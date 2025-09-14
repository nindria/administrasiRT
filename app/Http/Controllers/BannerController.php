<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::latest()->get();

        return Inertia::render('Banner/Index', [
            'banners' => $banners,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Banner/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'required',
            'description' => 'nullable',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->is_active ?? false,
            'image' => null,
            'public_id' => null,
        ];

        if ($request->hasFile('image')) {
            $cloudinaryService = new CloudinaryService;
            $uploadResult = $cloudinaryService->uploadImage($request->file('image'), 'banner-images');

            // Check if upload was successful
            if (! $uploadResult['url']) {
                return redirect()->back()->with('error', 'Gagal mengunggah gambar ke Cloudinary. Silakan coba lagi.');
            }

            $data['image'] = $uploadResult['url'];
            $data['public_id'] = $uploadResult['public_id'];
        }

        Banner::create($data);

        return redirect()->route('banners.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $banner = Banner::findOrFail($id);

        return Inertia::render('Banner/Show', [
            'banner' => $banner,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $banner = Banner::findOrFail($id);

        return Inertia::render('Banner/Edit', [
            'banner' => $banner,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'required',
            'description' => 'nullable',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->is_active ?? false,
        ];

        if ($request->hasFile('image')) {
            // Delete old image from Cloudinary if exists
            if ($banner->public_id) {
                $cloudinaryService = new CloudinaryService;
                $cloudinaryService->deleteImage($banner->public_id);
            }

            // Upload new image to Cloudinary
            $cloudinaryService = new CloudinaryService;
            $uploadResult = $cloudinaryService->uploadImage($request->file('image'), 'banner-images');

            // Check if upload was successful
            if (! $uploadResult['url']) {
                return redirect()->back()->with('error', 'Gagal mengunggah gambar ke Cloudinary. Silakan coba lagi.');
            }

            $data['image'] = $uploadResult['url'];
            $data['public_id'] = $uploadResult['public_id'];
        }

        $banner->update($data);

        return redirect()->route('banners.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::findOrFail($id);

        // Delete image from Cloudinary if exists
        if ($banner->public_id) {
            $cloudinaryService = new CloudinaryService;
            $cloudinaryService->deleteImage($banner->public_id);
        }

        $banner->delete();

        return redirect()->route('banners.index');
    }

    /**
     * Get active banner for landing page
     */
    public function getActiveBanner()
    {
        $banner = Banner::active()->latest()->first();

        return response()->json($banner);
    }
}
