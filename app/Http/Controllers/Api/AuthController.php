<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // No need for register method since I'll just use seeders to create admin accounts, since this is just a test
    // And there is no requirement for user registration in the project :3

    public function login(Request $request)
    {
        // Validate the request, and return json response if validation fails
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if user is already logged in as per requirement no 1
        if (Auth::guard('sanctum')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'User already logged in'
            ], 400);
        }

        if (!Auth::attempt($request->only('username', 'password'))) {
            return response([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $admin = Admin::where('username', $request['username'])->first();
        $token = $admin->createToken('token')->plainTextToken;

        // Output per requirement
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'username' => $admin->username,
                    'phone' => $admin->phone,
                    'email' => $admin->email,
                ],
            ],
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful',
        ]);
    }
}
