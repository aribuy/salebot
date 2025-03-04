<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class GroupSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'avatar',
        'phone',
        'client_id',
        'group_chat_id',
        'group_subscriber_id',
        'group_id',
        'unique_id',
        'is_left_group',
        'created_by',
        'updated_by',
        'type',
        'status',
        'is_blacklist',
        'is_bot',
        'is_admin',
        'scopes',
    ];
    protected $casts    = [
        'scopes'     => 'array',
    ];
    public function scopeWithPermission($query)
    {
        if (auth()->user()->user_type != 'admin') {
            $client = auth()->user()->client;
            $query->where('client_id', $client->id);
        }
    }

 
    public function group()
    {
        return $this->belongsTo(BotGroup::class, 'group_id');
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 1)->where('is_blacklist', 0);
    }

    public function scopeBlock($query)
    {
        return $query->where('status', 0)->where('is_blacklist', 1);
    }
}
