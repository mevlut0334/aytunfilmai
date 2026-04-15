<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Services\Interfaces\UserServiceInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->register([
                'name'                   => $request->name,
                'email'                  => $request->email,
                'phone'                  => $request->phone,
                'password'               => $request->password,
                'terms_accepted'         => $request->terms_accepted,
                'copyright_accepted'     => $request->copyright_accepted,
                'kvkk_accepted'          => $request->kvkk_accepted,
                'personal_data_accepted' => $request->personal_data_accepted,
                'ip_address'             => $request->ip(),
                'user_agent'             => $request->userAgent(),
            ]);

            $token = $user->createToken('mobile')->plainTextToken;

            return $this->success([
                'user' => [
                    'id'            => $user->id,
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'phone'         => $user->phone,
                    'token_balance' => $user->token_balance,
                ],
                'token' => $token,
            ], 'Kayıt işlemi başarılı.', 201);

        } catch (\Exception $e) {
            return $this->error('Kayıt sırasında bir hata oluştu. Lütfen tekrar deneyiniz.', 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->userService->login($request->email, $request->password);

        if (!$user) {
            return $this->error('E-posta veya şifre hatalı.', 401);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return $this->success([
            'user' => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone,
                'token_balance' => $user->token_balance,
            ],
            'token' => $token,
        ], 'Giriş başarılı.');
    }

    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();

        return $this->success(null, 'Çıkış başarılı.');
    }
}
