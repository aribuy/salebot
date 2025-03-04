<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'images',
        'action_for',
        'status',
    ];

    protected $casts    = [
        'images' => 'array',
    ];
}
