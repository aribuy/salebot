<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'description',
        'type',
        'icon',
    ];

    protected $casts    = [
        'image'       => 'array',
        'description' => 'array',
    ];


    public function languages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WebsiteFeatureLanguage::class);
    }

    public function language(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(WebsiteFeatureLanguage::class)->where('lang', app()->getLocale());
    }


    public function getLangTitleAttribute()
    {
        return $this->language ? $this->language->title : $this->title;
    }

    public function getLangDescriptionAttribute()
    {
        return $this->language ? $this->language->description : $this->description;
    }
}
