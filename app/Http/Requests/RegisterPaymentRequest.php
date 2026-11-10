<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class RegisterPaymentRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array
    {
        return ['amount_cents' => ['required', 'integer', 'min:1'], 'paid_on' => ['required', 'date'], 'method' => ['required', 'in:pix,bank_transfer,credit_card,cash,other'], 'external_reference' => ['nullable', 'string', 'max:180'], 'notes' => ['nullable', 'string', 'max:2000']];
    }
}
