<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
    {
   
        public function signup(Request $request)
        {
            try {
                $validator = $request->validate([
                    'name' => 'required|string',
                    'email' => 'required|email|unique:users',
                    'password' => [
                        'required',
                        'string',
                        'min:8',
                        'regex:/^(?=.*[a-z])(?=.*[A-Z])/',
                    ],
                ], [
                    'password.required' => 'The password field is required.',
                    'password.string' => 'The password must be a string.',
                    'password.min' => 'The password must be at least 8 characters.',
                    'password.regex' => 'The password must contain at least one uppercase and one lowercase letter.',
                ]);


                $user = new User([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                $user->save();

                return response()->json([
                    'message' => 'User registered successfully',
                    'user' => $user->only(['name', 'email']),
                ], 201);

            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
            } catch (\Exception $e) {
                // Handle any unexpected errors
                return response()->json(['message' => 'An error occurred during user registration'], 500);
            }
        }



        public function login(Request $request)
        {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('API Token')->plainTextToken;
                return response()->json(['message' => 'Login successful', 'token' => $token], 201);
            }

            return response()->json(['message' => 'Invalid login credentials'], 401);
        }



        public function logout(Request $request)
        {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'User logged out successfully'], 200);
        }
}

