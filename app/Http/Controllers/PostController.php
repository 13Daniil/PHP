<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function create(Request $request)
    {
        // // Проверяем, аутентифицирован ли пользователь
        // if (!Auth::check()) {
        //     return response()->json("Пользователь не аутентифицирован.", 401);
        // }

        // $request->validate([
        //     'content' => 'required|string',
        // ]);

        $post = new Post();
        $post->user_id = 1;  //TODO
        $post->content = $request->content;
        $post->save();

        return response()->json("Пост успешно создан.");
    }

    public function delete($id)
    {
        // Находим пост
        $post = Post::find($id);

        if (!$post) {
            return response()->json("Пост не найден.", 404);
        }

        // Проверяем, является ли текущий пользователь владельцем поста
        if ($post->user_id !== Auth::id()) {
            return response()->json("У вас нет прав для удаления этого поста.", 403);
        }

        $post->delete();

        return response()->json("Пост успешно удален.");
    }

    public function show()
    {
        $posts = Post::all();
        return response()->json($posts);
    }
}