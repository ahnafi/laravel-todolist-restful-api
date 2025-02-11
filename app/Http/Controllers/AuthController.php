<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create($data);
        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "data" => [
                "user" => UserResource::make($user),
                "token_type" => "Bearer",
                "token" => $token
            ]
        ], 201);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::query()->where("email", $data["email"])->first();
        if (!$user || !Hash::check($data["password"], $user->password)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "Email or Password is wrong"
                    ]
                ]
            ], 401));
        }

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "data" => [
                "user" => UserResource::make($user),
                "token_type" => "Bearer",
                "token" => $token
            ]
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->currentAccessToken()->delete;
        return response()->json([
            "data" => true
        ]);
    }
}
