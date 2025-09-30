<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Get all users (protected)
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Register new user
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'phonenumber' => $request->phonenumber,
            'address' => $request->address,
            'profile_picture' => $request->profile_picture,
            'status' => $request->status ?? 'active',
        ]);

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    // Login user
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    //get profile
    public function profile(Request $request)
    {
        return response()->json([
            'message' => 'User profile fetched successfully',
            'token' => $request->user()->currentAccessToken()->plainTextToken,
            'user' => $request->user()
        ], 201);
    }
    // Logout user (revoke tokens)
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
