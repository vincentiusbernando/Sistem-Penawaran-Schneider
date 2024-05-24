<?php

namespace App\Http\Controllers;

use DateTime;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class CustomerLoginController extends Controller
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
        if (!$user = auth()->guard('customer')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Anda salah'
            ]);
        }

        // Retrieve the authenticated user
        $user = auth()->guard('customer')->user();

        // Check if the retrieved user is a customer
        if ($user instanceof \App\Models\Customer) {

            // Set expiration date to tomorrow
            $currentDateTime = new DateTime();
            $expirationDateTime = clone $currentDateTime;
            $expirationDateTime->modify('+1 day');
            $expirationTimestamp = $expirationDateTime->getTimestamp();

            $payload = [
                'id' => $user->id,
                'guard' => 'customer',
                'exp' => $expirationTimestamp,
            ];
            // If so, generate JWT token
            $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token,
                'redirectUrl' => '/customer',
            ], 200);
        } else {
            // If the retrieved user is not a customer, return unauthorized response
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        auth()->guard('customer')->logout();
        $request->session()->invalidate();
        return redirect('/login');
    }
}
