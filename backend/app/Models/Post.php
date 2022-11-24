<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostReaction;
use App\Models\PostShare;


class Post extends Model
{
  // protected $guarded = [];
  protected $fillable = ['user_id', 'text', 'image_url', 'video_url'];

  public function userpostmodel()
  {
      return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
  }

  public function postcommentmodel()
  {
      return $this->hasMany(PostComment::class)->where('status','!=', 'deleted');
  }

  public function postreactionmodel()
  {
      return $this->hasMany(PostReaction::class);
  }

  public function postsharemodel()
  {
      return $this->hasMany(PostShare::class);
  }
}
