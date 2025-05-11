<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        return $this->handleException(function () use ($request) {
            if (!Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ])) {
                return ResponseHelper::error(
                    message: 'Email or password is wrong',
                );
            }

            $token = Auth::user()->createToken('token')->plainTextToken;

            return ResponseHelper::success(
                message: 'Login successfully',
                data: [
                    'token' => $token,
                ]
            );
        }, $request);
    }

    public function logout()
    {
        return $this->handleException(function () {
            Auth::user()->tokens()->delete();

            return ResponseHelper::success(
                message: 'Logout successful',
                status_code: 200,
                data: []
            );
        });
    }
}
