<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MessageReaction extends Model
{
    public $table = 'messages_reactions';
    protected $fillable = ['message_id', 'user_id', 'reaction_type'];

    public function usermessagereactionmodel()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
    }
}
