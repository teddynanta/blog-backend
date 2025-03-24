<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
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
    return $user->createToken('auth_token')->plainTextToken;

    // return [
    //   'token' => $user->createToken('auth_token')->plainTextToken,
    //   'logged_in' => true,
    // ];
  }

  public function logout(Request $request): void
  {
    $request->user()->currentAccessToken()->delete();
  }
}
