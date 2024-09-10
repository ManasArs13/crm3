<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'name'    => 'string|required',
            'email'   => 'required|email|unique:users,email',
            'role'   => 'string|required',
            'password'   => 'string|required|min:5|max:55',
        ];
    }
}
