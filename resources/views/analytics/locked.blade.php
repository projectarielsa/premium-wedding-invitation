<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Analitik') }} - {{ $invitation->couple_name }}
            </h2>
            <a href="{{ route('invitations.show', $invitation) }}" class="text-sm text-gray-500 hover:text-gray-700">
                &larr; Kembali ke undangan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-center">
                    <!-- Lock Icon -->
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-purple-100 mb-6">
                        <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Fitur Analitik Terkunci
                    </h3>
                    
                    <p class="text-gray-600 mb-6">
                        {{ $message }}
                    </p>

                    @if($upgradeRequired && isset($suggestedPackage) && $suggestedPackage)
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-lg p-6 mb-6">
                            <h4 class="font-semibold text-gray-900 mb-2">
                                Upgrade ke {{ $suggestedPackage->name }}
                            </h4>
                            <p class="text-sm text-gray-600 mb-4">
                                Dapatkan akses ke fitur analitik lengkap untuk memantau performa undangan Anda.
                            </p>
                            <div class="text-2xl font-bold text-purple-600 mb-4">
                                {{ $suggestedPackage->formatted_price }}
                                <span class="text-sm font-normal text-gray-500">/ {{ $suggestedPackage->duration_label }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('pricing') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                            Upgrade Sekarang
                        </a>
                        <a href="{{ route('invitations.show', $invitation) }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Kembali ke Undangan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Preview of what analytics looks like -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h4 class="font-semibold text-gray-900 mb-4">Fitur Analitik Premium</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 opacity-60">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-purple-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span class="text-sm text-gray-600">Statistik pengunjung real-time</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-purple-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span class="text-sm text-gray-600">Grafik RSVP per hari</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-purple-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span class="text-sm text-gray-600">Breakdown perangkat pengunjung</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-purple-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span class="text-sm text-gray-600">Sumber referral trafik</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-purple-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span class="text-sm text-gray-600">Engagement metrics</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-purple-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span class="text-sm text-gray-600">Export laporan lengkap</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
