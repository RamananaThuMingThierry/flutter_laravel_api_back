<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
    // Get all posts
    public function index(){
        $posts = Post::orderBy('created_at', 'desc')
        ->with('user:id,name,image')
        ->withCount('commentaires','likes')
        ->with('likes', function($like){
            return $like->where('user_id', auth()->user()->id)
            ->select('id', 'user_id', 'post_id')->get();
        })
        ->get();
        return response()->json([
            'posts' => $posts,
            'status' => 200
        ]);
    }

    // Get single post
    public function show($id){
        $post = Post::where('id', $id)->withCount('commentaires','likes')->get();
        return response()->json([
            'posts' => $post
        ], 200);
    }

    // Create a post
    public function store(Request $request){
      
        // validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $image = $this->saveImage($request->image, 'posts');

        $post = Post::create([
            'body' => $attrs['body'],
            'user_id' => auth()->user()->id,
            'image' => $image
        ]);

        // For now skip for post image

        return response()->json([
            'message' => 'Post Created.',
            'post' => $post,
            'status' => 200
        ]);
    }

     // Update a post
     public function update(Request $request, $id){
            
        $post = Post::find($id);

        if(!$post){
            return response()->json([
                'message' => 'Post not found.'
            ], 403);
        }

        if($post->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Permission denied.'
            ], 403);
        }

        // validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $image = $this->saveImage($request->image, 'posts');

        $post->update([
            'body' => $attrs['body'],
            '$image' => $image == null ? $post->image : $image
        ]);

        return response()->json([
            'message' => 'Post Updated.',
            'post' => $post
        ], 200);
    }

    public function destroy($id){
        $post = Post::find($id);

        if(!$post){
            return response()->json([
                'message' => 'Post not found.'
            ], 403);
        }

        if($post->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Permission denied.'
            ], 403);
        }

        $post->commentaires()->delete();
        $post->likes()->delete();

        $post->delete();

        return response()->json([
            'message' => 'Suppresion a été bien effectuée!.'
        ], 200);
    }
}
