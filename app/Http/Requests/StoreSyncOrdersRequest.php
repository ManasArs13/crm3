<?php

namespace App\Http\Requests;

use App\Models\OrderAmo;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSyncOrdersRequest extends FormRequest
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
     * @return array< ValidationRule|string>
     */
    public function rules(): array
    {
        if (!$this->notSync) {
            return [
                'id'=>['required', Rule::exists(OrderAmo::class, 'id')]
            ];
        }

        return [];
    }
}
