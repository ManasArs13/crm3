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
            'login'   => 'required|string|min:3|max:55',
            'role'   => 'string|required',
            'password'   => 'string|required|min:5|max:55',
        ];
    }
}
