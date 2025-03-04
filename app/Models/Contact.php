<?php

namespace App\Models;

use App\Services\WhatsAppService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name','username','avatar','phone','country_id','images','client_id','group_chat_id','group_id','type','status','is_blacklist','is_verified','last_conversation_at','has_conversation','has_unread_conversation','contact_id'];
    protected $casts    = [
        'contact_list_id' => 'array',
        'segment_id'      => 'array',
        'images'          => 'array',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $whatsappService           = new WhatsAppService();
            $model->created_by         = auth()->user() ? auth()->user()->id : null;
            $model->created_at         = date('Y-m-d H:i:s');
            if(empty($model->country_id) && !empty($model->phone) ){
                $model->country_id = $whatsappService->extractCountryCode($model->phone);
            }
        
        });
        static::updating(function ($model) {
            $whatsappService           = new WhatsAppService();
            $model->created_by         = auth()->user() ? auth()->user()->id : null;
            $model->updated_at         = date('Y-m-d H:i:s');
            if(empty($model->country_id) && !empty($model->phone) ){
                $model->country_id = $whatsappService->extractCountryCode($model->phone);
            }
        });
    }

    public function segmentList()
    {
        return $this->hasMany(ContactRelationSegments::class, 'contact_id', 'id');
    }


    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeBlock($query)
    {
        return $query->where('status', 0);
    }

    public function conversation(): HasMany
    {
        return $this->hasMany(Message::class, 'contact_id')->orderBy('created_at', 'DESC');
    }

    public function lastConversation(): HasOne
    {
        return $this->hasOne(Conversation::class, 'contact_id')->latest();
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(Message::class, 'contact_id')->latest();
    }


    public function group()
    {
        return $this->belongsTo(BotGroup::class, 'group_id');
    }
    

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function list()
    {
        return $this->belongsTo(ContactsList::class, 'contact_list_id', 'id');
    }

    public function contactList()
    {
        return $this->hasMany(ContactRelationList::class, 'contact_id', 'id');
    }
    
    public function tags(): HasMany
    {
        return $this->hasMany(ContactTag::class);
    }

    public function note()
    {
        return $this->hasOne(ContactNote::class, 'contact_id');
    }


    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function contact_flow()
    {
        return $this->hasOne(ContactFlow::class)->latest();
    }

    public function getProfilePicAttribute(): string
    {
        return arrayCheck('image_40x40', $this->images) && is_file_exists($this->images['image_40x40'], $this->images['storage']) ?
            get_media($this->images['image_40x40'], $this->images['storage']) : static_asset('images/default/user.jpg');
    }

    public function scopeWithPermission($query)
    {
        if (auth()->user()->user_type != 'admin') {
            $client = auth()->user()->client;
            $query->where('client_id', $client->id);
        }
    }
}
