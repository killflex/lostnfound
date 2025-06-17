<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class Item extends Model
{
    use HasTimestamps;    
    
    const TYPE_LOST = 'lost';
    const TYPE_FOUND = 'found';
    const STATUS_PENDING = 'pending';
    const STATUS_RESOLVED = 'resolved';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $appends = ['image_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image
            ? url('storage/images/' . $this->image)
            : null;
    }
}