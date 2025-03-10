<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestimonialLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'testimonial_id',
        'lang',
        'name',
        'title',
        'designation',
        'description',
    ];
}
