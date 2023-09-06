<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Proteted Routes
Route::group(['middleware' => ['auth:sanctum']], function(){

    // Utilisateurs
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Post
    Route::get('/posts', [PostController::class,'index']);
    Route::post('/posts', [PostController::class,'store']);
    Route::get('/posts/{id}', [PostController::class,'show']);
    Route::put('/posts/{id}', [PostController::class,'update']);
    Route::delete('/posts/{id}', [PostController::class,'destroy']);
    
    // Commentaires
    Route::get('/posts/{id}/commentaires', [CommentaireController::class,'index']);
    Route::post('/posts/{id}/commentaires', [CommentaireController::class,'store']);
    Route::put('/commentaires/{id}', [CommentaireController::class,'update']);
    Route::delete('/commentaires/{id}', [CommentaireController::class,'destroy']);

    // Like
    Route::post('/posts/{id}/likes', [LikeController::class, 'likeOrunlike']);
});
