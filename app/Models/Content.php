<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $guarded = [];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('position');
    }
}
