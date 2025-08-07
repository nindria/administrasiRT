<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KartuKeluarga extends Model
{
    protected $fillable =[
        'no_kk',
        'nama_kepala_keluarga',
        'no_rumah',
    ];

    public function iplPayments()
    {
        return $this->hasMany(DataIpl::class);
    }
}
