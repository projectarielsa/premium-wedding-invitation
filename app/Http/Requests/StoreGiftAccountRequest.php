<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\GiftAccountType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form request for creating a new gift account.
 */
class StoreGiftAccountRequest extends FormRequest
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
        $rules = [
            'type' => ['required', 'string', Rule::enum(GiftAccountType::class)],
            'provider' => ['required', 'string', 'max:100'],
            'provider_logo' => ['nullable', 'image', 'max:1024'], // 1MB max
            'account_holder' => ['required', 'string', 'max:255'],
            'instructions' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];

        // Conditionally require account_number based on type
        $type = $this->input('type');
        if ($type && in_array($type, [GiftAccountType::BankTransfer->value, GiftAccountType::EWallet->value])) {
            $rules['account_number'] = ['required', 'string', 'max:50'];
            $rules['qr_image'] = ['nullable', 'image', 'max:2048'];
        } else {
            $rules['account_number'] = ['nullable', 'string', 'max:50'];
            $rules['qr_image'] = ['required', 'image', 'max:2048'];
        }

        return $rules;
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
            'account_number.required' => 'Account number is required for this account type.',
            'qr_image.required' => 'QR code image is required for QRIS account type.',
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

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default type if not provided
        if (!$this->has('type') || empty($this->type)) {
            $this->merge(['type' => GiftAccountType::BankTransfer->value]);
        }
    }
}
