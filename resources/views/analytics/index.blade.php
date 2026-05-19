<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Analytics') }} - {{ $invitation->couple_name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('invitations.export.summary', $invitation) }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                    Export Summary
                </a>
                <a href="{{ route('invitations.show', $invitation) }}" class="text-indigo-600 hover:text-indigo-800">
                    &larr; Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Period Filter -->
            <div class="mb-6">
                <form method="GET" class="flex gap-2">
                    <select name="period" onchange="this.form.submit()" 
                            class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="7days" {{ $period === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30days" {{ $period === '30days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="90days" {{ $period === '90days' ? 'selected' : '' }}>Last 90 Days</option>
                        <option value="all" {{ $period === 'all' ? 'selected' : '' }}>All Time</option>
                    </select>
                </form>
            </div>

            <!-- Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($overview['total_views']) }}</div>
                    <div class="text-sm text-gray-500">Total Views</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-indigo-600">{{ number_format($overview['unique_visitors']) }}</div>
                    <div class="text-sm text-gray-500">Unique Visitors</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-green-600">{{ $overview['total_guests'] }}</div>
                    <div class="text-sm text-gray-500">Total Guests</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-purple-600">{{ $overview['total_rsvps'] }}</div>
                    <div class="text-sm text-gray-500">RSVPs Received</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-yellow-600">{{ $overview['total_events'] }}</div>
                    <div class="text-sm text-gray-500">Events</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold {{ ($overview['days_until_event'] ?? 0) > 0 ? 'text-blue-600' : 'text-gray-400' }}">
                        {{ $overview['days_until_event'] ?? 'N/A' }}
                    </div>
                    <div class="text-sm text-gray-500">Days Until Event</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- RSVP Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">RSVP Statistics</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Invited</span>
                            <span class="font-semibold">{{ $rsvpStats['total_invited'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Responded</span>
                            <span class="font-semibold">{{ $rsvpStats['total_responded'] }}</span>
                        </div>
                        <hr>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600">Attending</span>
                            <span class="font-semibold text-green-600">{{ $rsvpStats['attending'] }} ({{ $rsvpStats['attending_guests'] }} guests)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-red-600">Not Attending</span>
                            <span class="font-semibold text-red-600">{{ $rsvpStats['not_attending'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-yellow-600">Maybe</span>
                            <span class="font-semibold text-yellow-600">{{ $rsvpStats['maybe'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Pending</span>
                            <span class="font-semibold text-gray-400">{{ $rsvpStats['pending'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Period Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Period Statistics</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Page Views</span>
                            <span class="font-semibold">{{ number_format($visitorStats['totals']['page_views']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Unique Visitors</span>
                            <span class="font-semibold">{{ number_format($visitorStats['totals']['unique_visitors']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">RSVPs Submitted</span>
                            <span class="font-semibold">{{ number_format($visitorStats['totals']['rsvp_submissions']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">WhatsApp Shares</span>
                            <span class="font-semibold">{{ number_format($visitorStats['totals']['whatsapp_shares']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Gift Account Copies</span>
                            <span class="font-semibold">{{ number_format($visitorStats['totals']['gift_copy_clicks']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Map Clicks</span>
                            <span class="font-semibold">{{ number_format($visitorStats['totals']['map_clicks']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Options -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Export Data</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('invitations.export.guests', $invitation) }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Export Guests (CSV)
                    </a>
                    <a href="{{ route('invitations.export.rsvps', $invitation) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Export RSVPs (CSV)
                    </a>
                    <a href="{{ route('invitations.export.analytics', $invitation) }}" 
                       class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                        Export Analytics (CSV)
                    </a>
                    <a href="{{ route('invitations.export.summary', $invitation) }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Export Summary Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
