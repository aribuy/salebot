<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Language extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'locale', 'flag', 'is_default', 'text_direction', 'status'];

    public function flag(): HasOne
    {
        return $this->hasOne(FlagIcon::class, 'title', 'locale');
    }

    public function getFlagIconAttribute()
    {
        return $this->flag ? static_asset($this->flag->image) : static_asset('images/flags/ad.png');
    }

       // Before saving, ensure only one row has is_default set to 1
    //    protected static function boot()
    //    {
    //        parent::boot();

    //        static::saving(function ($language) {
    //            if ($language->is_default) {
    //                static::query()->where('is_default', 1)->update(['is_default' => 0]); // Reset other defaults
    //            }
    //        });
    //    }
}
