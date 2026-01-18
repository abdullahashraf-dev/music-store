<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Result;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(private UserRepository $userRepository) {}

    public function register(array $data): Result
    {
        $data['password'] = Hash::make($data['password']);
        
        $user = $this->userRepository->create($data);
        
        return Result::success(
            data: $user,
            statusCode: 201
        );
    }

    public function login(string $email, string $password): Result
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return Result::error(
                message: 'Invalid credentials',
                statusCode: 400
            );
        }

        $token = $user->createToken('auth-token')->accessToken;

        return Result::success(
            data: [
                'user' => $user,
                'access_token' => $token
            ],
        );
    }
}
