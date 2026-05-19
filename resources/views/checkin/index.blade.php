<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Check-In Dashboard') }} - {{ $invitation->couple_name }}
            </h2>
            <a href="{{ route('invitations.show', $invitation) }}" class="text-indigo-600 hover:text-indigo-800">
                &larr; Back to Invitation
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                    <div class="text-sm text-gray-500">Total Guests</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-green-600">{{ $stats['checked_in'] }}</div>
                    <div class="text-sm text-gray-500">Checked In</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-yellow-600">{{ $stats['total'] - $stats['checked_in'] }}</div>
                    <div class="text-sm text-gray-500">Pending</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-indigo-600">{{ $stats['expected_attending'] }}</div>
                    <div class="text-sm text-gray-500">Expected (RSVP)</div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-wrap gap-4 items-center justify-between">
                        <form action="{{ route('invitations.checkin.qr.bulk-generate', $invitation) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                Generate All QR Codes
                            </button>
                        </form>

                        <!-- Filter Form -->
                        <form method="GET" class="flex gap-2">
                            <input type="text" name="search" placeholder="Search guest..."
                                value="{{ request('search') }}"
                                class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <select name="status" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Status</option>
                                <option value="checked_in" {{ request('status') === 'checked_in' ? 'selected' : '' }}>Checked In</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Filter</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Guests Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                            {{ session('warning') }}
                        </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RSVP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($guests as $guest)
                            <tr class="{{ $guest->is_checked_in ? 'bg-green-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $guest->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $guest->phone_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $guest->category_color }}-100 text-{{ $guest->category_color }}-800">
                                        {{ $guest->category_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($guest->rsvp)
                                        {{ $guest->rsvp->attendance_status->label() }}
                                        ({{ $guest->rsvp->attendance_count }} pax)
                                    @else
                                        <span class="text-gray-400">No RSVP</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($guest->is_checked_in)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Checked In
                                        </span>
                                        <div class="text-xs text-gray-500">{{ $guest->checked_in_at->format('H:i') }}</div>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        @if(!$guest->is_checked_in)
                                            <form action="{{ route('invitations.checkin.check-in', [$invitation, $guest]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">Check In</button>
                                            </form>
                                        @else
                                            <form action="{{ route('invitations.checkin.undo', [$invitation, $guest]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">Undo</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('invitations.checkin.qr.download', [$invitation, $guest]) }}"
                                           class="text-indigo-600 hover:text-indigo-900">QR</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No guests found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $guests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
