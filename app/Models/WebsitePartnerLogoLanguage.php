<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsitePartnerLogoLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_partner_logo_id',
        'lang',
        'name',
    ];
}
