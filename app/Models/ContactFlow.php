<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactFlow extends Model
{
    use HasFactory;
    protected $table    = 'contacts_flows';
    protected $fillable = ['contact_id','flow_id','campaign_id'];

    public function flow()
    {
        return $this->belongsTo(Flow::class);
    }
 
}
