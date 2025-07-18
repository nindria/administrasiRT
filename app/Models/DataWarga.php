<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataWarga extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'family_card_number',
        'no_rumah_id',
        'husband_name',
        'husband_birth_place',
        'husband_birth_date',
        'wife_name',
        'wife_birth_place',
        'wife_birth_date',
        'children_count',
        'children_data',
        'other_family_members',
        'status',
        'residence_status',
        'document_path'
    ];

    protected $casts = [
        'children_data' => 'array',
        'other_family_members' => 'array',
        'husband_birth_date' => 'date',
        'wife_birth_date' => 'date',
    ];

    public function noRumah(): BelongsTo
    {
        return $this->belongsTo(NoRumah::class);
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
}