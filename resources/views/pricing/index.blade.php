<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-b from-white to-gray-50">
        <!-- Header -->
        <div class="py-16 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Pilih Paket Anda
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto px-4">
                Buat undangan pernikahan digital yang indah dan berkesan dengan harga terjangkau.
            </p>
        </div>

        <!-- Pricing Cards -->
        <div class="max-w-7xl mx-auto px-4 pb-20">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($packages as $package)
                    <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden transition-transform hover:scale-105 {{ $package->is_featured ? 'ring-4 ring-gold-400 ring-opacity-50' : 'border border-gray-200' }}">
                        <!-- Featured Badge -->
                        @if($package->is_featured)
                            <div class="absolute top-0 right-0 -mt-2 -mr-2">
                                <div class="bg-gradient-to-r from-gold-400 to-gold-600 text-white px-4 py-1 rounded-bl-lg text-sm font-semibold shadow-lg">
                                    {{ $package->badge ?? 'Terpopuler' }}
                                </div>
                            </div>
                        @elseif($package->badge)
                            <div class="absolute top-4 right-4">
                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $package->badge }}
                                </span>
                            </div>
                        @endif

                        <!-- Header -->
                        <div class="p-8 {{ $package->is_featured ? 'bg-gradient-to-br from-gold-400 via-gold-500 to-gold-600 text-white' : '' }}">
                            <h3 class="text-2xl font-bold {{ $package->is_featured ? 'text-white' : 'text-gray-900' }}">
                                {{ $package->name }}
                            </h3>
                            <p class="mt-2 text-sm {{ $package->is_featured ? 'text-gold-100' : 'text-gray-500' }}">
                                {{ $package->description }}
                            </p>
                            
                            <div class="mt-6">
                                @if($package->has_discount)
                                    <span class="text-lg line-through {{ $package->is_featured ? 'text-gold-200' : 'text-gray-400' }}">
                                        {{ $package->formatted_original_price }}
                                    </span>
                                    <span class="ml-2 text-xs px-2 py-1 rounded-full {{ $package->is_featured ? 'bg-white/20 text-white' : 'bg-green-100 text-green-800' }}">
                                        Hemat {{ $package->discount_percentage }}%
                                    </span>
                                @endif
                                <div class="mt-1">
                                    <span class="text-4xl font-extrabold {{ $package->is_featured ? 'text-white' : 'text-gray-900' }}">
                                        {{ $package->formatted_price }}
                                    </span>
                                    <span class="text-sm {{ $package->is_featured ? 'text-gold-100' : 'text-gray-500' }}">
                                        / {{ $package->duration_label }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="p-8">
                            <ul class="space-y-4">
                                @foreach($package->features_list ?? [] as $feature)
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- CTA -->
                        <div class="px-8 pb-8">
                            @auth
                                <a href="{{ route('orders.checkout', $package) }}" 
                                   class="block w-full text-center px-6 py-4 rounded-xl font-semibold text-lg transition
                                          {{ $package->is_featured 
                                              ? 'bg-gradient-to-r from-gold-400 to-gold-600 text-white hover:from-gold-500 hover:to-gold-700 shadow-lg' 
                                              : 'bg-gray-900 text-white hover:bg-gray-800' }}">
                                    Pilih {{ $package->name }}
                                </a>
                            @else
                                <a href="{{ route('register') }}?package={{ $package->slug }}" 
                                   class="block w-full text-center px-6 py-4 rounded-xl font-semibold text-lg transition
                                          {{ $package->is_featured 
                                              ? 'bg-gradient-to-r from-gold-400 to-gold-600 text-white hover:from-gold-500 hover:to-gold-700 shadow-lg' 
                                              : 'bg-gray-900 text-white hover:bg-gray-800' }}">
                                    Mulai Sekarang
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Features Comparison -->
        <div class="bg-gray-50 py-20">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900">Bandingkan Fitur</h2>
                    <p class="mt-4 text-gray-600">Lihat perbandingan lengkap semua paket kami.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full bg-white rounded-xl shadow-lg overflow-hidden">
                        <thead>
                            <tr class="bg-gray-900 text-white">
                                <th class="px-6 py-4 text-left">Fitur</th>
                                @foreach($packages as $package)
                                    <th class="px-6 py-4 text-center {{ $package->is_featured ? 'bg-gold-600' : '' }}">
                                        {{ $package->name }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">Jumlah Undangan</td>
                                @foreach($packages as $package)
                                    <td class="px-6 py-4 text-center">{{ $package->max_invitations_display }}</td>
                                @endforeach
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">Tamu per Undangan</td>
                                @foreach($packages as $package)
                                    <td class="px-6 py-4 text-center">{{ $package->max_guests_display }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">Amplop Digital</td>
                                @foreach($packages as $package)
                                    <td class="px-6 py-4 text-center">
                                        @if($package->gift_enabled)
                                            <svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">QR Code Check-in</td>
                                @foreach($packages as $package)
                                    <td class="px-6 py-4 text-center">
                                        @if($package->qr_checkin_enabled)
                                            <svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">Statistik & Analitik</td>
                                @foreach($packages as $package)
                                    <td class="px-6 py-4 text-center">
                                        @if($package->analytics_enabled)
                                            <svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">Export Data</td>
                                @foreach($packages as $package)
                                    <td class="px-6 py-4 text-center">
                                        @if($package->export_enabled)
                                            <svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">Support</td>
                                @foreach($packages as $package)
                                    <td class="px-6 py-4 text-center text-sm">{{ $package->support_level->shortLabel() }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- FAQ or CTA Section -->
        <div class="py-20 bg-white">
            <div class="max-w-4xl mx-auto text-center px-4">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Masih punya pertanyaan?
                </h2>
                <p class="text-xl text-gray-600 mb-8">
                    Tim kami siap membantu Anda memilih paket yang tepat untuk hari spesial Anda.
                </p>
                <a href="https://wa.me/6281234567890?text=Halo, saya ingin bertanya tentang paket undangan digital" 
                   target="_blank"
                   class="inline-flex items-center px-8 py-4 bg-green-500 text-white rounded-xl font-semibold text-lg hover:bg-green-600 transition">
                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>

    <style>
        .bg-gold-100 { background-color: #fef9e7; }
        .bg-gold-400 { background-color: #d4af37; }
        .bg-gold-500 { background-color: #c9a227; }
        .bg-gold-600 { background-color: #b8972e; }
        .from-gold-400 { --tw-gradient-from: #d4af37; }
        .via-gold-500 { --tw-gradient-stops: var(--tw-gradient-from), #c9a227, var(--tw-gradient-to); }
        .to-gold-600 { --tw-gradient-to: #b8972e; }
        .text-gold-100 { color: #fef9e7; }
        .text-gold-200 { color: #fdf3ce; }
        .text-gold-800 { color: #7a5f1a; }
        .ring-gold-400 { --tw-ring-color: #d4af37; }
        .hover\:from-gold-500:hover { --tw-gradient-from: #c9a227; }
        .hover\:to-gold-700:hover { --tw-gradient-to: #9a7d26; }
    </style>
</x-guest-layout>
