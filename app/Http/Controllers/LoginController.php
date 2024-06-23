<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('')->plainTextToken;    
            
 
            return new JsonResponse([
                'message' => 'Logged in!',
                'token' => $token,
            ]);
        }

        return new JsonResponse(
            [
                'message' => 'Invalid credentials!',
            ],
            401
        );
    }
}
