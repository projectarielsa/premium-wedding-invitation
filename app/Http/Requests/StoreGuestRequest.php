<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\GuestCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form request for creating a new guest.
 */
class StoreGuestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $invitation = $this->route('invitation');

        return $invitation && $this->user()->can('create', [\App\Models\Guest::class, $invitation]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'whatsapp' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'category' => ['required', 'string', Rule::enum(GuestCategory::class)],
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

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default category if not provided
        if (!$this->has('category') || empty($this->category)) {
            $this->merge(['category' => GuestCategory::Friend->value]);
        }

        // Set default max_attendees if not provided
        if (!$this->has('max_attendees') || empty($this->max_attendees)) {
            $this->merge(['max_attendees' => 2]);
        }
    }
}
