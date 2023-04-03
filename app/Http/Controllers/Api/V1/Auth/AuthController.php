<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginUserRequest;
use App\Http\Requests\Api\V1\Auth\RegisterUserRequest;
use App\Http\Services\Api\V1\Auth\AuthService;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    public function register(RegisterUserRequest $request, AuthService $authService): object
    {
        return $authService->register($request);
    }

    public function login(LoginUserRequest $request, AuthService $authService): object
    {
        // Validate user credentials
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->failedRequest('', 'Invalid email address or password', 404);
        }

        return $authService->login($request);
    }

    public function profile(AuthService $authService): object
    {
        return $authService->profile(Auth::user());
    }
}
