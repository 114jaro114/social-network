<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PostShare extends Model
{
  public $table = 'posts_shares';
  protected $fillable = ['post_id', 'user_id'];

  public function userpostsharemodel()
  {
      return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');
  }
}
