<?php

namespace App\Repositories\Auth;

use App\Models\User;

interface AuthRepositoryInterface
{
  public function register(array $data): User;
  public function login(array $data): ?string;
}
