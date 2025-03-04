<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactFlowState extends Model
{
    use HasFactory;
    protected $fillable = [
        'contact_id',
        'flow_id',
        'current_node_id',
    ];

     // Define relationships
     public function contact()
     {
         return $this->belongsTo(Contact::class, 'contact_id');
     }
 
     public function flow()
     {
         return $this->belongsTo(Flow::class, 'flow_id');
     }
}
