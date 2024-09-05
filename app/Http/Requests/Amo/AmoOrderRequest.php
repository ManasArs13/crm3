<?php

namespace App\Http\Requests\Amo;

use Illuminate\Foundation\Http\FormRequest;

class AmoOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'column'    => ['string'],
            'orderBy'   => ['string'],
            'columns'   => ['array'],
            'filters'   => ['array'],
        ];
    }
}
