<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreTimeEntryRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() !== null; }

    public function rules(): array
    {
        return [
            'contract_id' => ['required', 'string', 'exists:contracts,id'],
            'worked_on' => ['required', 'date'],
            'duration_minutes' => ['required', 'integer', 'between:1,1440'],
            'billable' => ['sometimes', 'boolean'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
