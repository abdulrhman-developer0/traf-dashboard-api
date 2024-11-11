<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
    ];

    /**
     * Get the chat that this member belongs to.
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    /**
     * Get the user that is a member of this chat.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
