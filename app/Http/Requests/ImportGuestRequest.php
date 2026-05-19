<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for bulk importing guests.
 */
class ImportGuestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $invitation = $this->route('invitation');

        return $invitation && $this->user()->can('import', [\App\Models\Guest::class, $invitation]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:csv,txt,xlsx,xls',
                'max:5120', // 5MB max
            ],
            'skip_header' => ['nullable', 'boolean'],
            'column_mapping' => ['nullable', 'array'],
            'column_mapping.name' => ['nullable', 'integer', 'min:0'],
            'column_mapping.phone_number' => ['nullable', 'integer', 'min:0'],
            'column_mapping.whatsapp' => ['nullable', 'integer', 'min:0'],
            'column_mapping.email' => ['nullable', 'integer', 'min:0'],
            'column_mapping.category' => ['nullable', 'integer', 'min:0'],
            'column_mapping.max_attendees' => ['nullable', 'integer', 'min:0'],
            'column_mapping.notes' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Please upload a file.',
            'file.mimes' => 'File must be CSV, TXT, XLS, or XLSX format.',
            'file.max' => 'File size must not exceed 5MB.',
        ];
    }
}
