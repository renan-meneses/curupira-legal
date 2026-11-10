<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class PreviewTaxRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array
    {
        return [
            'service_code' => ['required', 'string', 'max:32'],
            'competency_date' => ['required', 'date'],
            'base_cents' => ['required', 'integer', 'min:0'],
            'issuer_municipality_code' => ['nullable', 'digits:7'],
            'customer_municipality_code' => ['nullable', 'digits:7'],
            'tax_regime' => ['nullable', 'string', 'max:32'],
            'source_type' => ['nullable', 'string', 'max:120'],
            'source_id' => ['nullable', 'string', 'max:26'],
        ];
    }
}
