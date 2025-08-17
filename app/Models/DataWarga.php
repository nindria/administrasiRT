<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

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
    ];

    public function rumah()
    {
        return $this->hasMany(Rumah::class, 'nik', 'nik');
    }

    // public function kartuKeluarga()
    // {
    //     return $this->belongsTo(KartuKeluarga::class, 'no_kk', 'no_kk');
    // }
}
