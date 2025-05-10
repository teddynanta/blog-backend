<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
  use ApiResponseTrait;
  public function register(array $data): User
  {
    $data['password'] = Hash::make(($data['password']));
    return User::create($data);
  }

  public function login(array $data): ?string
  {
    if (!Auth::attempt($data)) {
      return null;
    }

    $user = Auth::user();
    // dd($user);

    // Check if user already has a valid token
    if ($user->tokens()->count() > 0) {
      return 'already_logged_in';
    }

    // Create new token
    return $user->createToken('auth_token')->plainTextToken;
  }

  public function logout(Request $request): void
  {
    $request->user()->currentAccessToken()->delete();
  }
}
