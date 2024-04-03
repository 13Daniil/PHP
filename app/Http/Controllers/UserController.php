<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
            ]);

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['message'=>"success"]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                return response()->json("Успешная аутентификация.");
            } else {
                return response()->json("Неверные учетные данные.", 401);
            }
        } catch (\Exception $e) {
            return response()->json("Ошибка при аутентификации: " . $e->getMessage(), 500);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json("Пользователь не найден.", 404);
            }

            // Проверяем, имеет ли текущий пользователь право на удаление этого пользователя
            if ($user->id !== Auth::id()) {
                return response()->json("У вас нет прав для удаления этого пользователя.", 403);
            }

            $user->delete();

            return response()->json("Пользователь успешно удален.");
        } catch (\Exception $e) {
            return response()->json("Ошибка при удалении пользователя: " . $e->getMessage(), 500);
        }
    }
}
