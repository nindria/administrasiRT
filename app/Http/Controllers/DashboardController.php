<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\DataWarga;
use App\Models\Event;
use App\Models\KartuKeluarga;
use App\Models\Rumah;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics.
     */
    public function index()
    {
        // Collect statistics from various models
        $stats = [
            'total_warga' => DataWarga::count(),
            'total_kartu_keluarga' => KartuKeluarga::count(),
            'total_rumah' => Rumah::count(),
            'total_event' => Event::count(),
            'total_banner' => Banner::count(),
            'total_verifikasi_pending' => DataWarga::where('verification_status', 'pending')->count(),
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
        ]);
    }
}
