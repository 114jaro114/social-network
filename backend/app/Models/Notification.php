<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];
    // protected $fillable = [
    //     'to',
    //     'test',
    //     'status',
    // ];

    public function fromUser()
    {
        return $this->hasOne(User::class, 'id', 'from')->select('id', 'name', 'email', 'status', 'avatar', 'created_at');;
    }
}
