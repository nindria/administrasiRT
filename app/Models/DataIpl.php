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
        'recorded_by',
        'verification_status',
        'rejection_reason',
        'verified_by',
        'verified_at'
    ];

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function Lokasi() 
    {
        return $this->belongsTo(Lokasi::class, 'no_rumah_id');
    }

    public function recordedBy() 
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    
}
