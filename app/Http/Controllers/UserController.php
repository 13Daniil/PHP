<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|name|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return "Успешная регистрация.";
    }

    public function login(Request $request)
    {
        $credentials = $request->only('name', 'email', 'password');

        if(Auth::attempt($credentials))
        {
            return "Успешная аутентификация.";
        }
        else
        {
            return "Неверные учетные данные.";
        }
    }
}
