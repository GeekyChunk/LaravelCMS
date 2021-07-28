<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fs = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fs['name'],
            'email' => $fs['email'],
            'password' => bcrypt($fs['password'])
        ]);

        $token = $user->createToken('testtoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fs['email'])->first();

        if (!$user || !Hash::check($fs['password'], $user->password)) {
            return response([
                'message' => 'Bad Creds'
            ], 401);
        }

        $token = $user->createToken($user->tokens)->plainTextToken;

        return ['token' => $token];
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return [
            'message' => 'Logged Out!'
        ];
    }
}
