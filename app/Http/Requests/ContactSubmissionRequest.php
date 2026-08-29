<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $fields = [
            'name',
            'phone',
            'email',
            'treatment',
            'preferred_date',
            'preferred_time',
            'message',
        ];

        $clean = [];

        foreach ($fields as $field) {
            $value = $this->input($field);
            $clean[$field] = is_string($value) ? trim($value) : $value;
        }

        foreach (['email', 'treatment', 'preferred_date', 'preferred_time', 'message'] as $optional) {
            if (($clean[$optional] ?? null) === '') {
                $clean[$optional] = null;
            }
        }

        $this->merge($clean);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:255'],
            'treatment' => ['nullable', 'string', 'max:140'],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
            'preferred_time' => ['nullable', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
