<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientStaff extends Model
{
    use HasFactory;

    protected $table    = 'client_staff';

    protected $casts    = [
        'social_links' => 'array',
    ];

    protected $fillable = ['user_id', 'client_id', 'slug'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Client::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    public function scopeWithPermission($query)
    {
        if (auth()->user()->user_type != 'admin') {
            $client = auth()->user()->client;
            $query->where('client_id', $client->id);
        }
    }
}
