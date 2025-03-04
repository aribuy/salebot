<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactRelationList extends Model
{
    use HasFactory;

    protected $fillable = ['contact_id', 'contact_list_id'];

    public function list()
    {
        return $this->belongsTo(ContactsList::class, 'contact_list_id', 'id');
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
