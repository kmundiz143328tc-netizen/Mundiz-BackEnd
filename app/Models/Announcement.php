<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'priority',
        'category',
        'is_pinned',
        'created_by',
        'expires_at',
    ];

    protected $casts = [
        'is_pinned'  => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}