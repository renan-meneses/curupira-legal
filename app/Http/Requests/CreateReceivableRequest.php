<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateReceivableRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array { return ['billing_record_id' => ['required', 'string', 'exists:billing_records,id'], 'due_on' => ['required', 'date']]; }
}
