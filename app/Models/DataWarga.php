<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataWarga extends Model
{
    protected $fillable = [
        'full_name',
        'family_card_number',
        'house_number',
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
    
}
