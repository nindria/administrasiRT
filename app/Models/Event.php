<?php

namespace App\Models;

use App\Traits\HasCloudinaryImage;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasCloudinaryImage;

    protected $fillable = [
        'image',
        'title',
        'description',
        'public_id',
    ];
}
