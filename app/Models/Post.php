<?php

namespace App\Models;

use App\Models\Like;
use App\Models\User;
use App\Models\Commentaires;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ["body", "user_id", "image"];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function commentaires(){
        return $this->hasMany(Commentaires::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }
}
