<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteCTALanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_advantage_id',
        'lang',
        'title',
        'description',
    ];
}
