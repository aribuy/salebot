<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BotGroup extends Model
{
    use HasFactory;

    protected $table    = 'bot_groups';
    protected $fillable = [
        'name',
        'group_id',
        'super',
        'client_setting_id',
        'client_id',
        'is_admin',
        'type',
        'supergroup_subscriber_id',
        'status',
        'created_by',
        'updated_by',
    ];
    public function scopeWithPermission($query)
    {
        if (auth()->user()->user_type != 'admin') {
            $client = auth()->user()->client;
            $query->where('client_id', $client->id);
        }
    }
 
    public function subscriber(): HasMany
    {
        return $this->hasMany(GroupSubscriber::class, 'group_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function contact()
    {
        return $this->hasOne(Contact::class, 'group_id');
    }


}
