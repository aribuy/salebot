<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteStoryLanguage extends Model
{
    use HasFactory;
    protected $fillable = [
        'website_story_id',
        'lang',
        'description',
    ];
}
