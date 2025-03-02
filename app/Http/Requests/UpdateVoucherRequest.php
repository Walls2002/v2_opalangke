<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVoucherRequest extends FormRequest
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
        $valueRules = ['required', 'decimal:0,2', 'min:1.00'];

        if ($this->boolean('is_percent')) {
            array_push($valueRules, 'max:100');
        } else {
            array_push($valueRules, 'max:100000');
        }

        return [
            'code' => ['required', 'string', 'min:3', 'max:50', Rule::unique('vouchers', 'code')->ignore($this->voucher)],
            'min_order_price' => ['required', 'decimal:0,2', 'min:0.00', 'max:10000'],
            'value' => $valueRules,
            'description' => ['required', 'string', 'min:3', 'max:100'],
            'is_percent' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'The voucher code is required.',
            'code.unique' => 'This voucher code is already in use.',
            'min_order_price.required' => 'The minimum order price is required.',
            'value.required' => 'The voucher value is required.',
            'value.max' => $this->boolean('is_percent')
                ? 'The voucher percentage must not exceed 100.'
                : 'The voucher value must not exceed 100000.',
            'description.required' => 'The voucher description is required.',
            'is_percent.required' => 'The is percent field is required.',
        ];
    }
}