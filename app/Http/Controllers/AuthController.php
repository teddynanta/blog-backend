<?php

namespace App\Http\Controllers;

use App\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use App\Repositories\Auth\AuthRepositoryInterface;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponseTrait;
    protected AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Register a newly created resource in storage.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authRepository->register(($request->validated()));
        return $this->successResponse(new UserResource(($user)), 'Account Created!', 201);
    }

    /**
     * Display the specified resource.
     */
    public function me(Request $request): JsonResponse
    {
        return $this->successResponse(new UserResource($request->user()), 'User Details');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authRepository->login(($request->validated()));

        if (!$user) {
            return $this->errorResponse([], 'Invalid Credentials', 401);
        } elseif ($user === 'already_logged_in') {
            return $this->errorResponse([], 'User already logged in', 403);
        }
        return $this->successResponse(['token' => $user], 'Login Success', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request)
    {
        $this->authRepository->logout($request);
        return $this->successResponse([], 'Successfully logged out!');
    }
}
