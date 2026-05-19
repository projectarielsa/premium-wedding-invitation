<x-admin-layout>
    <x-slot name="header">Payment Settings</x-slot>
    <x-slot name="title">Payment Settings</x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600">Manage bank accounts and payment methods.</p>
        <a href="{{ route('admin.payment-settings.create') }}" class="px-4 py-2 bg-gold-600 text-white rounded-lg hover:bg-gold-700 transition">
            + Add Payment Method
        </a>
    </div>

    @foreach($paymentSettings as $type => $methods)
        <div class="bg-white rounded-xl shadow-sm border mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h3 class="font-semibold text-gray-900">
                    @if($type === 'bank_transfer') Transfer Bank
                    @elseif($type === 'e_wallet') E-Wallet
                    @elseif($type === 'qris') QRIS
                    @endif
                </h3>
            </div>
            <div class="divide-y">
                @foreach($methods as $method)
                    <div class="p-6 flex items-center justify-between">
                        <div class="flex items-center">
                            @if($method->logo_url)
                                <img src="{{ $method->logo_url }}" alt="{{ $method->name }}" class="h-10 w-auto">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">{{ $method->name }}</p>
                                @if($method->account_number)
                                    <p class="text-sm text-gray-500">{{ $method->account_number }} - {{ $method->account_name }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="w-2 h-2 rounded-full {{ $method->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                            <a href="{{ route('admin.payment-settings.edit', $method) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Edit
                            </a>
                            <form action="{{ route('admin.payment-settings.toggle-active', $method) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                    {{ $method->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.payment-settings.destroy', $method) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this payment method?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    @if($paymentSettings->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No payment methods</h3>
            <p class="text-gray-500">Add your first payment method to start receiving payments.</p>
        </div>
    @endif
</x-admin-layout>
