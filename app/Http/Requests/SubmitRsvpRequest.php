<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\AttendanceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form request for submitting an RSVP response.
 */
class SubmitRsvpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Public RSVP submission - no authentication required
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $invitation = $this->route('invitation');
        $maxAttendees = $invitation?->max_attendance_per_guest ?? 5;

        return [
            'attendance_status' => [
                'required',
                'string',
                Rule::enum(AttendanceStatus::class),
                Rule::notIn([AttendanceStatus::Pending->value]),
            ],
            'attendance_count' => [
                'required_if:attendance_status,' . AttendanceStatus::Attending->value,
                'nullable',
                'integer',
                'min:1',
                'max:' . $maxAttendees,
            ],
            'message' => ['nullable', 'string', 'max:1000'],
            'dietary_requirements' => ['nullable', 'string', 'max:500'],
            'special_requests' => ['nullable', 'string', 'max:500'],
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
            'attendance_status.required' => 'Please select your attendance status.',
            'attendance_status.not_in' => 'Please select a valid attendance response.',
            'attendance_count.required_if' => 'Please specify how many people will attend.',
            'attendance_count.min' => 'At least 1 person must attend.',
            'attendance_count.max' => 'The maximum number of attendees allowed has been exceeded.',
            'message.max' => 'Your message cannot exceed 1000 characters.',
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
            'attendance_status' => 'attendance response',
            'attendance_count' => 'number of attendees',
            'dietary_requirements' => 'dietary requirements',
            'special_requests' => 'special requests',
        ];
    }
}
