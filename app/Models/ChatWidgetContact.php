<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatWidgetContact extends Model
{
    use HasFactory,SoftDeletes;
    protected $table    = 'chat_widget_contacts';
    protected $fillable = [
        'name',
        'username',
        'images',
        'phone',
        'label',
        'priority',
        'welcome_message',
        'available_from',
        'available_to',
        'timezone',
        'widget_id',
        'status',
    ];
    
    protected $casts    = [
        'images' => 'array',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by         = auth()->user() ? auth()->user()->id : null;
            $model->created_at         = date('Y-m-d H:i:s');
          });
        static::updating(function ($model) {
            $model->created_by         = auth()->user() ? auth()->user()->id : null;
            $model->updated_at         = date('Y-m-d H:i:s');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeWithPermission($query)
    {
        if (auth()->user()->user_type != 'admin') {
            $client = auth()->user()->client;
            $query->where('client_id', $client->id);
        }
    }
    
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    
    public function chatwidget()
    {
        return $this->belongsTo(ChatWidget::class, 'widget_id', 'id');
    }

    // public function getProfilePicAttribute(): string
    // {
    //     return arrayCheck('image_40x40', $this->images) && is_file_exists($this->images['image_40x40'], $this->images['storage']) ?
    //         get_media($this->images['image_40x40'], $this->images['storage']) : static_asset('images/default/user.jpg');
    // }


}
