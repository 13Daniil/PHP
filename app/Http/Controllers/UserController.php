<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use APP\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'login' => 'required|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = new User();
        $user->login = $data['login'];
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return "Успешная регистрация.";
    }

    public function login(Request $request)
    {
        $credentials = $request->only('login', 'password');

        if(Auth::attemt($credentials))
        {
            return "Успешная аутентификация.";
        }
        else
        {
            return "Неверные учетные данные.";
        }
    }
}
