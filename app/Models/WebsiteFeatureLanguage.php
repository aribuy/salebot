<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteFeatureLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_feature_id',
        'lang',
        'title',
        'description',
    ];

    protected $casts    = [
        'description' => 'array',
    ];
}
