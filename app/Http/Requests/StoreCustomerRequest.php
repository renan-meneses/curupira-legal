<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\ValidBrazilianDocument;
use Illuminate\Foundation\Http\FormRequest;

final class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:180'],
            'document' => ['required', 'string', new ValidBrazilianDocument()],
            'email' => ['nullable', 'email:rfc', 'max:180'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'array'],
            'billing_contact' => ['nullable', 'string', 'max:180'],
            'payment_terms_days' => ['nullable', 'integer', 'between:0,365'],
        ];
    }
}
