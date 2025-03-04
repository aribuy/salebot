<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteStory extends Model
{
    use HasFactory;


    protected $fillable = [
        'description',
        'image',
    ];

    protected $casts    = [
        'image' => 'array',
    ];

    public function languages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WebsiteStoryLanguage::class);
    }

    public function language(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(WebsiteStoryLanguage::class, 'website_story_id', 'id')->where('lang', app()->getLocale());
    }

    public function getLangDescriptionAttribute()
    {
        return $this->language ? $this->language->description : $this->description;
    }
}
