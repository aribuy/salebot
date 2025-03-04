<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'name', 'data', 'status','contact_list_id','segment_id','flow_for','flow_type','exclude_list_ids','exclude_segment_ids'];

    protected $casts = [
        'data' => 'array',
        // 'contact_list_ids' => 'array',
        // 'segment_ids' => 'array',
        'exclude_list_ids' => 'array',
        'exclude_segment_ids' => 'array',
    ];

    public function nodes()
    {
        return $this->hasMany(FlowNode::class);
    }
    public function edges()
    {
        return $this->hasMany(FlowEdge::class);
    }

    public function scopeWithPermission($query)
    {
        if (auth()->user()->user_type != 'admin') {
            $client = auth()->user()->client;
            $query->where('client_id', $client->id);
        }
    }


    public function contactList()
    {
        return $this->belongsTo(ContactsList::class, 'contact_list_id');
    }

    /**
     * Get the segment associated with the flow.
     */
    public function segment()
    {
        return $this->belongsTo(Segment::class, 'segment_id');
    }



}
