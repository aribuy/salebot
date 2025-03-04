<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlowEdge extends Model
{
    use HasFactory;

    protected $fillable = ['flow_id', 'edge_id', 'source', 'target', 'data', 'sourceHandle'];

    protected $casts    = [
        'data' => 'array',
    ];

    public function flow(): BelongsTo
    {
        return $this->belongsTo(Flow::class);
    }
}
