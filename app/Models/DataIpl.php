<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataIpl extends Model
{
    protected $fillable = [
        'no_rumah_id',
        'amount',
        'payment_date',
        'payment_method',
        'receipt_path',
        'notes',
        'status',
        'recorded_by'
    ];

    public function noRumah() 
    {
        return $this->belongsTo(NoRumah::class);
    }

    public function recordedBy() 
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    
}
