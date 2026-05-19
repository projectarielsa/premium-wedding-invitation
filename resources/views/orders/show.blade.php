<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pesanan
            </h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                &larr; Kembali ke Daftar Pesanan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Banner -->
            <div class="mb-8 rounded-xl overflow-hidden">
                @if($order->status === \App\Enums\OrderStatus::WaitingPayment)
                    <div class="bg-yellow-50 border border-yellow-200 p-6">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-yellow-800">Menunggu Pembayaran</h3>
                                <p class="text-yellow-700">Silakan lakukan pembayaran dan upload bukti transfer.</p>
                            </div>
                            <a href="{{ route('orders.payment', $order) }}" 
                               class="ml-auto px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition">
                                Bayar Sekarang
                            </a>
                        </div>
                    </div>
                @elseif($order->status === \App\Enums\OrderStatus::Paid)
                    <div class="bg-blue-50 border border-blue-200 p-6">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-blue-800">Pembayaran Sedang Diverifikasi</h3>
                                <p class="text-blue-700">Tim kami akan memverifikasi pembayaran Anda dalam 1x24 jam kerja.</p>
                            </div>
                        </div>
                    </div>
                @elseif($order->status === \App\Enums\OrderStatus::Approved || $order->status === \App\Enums\OrderStatus::Completed)
                    <div class="bg-green-50 border border-green-200 p-6">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-green-800">Pesanan Berhasil!</h3>
                                <p class="text-green-700">Paket {{ $order->package->name }} sudah aktif di akun Anda.</p>
                            </div>
                            <a href="{{ route('invitations.create') }}" 
                               class="ml-auto px-6 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition">
                                Buat Undangan
                            </a>
                        </div>
                    </div>
                @elseif($order->status === \App\Enums\OrderStatus::Rejected)
                    <div class="bg-red-50 border border-red-200 p-6">
                        <div class="flex items-start">
                            <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-red-800">Pesanan Ditolak</h3>
                                <p class="text-red-700">{{ $order->rejection_reason }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Details -->
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Informasi Pesanan</h3>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Order Number</p>
                                <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Order</p>
                                <p class="font-medium text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Nama</p>
                                <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium text-gray-900">{{ $order->customer_email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">WhatsApp</p>
                                <p class="font-medium text-gray-900">{{ $order->customer_whatsapp ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Pernikahan</p>
                                <p class="font-medium text-gray-900">{{ $order->wedding_date?->format('d M Y') ?? '-' }}</p>
                            </div>
                        </div>

                        @if($order->notes)
                            <div class="mt-6 pt-6 border-t">
                                <p class="text-sm text-gray-500 mb-2">Catatan</p>
                                <p class="text-gray-700">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Package Info -->
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Paket yang Dibeli</h3>
                        
                        <div class="flex items-start">
                            <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                                {{ strtoupper(substr($order->package->name, 0, 1)) }}
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="font-semibold text-gray-900 text-lg">{{ $order->package->name }}</h4>
                                <p class="text-gray-500">{{ $order->package->description }}</p>
                                <p class="text-sm text-gray-400 mt-1">Masa aktif: {{ $order->package->duration_label }}</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t">
                            <p class="text-sm font-medium text-gray-700 mb-3">Fitur yang didapat:</p>
                            <ul class="grid grid-cols-2 gap-2">
                                @foreach($order->package->features_list ?? [] as $feature)
                                    <li class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Payment Proof -->
                    @if($order->payment_proof)
                        <div class="bg-white rounded-xl shadow-sm border p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Bukti Pembayaran</h3>
                            
                            <div class="border rounded-lg overflow-hidden">
                                <img src="{{ $order->payment_proof_url }}" alt="Payment Proof" class="w-full max-h-96 object-contain">
                            </div>
                            
                            <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                                <span>Diupload: {{ $order->payment_uploaded_at?->format('d M Y H:i') }}</span>
                                <a href="{{ $order->payment_proof_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    Lihat Gambar Penuh
                                </a>
                            </div>

                            @if($order->payment_method)
                                <p class="mt-2 text-sm text-gray-600">
                                    <strong>Metode:</strong> {{ $order->payment_method }}
                                </p>
                            @endif

                            @if($order->payment_notes)
                                <p class="mt-2 text-sm text-gray-600">
                                    <strong>Catatan:</strong> {{ $order->payment_notes }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Payment Summary -->
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Harga Paket</span>
                                <span class="text-gray-900">{{ $order->formatted_package_price }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Diskon</span>
                                    <span class="text-green-600">-{{ $order->formatted_discount }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between pt-3 border-t">
                                <span class="font-semibold text-gray-900">Total</span>
                                <span class="text-xl font-bold text-gray-900">{{ $order->formatted_total_price }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Order</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status->badgeClasses() }}">
                                    {{ $order->status->label() }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Pembayaran</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->payment_status->badgeClasses() }}">
                                    {{ $order->payment_status->label() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if(!$order->isFinal())
                        <div class="bg-white rounded-xl shadow-sm border p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h3>
                            
                            <div class="space-y-3">
                                @if($order->canUploadPaymentProof())
                                    <a href="{{ route('orders.payment', $order) }}" 
                                       class="block w-full text-center py-3 px-4 bg-gradient-to-r from-gold-400 to-gold-600 text-white font-semibold rounded-lg hover:from-gold-500 hover:to-gold-700 transition">
                                        {{ $order->payment_proof ? 'Update Bukti Bayar' : 'Upload Bukti Bayar' }}
                                    </a>
                                @endif

                                @if($order->status->canBeCancelled())
                                    <form action="{{ route('orders.cancel', $order) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                        @csrf
                                        <button type="submit" 
                                                class="block w-full text-center py-3 px-4 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                                            Batalkan Pesanan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Activity Timeline -->
                    @if($order->activities->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm border p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat</h3>
                            
                            <div class="space-y-4">
                                @foreach($order->activities->take(5) as $activity)
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 rounded-full bg-gray-400 mt-2"></div>
                                        <div class="ml-3">
                                            <p class="text-sm text-gray-900">{{ $activity->action_label }}</p>
                                            <p class="text-xs text-gray-400">{{ $activity->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .from-gold-400 { --tw-gradient-from: #d4af37; }
        .to-gold-600 { --tw-gradient-to: #b8972e; }
        .hover\:from-gold-500:hover { --tw-gradient-from: #c9a227; }
        .hover\:to-gold-700:hover { --tw-gradient-to: #9a7d26; }
    </style>
</x-app-layout>
