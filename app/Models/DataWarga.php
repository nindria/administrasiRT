<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DataWarga extends Model
{
    use HasFactory;

    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'nik',
        'full_name',
        'no_rumah_id'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'children_data' => 'array',
        'other_family_members' => 'array',
        'husband_birth_date' => 'date',
        'wife_birth_date' => 'date',
    ];

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'no_rumah_id', 'id_rumah');
    }

    public function getAgeGroup($birthDate)
    {
        if (!$birthDate) return null;

        $age = now()->diffInYears($birthDate);

        if ($age < 1) return '0-12 months';
        if ($age >= 1 && $age <= 12) return '1-12 years';
        if ($age >= 12 && $age <= 20) return '12-20 years';
        if ($age >= 21 && $age <= 35) return '21-35 years';
        return '35+ years';
    }

    public function getDocumentPathAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }
}
