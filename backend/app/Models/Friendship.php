<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    protected $guarded = [];
    // protected $fillable = ['requester', 'user_requested', 'friendship_status'];

    public function fromUser()
    {
        return $this->hasOne(User::class, 'id', 'requester')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
    }

    public function toUser()
    {
        return $this->hasOne(User::class, 'id', 'user_requested')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
    }
}
