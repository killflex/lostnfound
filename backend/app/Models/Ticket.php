<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Pest\ArchPresets\Relaxed;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'title',
        'description',
        'status',
        'priority',
        'completed_at',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ticketReplies() {
        return $this->hasMany(TicketReply::class);
    }
}
