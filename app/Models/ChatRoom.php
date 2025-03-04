<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_id',
        'is_accepted',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'receiver_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'chat_room_id');
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(Message::class, 'chat_room_id')->latest();
    }
}
