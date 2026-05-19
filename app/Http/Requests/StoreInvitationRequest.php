<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form request for creating a new invitation.
 */
class StoreInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'template_id' => ['nullable', 'integer', 'exists:templates,id'],
            'bride_name' => ['required', 'string', 'max:100'],
            'groom_name' => ['required', 'string', 'max:100'],
            'bride_parent' => ['nullable', 'string', 'max:255'],
            'groom_parent' => ['nullable', 'string', 'max:255'],
            'opening_message' => ['nullable', 'string', 'max:1000'],
            'story_section' => ['nullable', 'array'],
            'story_section.*.title' => ['required_with:story_section', 'string', 'max:255'],
            'story_section.*.content' => ['required_with:story_section', 'string', 'max:2000'],
            'story_section.*.date' => ['nullable', 'date'],
            'cover_image' => ['nullable', 'image', 'max:5120'], // 5MB max
            'gallery' => ['nullable', 'array', 'max:20'],
            'gallery.*' => ['image', 'max:5120'],
            'music_url' => ['nullable', 'url', 'max:500'],
            'event_date' => ['nullable', 'date', 'after_or_equal:today'],
            'location' => ['nullable', 'string', 'max:500'],
            'google_maps_url' => ['nullable', 'url', 'max:500'],
            'dress_code' => ['nullable', 'string', 'max:255'],
            'theme_settings' => ['nullable', 'array'],
            'theme_settings.primary_color' => ['nullable', 'string', 'max:50'],
            'theme_settings.secondary_color' => ['nullable', 'string', 'max:50'],
            'theme_settings.font_family' => ['nullable', 'string', 'max:100'],
            'custom_css' => ['nullable', 'array'],
            'seo_title' => ['nullable', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:160'],
            'seo_image' => ['nullable', 'image', 'max:2048'],
            'settings' => ['nullable', 'array'],
            'settings.rsvp_enabled' => ['nullable', 'boolean'],
            'settings.gift_enabled' => ['nullable', 'boolean'],
            'settings.guest_book_enabled' => ['nullable', 'boolean'],
            'settings.countdown_enabled' => ['nullable', 'boolean'],
            'settings.music_autoplay' => ['nullable', 'boolean'],
            'settings.show_guest_count' => ['nullable', 'boolean'],
            'settings.require_attendance_count' => ['nullable', 'boolean'],
            'settings.max_attendance_per_guest' => ['nullable', 'integer', 'min:1', 'max:20'],
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
            'title.required' => 'Please provide a title for your invitation.',
            'bride_name.required' => 'Please enter the bride\'s name.',
            'groom_name.required' => 'Please enter the groom\'s name.',
            'cover_image.max' => 'Cover image must not exceed 5MB.',
            'gallery.max' => 'You can upload a maximum of 20 gallery images.',
            'event_date.after_or_equal' => 'Event date must be today or a future date.',
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
            'bride_name' => 'bride\'s name',
            'groom_name' => 'groom\'s name',
            'bride_parent' => 'bride\'s parents',
            'groom_parent' => 'groom\'s parents',
            'google_maps_url' => 'Google Maps URL',
            'seo_title' => 'SEO title',
            'seo_description' => 'SEO description',
        ];
    }
}
