<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteAdvantage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'description',
    ];

    protected $casts    = [
        'image' => 'array',
    ];


    public function languages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WebsiteAdvantageLanguage::class);
    }
    
    public function language(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(WebsiteAdvantageLanguage::class, 'website_advantage_id', 'id')->where('lang', app()->getLocale());
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
