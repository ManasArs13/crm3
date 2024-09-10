<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;

class TransportStoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:transports|max:255',
            'description' => 'nullable',
            'ms_id' => 'nullable|max:36',
            'tonnage' => 'integer|nullable',
            'contact_id' => 'exists:App\Models\Contact,id|nullable'
        ];
    }
}
