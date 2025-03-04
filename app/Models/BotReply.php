<?php

namespace App\Models;

use App\Enums\BotReplyType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotReply extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'name', 'reply_text', 'reply_using_ai', 'keywords', 'reply_type', 'status'];

    protected $casts    = [
        'reply_type' => BotReplyType::class,
    ];

    public function scopeWithPermission($query)
    {
        if (auth()->user()->user_type != 'admin') {
            $client = auth()->user()->client;
            $query->where('client_id', $client->id);
        }
    }

    
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->user() ? auth()->user()->id : null;
            $model->created_at = date('Y-m-d H:i:s');
        });

        static::updating(function ($model) {
            $model->created_by = auth()->user() ? auth()->user()->id : null;
            $model->updated_at = date('Y-m-d H:i:s');
        });
    }
}
