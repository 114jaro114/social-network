<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PostReaction extends Model
{
  public $table = 'posts_reactions';
  protected $fillable = ['post_id', 'user_id', 'reaction_type'];

  public function userpostreactionmodel()
  {
      return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
  }
}
