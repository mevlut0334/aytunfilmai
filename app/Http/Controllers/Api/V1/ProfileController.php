<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/v1/profile
     */
    public function show(): JsonResponse
    {
        $user = Auth::user();

        return $this->success([
            'id'            => $user->id,
            'name'          => $user->name,
            'email'         => $user->email,
            'phone'         => $user->phone,
            'token_balance' => $user->token_balance,
        ], 'Profil bilgileri getirildi.');
    }
}
