<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\EventType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form request for creating a new event.
 */
class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $invitation = $this->route('invitation');

        return $invitation && $this->user()->can('manageEvents', $invitation);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::enum(EventType::class)],
            'name' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'timezone' => ['nullable', 'string', 'timezone'],
            'venue_name' => ['nullable', 'string', 'max:255'],
            'venue_address' => ['nullable', 'string', 'max:500'],
            'google_maps_url' => ['nullable', 'url', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'dress_code' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
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
            'type.required' => 'Please select an event type.',
            'name.required' => 'Please provide a name for the event.',
            'event_date.required' => 'Please select the event date.',
            'end_time.after' => 'End time must be after the start time.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
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
            'event_date' => 'event date',
            'start_time' => 'start time',
            'end_time' => 'end time',
            'venue_name' => 'venue name',
            'venue_address' => 'venue address',
            'google_maps_url' => 'Google Maps URL',
            'sort_order' => 'display order',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default timezone if not provided
        if (!$this->has('timezone') || empty($this->timezone)) {
            $this->merge(['timezone' => 'Asia/Jakarta']);
        }
    }
}
