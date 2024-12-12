<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;

class TransportUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        if (!$this->has('main')) {
            $this->merge([
                'main' => 0,
            ]);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'ms_id' => 'nullable|max:36',
            'tonnage' => 'integer|nullable',
            'car_number' => 'string|nullable|max:255',
            'driver' => 'string|nullable|max:255',
            'phone' => 'string|nullable|max:255',
            'type_id' => 'exists:App\Models\TransportType,id|nullable',
            'contact_id' => 'exists:App\Models\Contact,id|nullable',
            'main' => 'boolean'
        ];
    }
}
