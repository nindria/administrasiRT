<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rumah extends Model
{
    protected $primaryKey = 'id_rumah';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_rumah',
        'nik', // -> foreign key 
        'perumahan',
        'jalan',
        'blok',
        'nomor',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            if (empty($model->id_rumah)) {
                $model->id_rumah = strtoupper(
                    $model->perumahan .
                        $model->jalan .
                        $model->blok .
                        str_pad($model->nomor, 2, '0', STR_PAD_LEFT)
                );
            }
        });
    }

    // public function dataWarga()
    // {
    //     return $this->hasMany(DataWarga::class, 'id_rumah', 'id_rumah');
    // }

    public function dataWarga()
    {
        return $this->belongsTo(DataWarga::class, 'nik', 'nik');
    }

    // public function kartuKeluarga()
    // {
    //     return $this->hasOne(KartuKeluarga::class, 'id_rumah', 'id_rumah');
    // }
}
