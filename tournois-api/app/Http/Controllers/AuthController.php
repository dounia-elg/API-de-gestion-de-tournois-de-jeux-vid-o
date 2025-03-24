<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = auth()->login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!$token = Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged in successfully',
            'data' => [
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer'
                ]
            ]
        ]);
    }



    public function logout(): JsonResponse
    {
        try {
            auth()->logout();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error during logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function user(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => auth()->user()
            ]
        ]);
    }
}