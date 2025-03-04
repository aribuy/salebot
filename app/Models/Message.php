<?php

namespace App\Models;

use App\Enums\MessageStatusEnum;
use App\Enums\TypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    // protected $fillable = [
    //     'message',
    //     'is_seen',
    //     'chat_room_id',
    //     'type',
    //     'message',
    //     'file_type',
    //     'is_file',
    //     'file',
    // ];

    use HasFactory;

    public function chatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class);
    }

    protected $fillable = [
        'contact_id', 'client_id', 'header_text', 'footer_text', 'header_image', 'header_video', 'header_location', 'header_document', 'buttons', 'value', 'error', 'message_type', 'status',
        'schedule_at', 'components', 'campaign_id', 'header_audio', 'file_info',
    ];

    protected $casts    = [
        'status'    => MessageStatusEnum::class,
        'source'    => TypeEnum::class,
        'file_info' => 'array',
        'component_body' => 'array',
        'component_buttons' => 'array',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function group_subscriber()
    {
        return $this->belongsTo(GroupSubscriber::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
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

    public function scopeWithPermission($query)
    {
        if (auth()->user()->user_type != 'admin') {
            $client = auth()->user()->client;
            $query->where('client_id', $client->id);
        }
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
