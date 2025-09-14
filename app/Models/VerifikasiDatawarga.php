<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiDatawarga extends Model
{
    protected $fillable = [
        'nik',
        'full_name',
        'tempat_lahir',
        'tanggal_lahir',
        'status',
        'verifikasi_status',
        'catatan',
    ];
}
