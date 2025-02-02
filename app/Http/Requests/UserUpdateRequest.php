<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string', 'min:3', "max:100"],
            'last_name' => ['nullable', 'string', "max:100"],
            'password' => ['nullable', 'string', Password::min(8)->numbers()->mixedCase()],
            'photo' => ['nullable', File::image()->min('25kb')->max('2mb')]
        ];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if (!$this->first_name && !$this->last_name && !$this->password && !$this->hasFile("photo")) {
                $validator->errors()->add('message', 'At least one field must be present.');
            }
        });
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
