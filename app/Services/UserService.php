<?php

namespace App\Services;

use App\Http\Requests\UserUpdateRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService
{
    public function SavePhoto(Request $request): string
    {
        return $request->file("photo")->storePublicly("profiles", "public");
    }

    public function UpdateUser(UserUpdateRequest $request, Authenticatable $user): Authenticatable
    {
        $validated = $request->validated();

        if (isset($validated["photo"])) {
            $user->photo = $this->SavePhoto($request);
        }

        if (isset($validated["first_name"])) $user->first_name = $validated["first_name"];

        if (isset($validated["last_name"])) $user->last_name = $validated["last_name"];

        if (isset($validated["password"])) $user->password = Hash::make($validated["password"]);

        $user->save();

        return $user;
    }
}
