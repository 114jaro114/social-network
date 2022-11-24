<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\CommentReaction;

class AnswerOnComment extends Model
{
  public $table = 'answers_on_comments';
  protected $fillable = ['comment_id', 'user_id', 'text', 'image_url', 'video_url', 'chip'];

  public function useransweroncommentmodel()
  {
      return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
  }

  public function useransweroncommentmodel2()
  {
      return $this->belongsTo(User::class, 'chip', 'name')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
  }

  public function commentreactionmodel()
  {
      return $this->hasMany(CommentReaction::class, 'answer_on_comment_id');
  }
}
