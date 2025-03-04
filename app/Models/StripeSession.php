<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'stripe_session_id',
        'client_id',
        'plan_id',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
