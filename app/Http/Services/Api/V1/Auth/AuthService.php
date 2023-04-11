<?php

namespace App\Http\Services\Api\V1\Auth;

use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    use HttpResponses;

    public function register(object $request): object
    {
        // get user
        $user = User::where('email', $request->email)->first();

        // Request object should validate for unique users, but double check if user already exists
        if ($user) {
            return $this->failedRequest('', 'The email has already been taken', 400);
        }

        // create new user
        $user = User::create([
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password)
        ]);

        return $this->successfullRequest([
            'user' => $user,
            'token' => $user->createToken('Basic web token')->plainTextToken
        ], 'User successfully created', 201);
    }

    public function login(object $request): object
    {
        // get user
        $user = User::where('email', $request->email)->first();

        // return response
        return $this->successfullRequest([
            'user' => $user,
            'token' => $user->createToken('Basic web token')->plainTextToken
        ], 'User successfully logged in', 200);
    }

    public function profile(object $user): object
    {
        return User::where('id', $user->id)
            ->withCount([
                'todos as pending_todos_count' => function ($query) {
                    $query->where('status', 'pending');
                },
                'todos as in_progress_todos_count' => function ($query) {
                    $query->where('status', 'in_progress');
                },
                'todos as completed_todos_count' => function ($query) {
                    $query->where('status', 'completed');
                },
                'todos as cancelled_todos_count' => function ($query) {
                    $query->where('status', 'cancelled');
                },
                'todos'
            ])->first();
    }
}
