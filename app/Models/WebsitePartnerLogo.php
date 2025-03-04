<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsitePartnerLogo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
    ];

    protected $casts    = [
        'image' => 'array',
    ];

    public function languages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WebsitePartnerLogoLanguage::class);
    }

    public function language(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(WebsitePartnerLogoLanguage::class, 'website_partner_logo_id', 'id')->where('lang', app()->getLocale());
    }

    public function getLangNameAttribute()
    {
        return $this->language ? $this->language->name : $this->name;
    }


}
