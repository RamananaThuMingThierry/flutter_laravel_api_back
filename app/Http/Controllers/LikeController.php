<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    // lIKE Or unLike
    public function likeOrunlike($id){
        $post = Post::find($id);

        if(!$post){
            return response()->json([
                'message' => 'Post not found.',
                'status' => 403
            ]);
        }

        $like = $post->likes()->where('user_id', auth()->user()->id)->first();

        // if not liked then like
        if(!$like){
            Like::create([
                'post_id' => $id,
                'user_id' => auth()->user()->id
            ]);

            return response()->json([
                'message' => 'Liked',
                'status' => 200
            ]);
        }

        $like->delete();

        return response()->json([
            'message' => 'Disliked',
            'status' => 200
        ]);
    }
}
