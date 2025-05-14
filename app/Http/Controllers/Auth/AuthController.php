<?php

namespace App\Http\Controllers\Auth;

use App\Attributes\HandleException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\AutoHandleException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AutoHandleException;

    #[HandleException]
    public function login(LoginRequest $request)
    {
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
    }

    #[HandleException]
    public function logout()
    {
        Auth::user()->tokens()->delete();

        return ResponseHelper::success(
            message: 'Logout successful',
            status_code: 200,
            data: []
        );
    }
}
