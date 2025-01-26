<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function get(Request $request): UserResource
    {
        return new UserResource(Auth::user());
    }

    public function update(UserUpdateRequest $request, UserService $service): UserResource
    {
        return new UserResource($service->UpdateUser($request, Auth::user()));
    }
}
