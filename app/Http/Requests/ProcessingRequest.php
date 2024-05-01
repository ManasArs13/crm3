<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessingRequest extends FormRequest
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
            // 'filter' => ['string']
        ];
    }
}
