<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Enums\TypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['campaign_name', 'variables', 'variables_match', 'url_link', 'campaign_type', 'template_id', 'contact_list_id', 'segment_id', 'client_id'];

    protected $casts    = [
        'campaign_type' => TypeEnum::class,
        'status'        => StatusEnum::class,
        'media_url'     => 'array',
        'contact_list_id' => 'array',
        'segment_id' => 'array',
        'contact_list_ids'      => 'array',
        'segment_ids'      => 'array',
    ];

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

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function contact_list()
    {
        return $this->hasOne(ContactsList::class, 'contact_list_id');
    }

    // public function contactList()
    // {
    //     return $this->hasOne(ContactsList::class, 'contact_list_id');
    // }

    public function segment()
    {
        return $this->hasOne(Segment::class, 'segment_id');
    }

    public function conversation()
    {
        return $this->hasMany(Message::class, 'campaign_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'campaign_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'client_id');
    }

    public function scopeWithPermission($query)
    {
        if (auth()->user()->user_type != 'admin') {
            $client = auth()->user()->client;
            $query->where('client_id', $client->id);
        }
    }
}
