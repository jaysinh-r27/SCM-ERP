<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(ApiLoginRequest $request)
    {
        $credentials = [];

        if (filter_var($request->login_input, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $request->login_input;
        } elseif (is_numeric($request->login_input)) {
            $credentials['phone'] = $request->login_input;
        } else {
            $credentials['name'] = $request->login_input;
        }

        $credentials['password'] = $request->password;

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('ERP_APP')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successfully',
                'access_token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ]
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid email or password'
        ], 401);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
