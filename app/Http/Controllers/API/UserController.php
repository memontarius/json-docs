<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\NewAccessToken;

class UserController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $nickname = $request->input('login');
        $user = User::query()->firstOrCreate([
            'name' => $nickname
        ]);

        $user->tokens()->where('expires_at', '<', Carbon::now())->delete();
        $tokenExpiresAt = Carbon::now()->addHour();

        /** @var NewAccessToken $token */
        $token = $user->createToken('token', ['*'], $tokenExpiresAt);
        $plainTextToken = explode('|', $token->plainTextToken)[1];

        return response()->json([
            "user" => $nickname,
            "token" => $plainTextToken,
            "until" => $tokenExpiresAt->getTimestamp()
        ]);
    }
}
