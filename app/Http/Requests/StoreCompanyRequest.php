<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\ValidBrazilianDocument;
use Illuminate\Foundation\Http\FormRequest;

final class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'legal_name' => ['required', 'string', 'max:180'],
            'trade_name' => ['nullable', 'string', 'max:180'],
            'document' => ['required', 'string', new ValidBrazilianDocument()],
            'municipal_registration' => ['nullable', 'string', 'max:40'],
            'cnae' => ['nullable', 'string', 'max:12'],
            'tax_regime' => ['nullable', 'in:mei,simei,simples_nacional,lucro_presumido,lucro_real'],
            'ibge_municipality_code' => ['nullable', 'digits:7'],
            'address' => ['nullable', 'array'],
            'contact' => ['nullable', 'array'],
        ];
    }
}
