<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'string', 'exists:customers,id'],
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:10000'],
            'contract_type' => ['required', 'in:temporary,indefinite,fixed_term,renewable,project'],
            'billing_model' => ['required', 'in:hourly,fixed,hybrid,milestone,recurring'],
            'starts_on' => ['required', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'fixed_amount' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'payment_terms_days' => ['nullable', 'integer', 'between:0,365'],
            'service_conditions' => ['nullable', 'array'],
            'renewal_rules' => ['nullable', 'array'],
            'termination_rules' => ['nullable', 'array'],
        ];
    }
}
