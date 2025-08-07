<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id_rumah';
    protected $table = [
        'id_rumah',
        'perumahan',
        'jalan',
        'blok',
        'nomor',
        'name',
    ];

    public function iplPayments()
    {
        return $this->hasMany(DataIpl::class);
    }
}
