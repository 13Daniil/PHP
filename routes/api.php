<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PhotoController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/delete', [UserController::class, 'delete']);

Route::post('/post/create', [PostController::class, 'create']);
Route::get('/post/show', [PostController::class, 'show']);
Route::delete('/post/{id}', [PostController::class, 'delete']);

Route::post('/comment/create', [CommentController::class, 'create']);
Route::delete('/comment/{id}', [CommentController::class, 'delete']);

Route::get('/photo/{id}', [PhotoController::class, 'Show']);
Route::post('/photo/addphoto', [PhotoController::class, 'addPhoto']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/post/create', [PostController::class, 'create']);
//     Route::delete('/post/{id}', [PostController::class, 'delete']);
//     Route::post('/comment/create', [CommentController::class, 'create']);
//     Route::delete('/comment/{id}', [CommentController::class, 'delete']);
// });
