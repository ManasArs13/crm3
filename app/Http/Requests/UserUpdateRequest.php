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
        $userId = $this->route('managment');

        return [
            'name'    => 'string|required',
            'email'   => 'required|email|unique:users,email,' . $userId,
            'role'   => 'string|required',
        ];
    }
}
