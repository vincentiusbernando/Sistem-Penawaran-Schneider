<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use DateTime;
class InternalLoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ];

        // Attempt authentication
        if (!$user = auth()->guard('internal')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Anda salah'
            ]);
        }

        // Retrieve the authenticated user
        $user = auth()->guard('internal')->user();

        // Check if the retrieved user is a internal
        if ($user instanceof \App\Models\Internal) {

            // Set expiration date to tomorrow
            $currentDateTime = new DateTime();
            $expirationDateTime = clone $currentDateTime;
            $expirationDateTime->modify('+1 day');
            $expirationTimestamp = $expirationDateTime->getTimestamp();

            $payload = [
                'user' => $user,
                'guard' => 'internal',
                'exp' => $expirationTimestamp,
            ];
            // If so, generate JWT token
            $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token,
                'redirectUrl' => '/internal',
            ], 200);
        } else {
            // If the retrieved user is not a internal, return unauthorized response
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
    }
}
