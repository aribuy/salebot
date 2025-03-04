<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlowBuilderFile extends Model
{
    use HasFactory;

    protected $fillable = ['file', 'flow_template_id', 'flow_template_type'];
}
