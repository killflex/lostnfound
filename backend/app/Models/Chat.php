<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class Chat extends Model
{
    use HasTimestamps;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
