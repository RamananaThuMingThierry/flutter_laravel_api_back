<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Commentaires extends Model
{
    use HasFactory;

    protected $fillable = ["post_id", "user_id", "commentaires"];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
