<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array { return ['otp' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/']]; }

    public function messages(): array { return ['otp.required' => 'Please enter the verification code.', 'otp.size' => 'The verification code must be 6 digits.', 'otp.regex' => 'The verification code must contain only numbers.']; }

    public function getOtp(): string { $otp = $this->input('otp'); return is_array($otp) ? implode('', $otp) : (string) $otp; }

    protected function prepareForValidation(): void { $otp = $this->input('otp'); if (is_array($otp)) $this->merge(['otp' => implode('', $otp)]); }
}
