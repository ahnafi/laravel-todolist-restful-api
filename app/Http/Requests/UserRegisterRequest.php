<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     *
     * unique:users,email -> unique table users field email
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:3', "max:100"],
            'last_name' => ['nullable', 'string', "max:100"],
            'email' => ['required', 'string', 'email', 'unique:users,email', "max:255"],
            'password' => ["required", Password::min(8)->numbers()->mixedCase()],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
