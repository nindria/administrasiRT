<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KartuKeluarga extends Model
{
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_kk',
        'nik_kepala_keluarga',
        'nama_kepala_keluarga',
        'jumlah_anggota',
        'foto_ktp_kepala_keluarga',
        'id_rumah'
    ];

    public function rumah()
    {
        return $this->belongsTo(Rumah::class, 'id_rumah', 'id_rumah');
    }

    public function dataWargas()
    {
        return $this->hasMany(DataWarga::class, 'no_kk', 'no_kk');
    }
}
