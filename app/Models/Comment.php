<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function user()
    {   
        return $this->belongsTo(User::class);
    }

}
