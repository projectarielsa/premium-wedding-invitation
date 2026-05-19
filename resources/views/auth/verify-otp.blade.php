<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        A verification code has been sent to <strong>{{ $email }}</strong>.
        Please enter the 6-digit code below.
    </div>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">{{ session('status') }}</div>
    @endif

    @if ($errors->has('otp'))
        <div class="mb-4 font-medium text-sm text-red-600">{{ $errors->first('otp') }}</div>
    @endif

    @if ($errors->has('resend'))
        <div class="mb-4 font-medium text-sm text-amber-600">{{ $errors->first('resend') }}</div>
    @endif

    <form method="POST" action="{{ route('verification.otp.verify') }}">
        @csrf
        <div>
            <x-input-label for="otp" value="Verification Code" />
            <x-text-input id="otp" class="block mt-1 w-full text-center text-2xl tracking-widest" type="text" name="otp" maxlength="6" required autofocus placeholder="000000" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <x-primary-button>Verify</x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center">
        @if ($canResend)
            <form method="POST" action="{{ route('verification.otp.resend') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">Resend Code</button>
            </form>
        @else
            <p class="text-sm text-gray-500">Resend available in {{ $resendCooldown }} seconds</p>
        @endif
    </div>

    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 underline">Sign out</button>
        </form>
    </div>
</x-guest-layout>
