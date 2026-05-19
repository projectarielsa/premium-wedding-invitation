<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\GiftAccountType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form request for updating an existing gift account.
 */
class UpdateGiftAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $invitation = $this->route('invitation');

        return $invitation && $this->user()->can('manageGiftAccounts', $invitation);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'required', 'string', Rule::enum(GiftAccountType::class)],
            'provider' => ['sometimes', 'required', 'string', 'max:100'],
            'provider_logo' => ['nullable', 'image', 'max:1024'],
            'remove_provider_logo' => ['nullable', 'boolean'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'account_holder' => ['sometimes', 'required', 'string', 'max:255'],
            'qr_image' => ['nullable', 'image', 'max:2048'],
            'remove_qr_image' => ['nullable', 'boolean'],
            'instructions' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
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
            'type.required' => 'Please select an account type.',
            'provider.required' => 'Please enter the provider/bank name.',
            'account_holder.required' => 'Please enter the account holder name.',
            'provider_logo.max' => 'Provider logo must not exceed 1MB.',
            'qr_image.max' => 'QR code image must not exceed 2MB.',
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
            'account_number' => 'account number',
            'account_holder' => 'account holder name',
            'provider_logo' => 'provider logo',
            'qr_image' => 'QR code image',
            'sort_order' => 'display order',
        ];
    }
}
