<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pembayaran - {{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Order Info Banner -->
            <div class="bg-gradient-to-r from-gold-400 to-gold-600 text-white rounded-xl p-6 mb-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <p class="text-gold-100 text-sm">Order Number</p>
                        <p class="text-xl font-bold">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-gold-100 text-sm">Total Pembayaran</p>
                        <p class="text-3xl font-bold">{{ $order->formatted_total_price }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Payment Methods -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900">Metode Pembayaran</h3>

                    @foreach($paymentMethods as $type => $methods)
                        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b">
                                <h4 class="font-medium text-gray-900">
                                    @if($type === 'bank_transfer')
                                        Transfer Bank
                                    @elseif($type === 'e_wallet')
                                        E-Wallet
                                    @elseif($type === 'qris')
                                        QRIS
                                    @endif
                                </h4>
                            </div>
                            <div class="divide-y">
                                @foreach($methods as $method)
                                    <div class="p-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                @if($method['logo_url'])
                                                    <img src="{{ $method['logo_url'] }}" alt="{{ $method['name'] }}" class="h-8 w-auto">
                                                @else
                                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <span class="ml-3 font-medium text-gray-900">{{ $method['name'] }}</span>
                                            </div>
                                        </div>
                                        
                                        @if($method['account_number'])
                                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                                <p class="text-sm text-gray-500">{{ $type === 'e_wallet' ? 'Nomor' : 'No. Rekening' }}</p>
                                                <div class="flex items-center justify-between mt-1">
                                                    <p class="text-lg font-mono font-bold text-gray-900">{{ $method['account_number'] }}</p>
                                                    <button type="button" 
                                                            onclick="navigator.clipboard.writeText('{{ $method['account_number'] }}')"
                                                            class="text-sm text-blue-600 hover:text-blue-800">
                                                        Salin
                                                    </button>
                                                </div>
                                                @if($method['account_name'])
                                                    <p class="text-sm text-gray-600 mt-1">a.n. {{ $method['account_name'] }}</p>
                                                @endif
                                            </div>
                                        @endif

                                        @if($method['qr_code_url'])
                                            <div class="mt-4 flex justify-center">
                                                <img src="{{ $method['qr_code_url'] }}" alt="QR Code" class="w-48 h-48">
                                            </div>
                                        @endif

                                        @if($method['instructions'])
                                            <p class="mt-3 text-sm text-gray-500">{{ $method['instructions'] }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Upload Form -->
                <div>
                    <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Upload Bukti Pembayaran</h3>

                        @if($order->payment_proof)
                            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-blue-800 font-medium mb-2">Bukti pembayaran sudah diupload</p>
                                <img src="{{ $order->payment_proof_url }}" alt="Payment Proof" class="rounded-lg max-h-48 w-full object-contain">
                                <p class="text-xs text-blue-600 mt-2">
                                    Diupload: {{ $order->payment_uploaded_at?->format('d M Y H:i') }}
                                </p>
                            </div>
                        @endif

                        <form action="{{ route('orders.upload-payment', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="space-y-4">
                                <!-- Payment Proof -->
                                <div>
                                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $order->payment_proof ? 'Ganti Bukti Pembayaran' : 'Bukti Pembayaran' }} *
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gold-400 transition">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="payment_proof" class="relative cursor-pointer bg-white rounded-md font-medium text-gold-600 hover:text-gold-500">
                                                    <span>Upload file</span>
                                                    <input id="payment_proof" name="payment_proof" type="file" class="sr-only" accept="image/*" required>
                                                </label>
                                                <p class="pl-1">atau drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG hingga 5MB</p>
                                        </div>
                                    </div>
                                    @error('payment_proof')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Payment Method -->
                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">
                                        Metode Pembayaran yang Digunakan
                                    </label>
                                    <select id="payment_method" name="payment_method" class="w-full rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500">
                                        <option value="">-- Pilih metode --</option>
                                        @foreach($paymentMethods as $type => $methods)
                                            @foreach($methods as $method)
                                                <option value="{{ $method['name'] }}">{{ $method['name'] }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Notes -->
                                <div>
                                    <label for="payment_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                        Catatan Pembayaran (Opsional)
                                    </label>
                                    <textarea id="payment_notes" name="payment_notes" rows="2"
                                              placeholder="Contoh: Transfer dari rekening a.n. ..."
                                              class="w-full rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500"></textarea>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                <button type="submit" class="w-full py-3 px-6 bg-gradient-to-r from-gold-400 to-gold-600 text-white font-semibold rounded-lg hover:from-gold-500 hover:to-gold-700 transition shadow">
                                    {{ $order->payment_proof ? 'Update Bukti Pembayaran' : 'Kirim Bukti Pembayaran' }}
                                </button>

                                <a href="{{ route('orders.show', $order) }}" class="block w-full text-center py-3 px-6 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                                    Lihat Detail Order
                                </a>
                            </div>
                        </form>

                        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-yellow-800">Penting!</h4>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        Pastikan jumlah transfer sesuai dengan total pembayaran. Tim kami akan memverifikasi pembayaran Anda dalam waktu 1x24 jam.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gold-100 { background-color: #fef9e7; }
        .text-gold-100 { color: #fef9e7; }
        .text-gold-600 { color: #b8972e; }
        .hover\:text-gold-500:hover { color: #c9a227; }
        .from-gold-400 { --tw-gradient-from: #d4af37; }
        .to-gold-600 { --tw-gradient-to: #b8972e; }
        .hover\:from-gold-500:hover { --tw-gradient-from: #c9a227; }
        .hover\:to-gold-700:hover { --tw-gradient-to: #9a7d26; }
        .hover\:border-gold-400:hover { border-color: #d4af37; }
        .focus\:border-gold-500:focus { border-color: #c9a227; }
        .focus\:ring-gold-500:focus { --tw-ring-color: #c9a227; }
    </style>
</x-app-layout>
