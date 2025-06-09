<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'gender' => 'required|in:male,female',
        ], [
            'email.unique' => 'Пользователь с таким email уже зарегистрирован.',
        ]);

        Log::info('Валидация пользователя успешно пройдена.', [
            'email' => $validated['email'],
            'gender' => $validated['gender']
        ]);

        $user = User::create([
            'name' => $validated['email'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'gender' => $validated['gender'],
        ]);

        Log::info('Пользователь успешно создан.', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('Токен авторизации успешно сгенерирован.', [
            'user_id' => $user->id
        ]);


        return response()->json([
            'message' => 'Пользователь успешно зарегистрирован',
            'token' => $token,
        ], 201);
    }

    public function profile(Request $request)
    {
        Log::info('Запрос данных пользователя.', [
            'user_id' => $request->user()->id,
            'email' => $request->user()->email,
            'ip' => $request->ip()
        ]);

        return response()->json([
            'email' => $request->user()->email,
            'gender' => $request->user()->gender,
            'created_at' => $request->user()->created_at,
        ]);
    }
}
