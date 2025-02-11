<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function login(Request $request) 
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        $timestamp = now()->timestamp;
        $token = hash('sha256', $user->email . $timestamp);

        return response()->json([
            'token' => $token,
            'timestamp' => $timestamp,
            'email' => $user->email
        ])->header('Content-Type', 'application/json');
    }
}
