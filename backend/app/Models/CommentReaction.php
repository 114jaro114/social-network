<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class CommentReaction extends Model
{
  public $table = 'comments_reactions';
  protected $fillable = ['comment_id', 'answer_on_comment_id', 'user_id', 'reaction_type'];

    public function usercommentreactionmodel()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
    }
}
