<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreTaxConfigurationRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array
    {
        return [
            'service_code' => ['required', 'string', 'max:32'],
            'issuer_municipality_code' => ['nullable', 'digits:7'],
            'customer_municipality_code' => ['nullable', 'digits:7'],
            'tax_regime' => ['nullable', 'in:mei,simei,simples_nacional,lucro_presumido,lucro_real'],
            'effective_from' => ['required', 'date'],
            'effective_to' => ['nullable', 'date', 'after_or_equal:effective_from'],
            'rules' => ['required', 'array', 'min:1'],
            'rules.*.code' => ['required', 'string', 'max:16'],
            'rules.*.basis_points' => ['required', 'integer', 'between:0,10000'],
            'rules.*.treatment' => ['nullable', 'in:withheld,outside_invoice,estimated'],
        ];
    }
}
