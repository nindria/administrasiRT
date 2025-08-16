<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rumah extends Model
{
    protected $primaryKey = 'id_rumah';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id_rumah', 'perumahan', 'jalan', 'blok', 'nomor'];

    public function kartuKeluargas()
    {
        return $this->hasMany(KartuKeluarga::class, 'id_rumah', 'id_rumah');
    }

    public function dataWargas()
    {
        return $this->hasMany(DataWarga::class, 'id_rumah', 'id_rumah');
    }
}
