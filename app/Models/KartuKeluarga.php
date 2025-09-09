<?php

namespace App\Models;

use App\Traits\HasCloudinaryImage;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\URL;

class KartuKeluarga extends Model
{
    use HasCloudinaryImage;

    protected $primaryKey = 'no_kk';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'no_kk',
        'nik', // -> foreign key
        'jumlah_anggota',
        'foto_ktp_kepala_keluarga',
        'public_id',
    ];

    public function rumah()
    {
        return $this->belongsTo(Rumah::class, 'nik', 'nik');
    }

    public function dataWarga()
    {
        return $this->belongsTo(DataWarga::class, 'nik', 'nik')->where('status', 'Kepala Keluarga');
    }

    // public function dataWarga()
    // {
    //     return $this->hasOne(DataWarga::class, 'no_kk', 'no_kk');
    // }

    // Accessor for full URL of foto_ktp_kepala_keluarga
    public function getFotoKtpKepalaKeluargaUrlAttribute()
    {
        $foto = $this->foto_ktp_kepala_keluarga;

        if (!$foto) {
            return null;
        }

        // If already a full URL (Cloudinary), return as is
        if (filter_var($foto, FILTER_VALIDATE_URL)) {
            return $foto;
        }

        // Otherwise, assume local storage path and prefix with /storage/
        return URL::to('/storage/' . ltrim($foto, '/'));
    }
}
