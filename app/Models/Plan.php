<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'billing_period',
        'is_free', 
        'contact_limit', 
        'color', 
        'campaigns_limit', 
        'featured', 
        'conversation_limit', 
        'team_limit', 
        'telegram_access', 
        'status',
        'max_chatwidget',
        'max_flow_builder',
        'max_bot_reply',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            // $model->created_by         = auth()->user() ? auth()->user()->id : null;
            $model->created_at         = date('Y-m-d H:i:s');
        });
        static::updating(function ($model) {
            // $model->created_by         = auth()->user() ? auth()->user()->id : null;
            $model->updated_at         = date('Y-m-d H:i:s');
        });
    }

}
