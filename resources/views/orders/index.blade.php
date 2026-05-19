<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pesanan Saya
            </h2>
            <a href="{{ route('pricing') }}" class="text-sm text-gold-600 hover:text-gold-700 font-medium">
                Lihat Paket &rarr;
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($orders->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div class="divide-y">
                        @foreach($orders as $order)
                            <div class="p-6 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between flex-wrap gap-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($order->package->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                                            <p class="text-sm text-gray-500">{{ $order->package->name }} - {{ $order->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900">{{ $order->formatted_total_price }}</p>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status->badgeClasses() }}">
                                                {{ $order->status->label() }}
                                            </span>
                                        </div>

                                        <div class="flex items-center space-x-2">
                                            @if($order->canUploadPaymentProof())
                                                <a href="{{ route('orders.payment', $order) }}" 
                                                   class="px-4 py-2 bg-gold-600 text-white text-sm font-medium rounded-lg hover:bg-gold-700 transition">
                                                    Bayar
                                                </a>
                                            @endif
                                            <a href="{{ route('orders.show', $order) }}" 
                                               class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                @if($order->status === \App\Enums\OrderStatus::Paid)
                                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-sm text-blue-800">
                                            <strong>Pembayaran sedang diverifikasi.</strong> 
                                            Tim kami akan menghubungi Anda dalam 1x24 jam kerja.
                                        </p>
                                    </div>
                                @elseif($order->status === \App\Enums\OrderStatus::Rejected)
                                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-sm text-red-800">
                                            <strong>Pesanan ditolak:</strong> {{ $order->rejection_reason }}
                                        </p>
                                    </div>
                                @elseif($order->status === \App\Enums\OrderStatus::Approved || $order->status === \App\Enums\OrderStatus::Completed)
                                    <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                        <p class="text-sm text-green-800">
                                            <strong>Paket sudah aktif!</strong> 
                                            Anda dapat mulai membuat undangan digital.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-500 mb-6">Pilih paket undangan digital yang sesuai dengan kebutuhan Anda.</p>
                    <a href="{{ route('pricing') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gold-400 to-gold-600 text-white font-semibold rounded-lg hover:from-gold-500 hover:to-gold-700 transition shadow">
                        Lihat Paket
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        .text-gold-600 { color: #b8972e; }
        .hover\:text-gold-700:hover { color: #9a7d26; }
        .bg-gold-600 { background-color: #b8972e; }
        .hover\:bg-gold-700:hover { background-color: #9a7d26; }
        .from-gold-400 { --tw-gradient-from: #d4af37; }
        .to-gold-600 { --tw-gradient-to: #b8972e; }
        .hover\:from-gold-500:hover { --tw-gradient-from: #c9a227; }
        .hover\:to-gold-700:hover { --tw-gradient-to: #9a7d26; }
    </style>
</x-app-layout>
