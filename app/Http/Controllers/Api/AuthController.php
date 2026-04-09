<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|unique:users,email',
            'phone'      => 'required|string|max:20',
            'password'   => 'required|string|min:8|confirmed',
            'role'       => 'sometimes|in:customer,provider',
        ]);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'password'   => Hash::make($data['password']),
            'role'       => $data['role'] ?? 'customer',
        ]);

        Wallet::create(['user_id' => $user->user_id, 'balance' => 0.00]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'token'   => $token,
            'user'    => [
                'id'    => $user->user_id,
                'name'  => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);
    
        $user = User::where('email', $data['email'])->first();
    
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }
    
        if (!$user->is_active) {
            return response()->json(['message' => 'Account deactivated.'], 403);
        }
    
        $user->tokens()->delete();
        $token = $user->createToken('api-token')->plainTextToken;
    
        return response()->json([
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => [
                'id'    => $user->user_id,
                'name'  => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id'        => $user->user_id,
            'name'      => $user->first_name . ' ' . $user->last_name,
            'email'     => $user->email,
            'phone'     => $user->phone,
            'role'      => $user->role,
            'is_active' => $user->is_active,
        ]);
    }
}