<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Checkout - {{ $package->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Informasi Pemesanan</h3>
                        
                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">

                            <div class="space-y-6">
                                <!-- Customer Name -->
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Lengkap *
                                    </label>
                                    <input type="text" id="customer_name" name="customer_name" 
                                           value="{{ old('customer_name', $user->name) }}" required
                                           class="w-full rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500">
                                    @error('customer_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Customer Email -->
                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">
                                        Email *
                                    </label>
                                    <input type="email" id="customer_email" name="customer_email" 
                                           value="{{ old('customer_email', $user->email) }}" required
                                           class="w-full rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500">
                                    @error('customer_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Customer WhatsApp -->
                                <div>
                                    <label for="customer_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nomor WhatsApp
                                    </label>
                                    <input type="text" id="customer_whatsapp" name="customer_whatsapp" 
                                           value="{{ old('customer_whatsapp', $user->whatsapp ?? $user->phone_number) }}"
                                           placeholder="08xxxxxxxxxx"
                                           class="w-full rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500">
                                    <p class="mt-1 text-xs text-gray-500">Kami akan menghubungi Anda via WhatsApp untuk konfirmasi pesanan.</p>
                                    @error('customer_whatsapp')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Wedding Date -->
                                <div>
                                    <label for="wedding_date" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tanggal Pernikahan (Perkiraan)
                                    </label>
                                    <input type="date" id="wedding_date" name="wedding_date" 
                                           value="{{ old('wedding_date') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500">
                                    @error('wedding_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Notes -->
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                        Catatan (Opsional)
                                    </label>
                                    <textarea id="notes" name="notes" rows="3"
                                              placeholder="Tulis catatan atau permintaan khusus..."
                                              class="w-full rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-8">
                                <button type="submit" 
                                        class="w-full py-4 px-6 bg-gradient-to-r from-gold-400 to-gold-600 text-white font-semibold text-lg rounded-xl hover:from-gold-500 hover:to-gold-700 transition shadow-lg">
                                    Lanjutkan ke Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h3>

                        <!-- Package Info -->
                        <div class="flex items-start mb-6">
                            <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center text-white font-bold text-xl">
                                {{ strtoupper(substr($package->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">{{ $package->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $package->duration_label }}</p>
                                @if($package->badge)
                                    <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium bg-gold-100 text-gold-800 rounded-full">
                                        {{ $package->badge }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="border-t border-b py-4 mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-3">Fitur yang didapat:</p>
                            <ul class="space-y-2">
                                @foreach(array_slice($package->features_list ?? [], 0, 5) as $feature)
                                    <li class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                                @if(count($package->features_list ?? []) > 5)
                                    <li class="text-sm text-gray-400">
                                        +{{ count($package->features_list) - 5 }} fitur lainnya
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <!-- Pricing -->
                        <div class="space-y-2">
                            @if($package->has_discount)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Harga Normal</span>
                                    <span class="line-through text-gray-400">{{ $package->formatted_original_price }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Diskon</span>
                                    <span class="text-green-600">-{{ $package->discount_percentage }}%</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center pt-2 border-t">
                                <span class="font-semibold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-gray-900">{{ $package->formatted_price }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gold-100 { background-color: #fef9e7; }
        .text-gold-800 { color: #7a5f1a; }
        .from-gold-400 { --tw-gradient-from: #d4af37; }
        .to-gold-600 { --tw-gradient-to: #b8972e; }
        .hover\:from-gold-500:hover { --tw-gradient-from: #c9a227; }
        .hover\:to-gold-700:hover { --tw-gradient-to: #9a7d26; }
        .focus\:border-gold-500:focus { border-color: #c9a227; }
        .focus\:ring-gold-500:focus { --tw-ring-color: #c9a227; }
    </style>
</x-app-layout>
