<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactRelationSegments extends Model
{
    use HasFactory;

    protected $fillable = ['contact_id', 'segment_id'];

    public function segment()
    {
        return $this->belongsTo(Segment::class, 'segment_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // $model->created_by = auth()->user() ? auth()->user()->id : null;
            $model->created_at = date('Y-m-d H:i:s');
        });

        static::updating(function ($model) {
            // $model->created_by = auth()->user() ? auth()->user()->id : null;
            $model->updated_at = date('Y-m-d H:i:s');
        });
    }

}
