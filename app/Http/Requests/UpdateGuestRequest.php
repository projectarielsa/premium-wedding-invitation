<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\GuestCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form request for updating an existing guest.
 */
class UpdateGuestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $guest = $this->route('guest');

        return $guest && $this->user()->can('update', $guest);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'whatsapp' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'category' => ['sometimes', 'required', 'string', Rule::enum(GuestCategory::class)],
            'max_attendees' => ['nullable', 'integer', 'min:1', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
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
            'name.required' => 'Please enter the guest\'s name.',
            'category.required' => 'Please select a guest category.',
            'phone_number.regex' => 'Please enter a valid phone number.',
            'whatsapp.regex' => 'Please enter a valid WhatsApp number.',
            'max_attendees.min' => 'Maximum attendees must be at least 1.',
            'max_attendees.max' => 'Maximum attendees cannot exceed 20.',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'phone_number' => 'phone number',
            'max_attendees' => 'maximum attendees',
        ];
    }
}
