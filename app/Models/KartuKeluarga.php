<?php

namespace App\Models;

use App\Traits\HasCloudinaryImage;
use Illuminate\Database\Eloquent\Model;

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
}
