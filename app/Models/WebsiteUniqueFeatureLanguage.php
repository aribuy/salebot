<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteUniqueFeatureLanguage extends Model
{
    use HasFactory;
    protected $fillable = [
        'website_unique_feature_id',
        'lang',
        'title',
        'description',
    ];
    
}
