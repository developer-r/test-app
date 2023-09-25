<?php

namespace App\Http\Requests\Api;

use App\Services\DateService;
use Illuminate\Foundation\Http\FormRequest;

class GetCurrencyDifferenceRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if(!$this->input('code'))
        {
            $this->merge([
                'code' => config('currency.default_value')
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'before_or_equal:' . DateService::getDateFormat()],
            'code' => ['nullable', 'string'],
        ];
    }
}
