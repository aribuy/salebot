<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteUniqueFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'image',
        'title',
        'description',
    ];
    
    protected $casts    = [
        'image' => 'array',
        'icon' => 'array',

    ];


    public function languages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WebsiteUniqueFeatureLanguage::class);
    }

    public function language(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(WebsiteUniqueFeatureLanguage::class, 'website_unique_feature_id', 'id')->where('lang', app()->getLocale());
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