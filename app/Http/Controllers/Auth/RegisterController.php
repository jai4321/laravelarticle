<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(Request $request) {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            return response()->json(["message"=> "User created Successfully", "Details" => array("id"=>$user->id,"name", $user->name, "email"=>$user->email)], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error during registration: ' . $e->getMessage(),
            ], 500);
        }
    }
}
