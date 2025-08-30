<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataWarga extends Model
{
    protected $primaryKey = 'nik';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'full_name',
        'tempat_lahir',
        'tanggal_lahir',
        'status',
        'is_warga',
        'verification_status',
        'verified_by',
        'verified_at',
        'rejection_reason',
    ];

    public function getRouteKeyName()
    {
        return 'nik'; //  {verifikasiwarga} pake nik, bukan id
    }

    public function rumah()
    {
        return $this->hasMany(Rumah::class, 'nik', 'nik');
    }

    // public function kartuKeluarga()
    // {
    //     return $this->belongsTo(KartuKeluarga::class, 'no_kk', 'no_kk');
    // }
}
