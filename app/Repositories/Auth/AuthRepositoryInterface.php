<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
  public function register(array $data): User;
  public function login(array $data): ?string;
  public function logout(Request $request): void;
}
