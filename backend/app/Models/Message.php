<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\MessageReaction;

class Message extends Model
{
    protected $guarded = [];

    public function fromContact()
    {
        return $this->hasOne(User::class, 'id', 'from')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
    }

    public function toContact()
    {
        return $this->hasOne(User::class, 'id', 'to')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
    }

    public function messagereactionmodel()
    {
        return $this->hasMany(MessageReaction::class);
    }

    public function replyMessage()
    {
        return $this->hasOne(Message::class, 'id', 'reply_id');
    }
}
