<?php

namespace App\Http\Controllers;

use App\Models\Commentaires;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{
    
    public function index($id){
        $post = Post::find($id);

        if(!$post){
            return response()->json([
                'message' =>  'Post not found'
            ], 403);
        }

        return response()->json([
            'commentaires' => $post->commentaires()->with('user:id,name,image')->get()
        ], 200);
    }
    
    // Ajouter un commentaire
    public function store(Request $request, $id){
        $post = Post::find($id);

        if(!$post){
            return response()->json([
                'message' =>  'Post not found'
            ], 403);
        }

        // Validation
        $attrs = $request->validate([
            'commentaires' => 'required|string'
        ]);

        Commentaires::create([
            'commentaires' => $attrs['commentaires'],
            'post_id' => $id,
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
            'message' => 'Commentaire a été bien ajoutée!'
        ], 200);
    }

    // Update commentaires
    public function update(Request $request, $id){

        $commentaires = Commentaires::find($id);

        if(!$commentaires){
            return response()->json([
                'message' => 'Commentaires non trouvé!'
            ]);
        }

        if($commentaires->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Permission denied.'
            ], 403);
        }

        // validate fields
        $attrs = $request->validate([
            'commentaires' => 'required|string'
        ]);

        $commentaires->update([
            'commentaires' => $attrs['commentaires']
        ]);

        return response()->json([
            'message' => 'Modification a été bien effectué.',
            'commentaires' => $commentaires
        ], 200);

    }

    public function destroy($id){
        $commentaires = Commentaires::find($id);

        if(!$commentaires){
            return response()->json([
                'message' => 'Commentaires not found.'
            ], 403);
        }

        if($commentaires->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Permission denied.'
            ], 403);
        }

        $commentaires->delete();
        
        return response()->json([
            'message' => 'La suppresion a été bien effectuée.'
        ], 200);
    }
}
