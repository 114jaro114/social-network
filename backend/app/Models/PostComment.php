<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\AnswerOnComment;
use App\Models\CommentReaction;

class PostComment extends Model
{
  public $table = 'posts_comments';
  protected $fillable = ['post_id', 'user_id', 'text', 'image_url', 'video_url', 'chip'];


  public function userpostcommentmodel()
  {
      return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
  }

  public function userpostcommentmodel2()
  {
      return $this->belongsTo(User::class, 'chip', 'name')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
  }

  public function answeroncommentmodel()
  {
      return $this->hasMany(AnswerOnComment::class, 'comment_id')->where('status','!=', 'deleted');
  }

  public function commentreactionmodel2()
  {
      return $this->hasMany(CommentReaction::class, 'comment_id');
  }
}
