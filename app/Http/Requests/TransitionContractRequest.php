<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class TransitionContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:draft,active,suspended,expired,terminated,renewed'],
            'reason' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
