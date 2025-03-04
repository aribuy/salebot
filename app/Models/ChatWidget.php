<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatWidget extends Model
{
    use HasFactory,SoftDeletes;
    protected $table    = 'chat_widgets';

    protected $fillable = [
        'name',
        'unique_id',
        'enable_box',
        'box_position',
        'layout',
        'schedule_from',
        'schedule_to',
        'timezone',
        'available_days',
        'button_text',
        'visibility',
        'type',
        'devices',
        'welcome_message',
        'offline_message',
        'header_title',
        'header_subtitle',
        'header_media',
        'footer_text',
        'font_family',
        'animation',
        'auto_open',
        'auto_open_delay',
        'animation_delay',
        'font_size',
        'rounded_border',
        'background_color',
        'header_background_color',
        'background_image',
        'text_color',
        'icon_size',
        'icon_font_size',
        'label_color',
        'name_color',
        'availability_color',
        'store_chat_history',
        'custom_style',
        'analytics_settings',
        'buttons',
        'client_id',
    ];

    protected $casts    = [
        'analytics_settings' => 'array',
        'buttons'      => 'array',
        'available_days'      => 'array',
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

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function contacts()
    {
        return $this->hasMany(ChatWidgetContact::class, 'widget_id')->orderBy('priority','asc');
    }


}
