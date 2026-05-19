<x-app-layout>
    {{-- Page Header --}}
    <x-premium.page-header 
        title="Welcome back, {{ explode(' ', Auth::user()->name)[0] }}!"
        description="Here's what's happening with your wedding invitations today."
    >
        <x-slot:actions>
            <x-premium.button href="{{ route('invitations.create') }}" variant="primary" icon="plus">
                Create Invitation
            </x-premium.button>
        </x-slot:actions>
    </x-premium.page-header>

    {{-- Stats Overview --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        {{-- Total Invitations --}}
        <x-premium.stat-card 
            :value="$invitationStats['total']"
            label="Total Invitations"
            icon="envelope"
            iconColor="gold"
        >
            <x-slot:footer>
                <div class="flex items-center gap-4 text-xs">
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-charcoal-600">{{ $invitationStats['published'] }} Published</span>
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span class="text-charcoal-600">{{ $invitationStats['draft'] }} Draft</span>
                    </span>
                </div>
            </x-slot:footer>
        </x-premium.stat-card>

        {{-- Total Guests --}}
        <x-premium.stat-card 
            :value="$rsvpStats['total_guests']"
            label="Total Guests"
            icon="users"
            iconColor="blue"
        >
            <x-slot:footer>
                <div class="flex items-center gap-1 text-xs text-charcoal-500">
                    <span>{{ $rsvpStats['total_rsvps'] }} have responded</span>
                </div>
            </x-slot:footer>
        </x-premium.stat-card>

        {{-- RSVPs --}}
        <x-premium.stat-card 
            :value="$rsvpStats['attending']"
            label="Confirmed Attending"
            icon="check-circle"
            iconColor="emerald"
        >
            <x-slot:footer>
                <div class="flex items-center gap-1 text-xs text-emerald-600">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>~{{ $rsvpStats['estimated_attendance'] }} estimated attendees</span>
                </div>
            </x-slot:footer>
        </x-premium.stat-card>

        {{-- Page Views --}}
        <x-premium.stat-card 
            :value="number_format($invitationStats['total_views'])"
            label="Total Views"
            icon="eye"
            iconColor="purple"
        >
            <x-slot:footer>
                <div class="flex items-center gap-1 text-xs text-charcoal-500">
                    <span>{{ number_format($invitationStats['unique_visitors']) }} unique visitors</span>
                </div>
            </x-slot:footer>
        </x-premium.stat-card>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        {{-- Left Column - Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Recent Invitations --}}
            <x-premium.card :padding="false">
                <div class="card-header flex items-center justify-between">
                    <h3 class="section-title">Recent Invitations</h3>
                    <a href="{{ route('invitations.index') }}" class="text-sm font-medium text-gold-600 hover:text-gold-700 transition-colors">
                        View All
                    </a>
                </div>

                @if($recentInvitations->count() > 0)
                    <div class="divide-y divide-ivory-100">
                        @foreach($recentInvitations as $invitation)
                            <div class="flex items-center gap-4 p-4 hover:bg-ivory-50 transition-colors">
                                {{-- Invitation Preview/Icon --}}
                                <div class="w-14 h-14 rounded-xl bg-gradient-gold flex items-center justify-center flex-shrink-0 shadow-soft">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>

                                {{-- Invitation Details --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-semibold text-charcoal-800 truncate">
                                            {{ $invitation->bride_name }} & {{ $invitation->groom_name }}
                                        </h4>
                                        <x-premium.badge 
                                            :variant="$invitation->status->value === 'published' ? 'published' : ($invitation->status->value === 'draft' ? 'draft' : 'neutral')"
                                            size="sm"
                                            :dot="true"
                                        >
                                            {{ ucfirst($invitation->status->value) }}
                                        </x-premium.badge>
                                    </div>
                                    <p class="text-sm text-charcoal-500 truncate">
                                        {{ $invitation->slug }}
                                    </p>
                                    <div class="flex items-center gap-4 mt-2 text-xs text-charcoal-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ number_format($invitation->view_count) }} views
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                            {{ $invitation->guests_count ?? 0 }} guests
                                        </span>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <x-premium.dropdown-action>
                                    <x-premium.dropdown-item :href="route('invitations.show', $invitation)" icon="eye">
                                        View Details
                                    </x-premium.dropdown-item>
                                    <x-premium.dropdown-item :href="route('invitations.edit', $invitation)" icon="edit">
                                        Edit
                                    </x-premium.dropdown-item>
                                    <x-premium.dropdown-item :href="route('invitations.guests.index', ['invitation' => $invitation->id])" icon="users">
                                        Manage Guests
                                    </x-premium.dropdown-item>
                                </x-premium.dropdown-action>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-premium.empty-state
                        icon="envelope"
                        title="No invitations yet"
                        description="Create your first wedding invitation to get started."
                        :action="route('invitations.create')"
                        actionLabel="Create Invitation"
                    />
                @endif
            </x-premium.card>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('invitations.create') }}" class="card card-body card-hover group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gold-100 text-gold-600 flex items-center justify-center group-hover:bg-gold-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-charcoal-800">New Invitation</h4>
                            <p class="text-sm text-charcoal-500">Create a new wedding invitation</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('invitations.index') }}" class="card card-body card-hover group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-charcoal-800">Manage Invitations</h4>
                            <p class="text-sm text-charcoal-500">View and manage your invitations</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('invitations.index') }}" class="card card-body card-hover group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center group-hover:bg-purple-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-charcoal-800">View Analytics</h4>
                            <p class="text-sm text-charcoal-500">Select an invitation to view analytics</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Right Column - Sidebar --}}
        <div class="space-y-6">
            {{-- RSVP Summary --}}
            <x-premium.card>
                <h3 class="section-title mb-4">RSVP Summary</h3>
                
                @if($rsvpStats['total_guests'] > 0)
                    {{-- Progress Ring Visual --}}
                    <div class="flex items-center justify-center mb-6">
                        <div class="relative">
                            <svg class="w-32 h-32 transform -rotate-90">
                                <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="12" fill="none" class="text-ivory-200"/>
                                @php
                                    $attendingPercent = $rsvpStats['total_guests'] > 0 ? ($rsvpStats['attending'] / $rsvpStats['total_guests']) * 100 : 0;
                                    $notAttendingPercent = $rsvpStats['total_guests'] > 0 ? ($rsvpStats['not_attending'] / $rsvpStats['total_guests']) * 100 : 0;
                                    $maybePercent = $rsvpStats['total_guests'] > 0 ? ($rsvpStats['maybe'] / $rsvpStats['total_guests']) * 100 : 0;
                                    $circumference = 2 * 3.14159 * 56;
                                @endphp
                                <circle 
                                    cx="64" cy="64" r="56" 
                                    stroke="currentColor" 
                                    stroke-width="12" 
                                    fill="none" 
                                    class="text-emerald-500"
                                    stroke-dasharray="{{ $circumference }}"
                                    stroke-dashoffset="{{ $circumference - ($circumference * $attendingPercent / 100) }}"
                                />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-display font-bold text-charcoal-800">{{ round($attendingPercent) }}%</span>
                                <span class="text-xs text-charcoal-500">Confirmed</span>
                            </div>
                        </div>
                    </div>

                    {{-- Stats List --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-ivory-100">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                <span class="text-sm text-charcoal-600">Attending</span>
                            </div>
                            <span class="font-semibold text-charcoal-800">{{ $rsvpStats['attending'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-ivory-100">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                <span class="text-sm text-charcoal-600">Not Attending</span>
                            </div>
                            <span class="font-semibold text-charcoal-800">{{ $rsvpStats['not_attending'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-ivory-100">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                <span class="text-sm text-charcoal-600">Maybe</span>
                            </div>
                            <span class="font-semibold text-charcoal-800">{{ $rsvpStats['maybe'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-charcoal-300"></span>
                                <span class="text-sm text-charcoal-600">Pending</span>
                            </div>
                            <span class="font-semibold text-charcoal-800">{{ $rsvpStats['pending'] }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-6">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-ivory-200 flex items-center justify-center">
                            <svg class="w-8 h-8 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <p class="text-sm text-charcoal-500">No RSVPs yet</p>
                        <p class="text-xs text-charcoal-400 mt-1">Add guests to start tracking RSVPs</p>
                    </div>
                @endif
            </x-premium.card>

            {{-- Recent RSVPs --}}
            <x-premium.card>
                <h3 class="section-title mb-4">Recent RSVPs</h3>
                
                @if($recentRsvps->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentRsvps->take(5) as $rsvp)
                            <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-ivory-100' : '' }}">
                                <x-premium.avatar :name="$rsvp->guest->name ?? 'Guest'" size="sm" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-charcoal-800 truncate">
                                        {{ $rsvp->guest->name ?? 'Unknown Guest' }}
                                    </p>
                                    <p class="text-xs text-charcoal-400">
                                        {{ $rsvp->responded_at?->diffForHumans() }}
                                    </p>
                                </div>
                                <x-premium.badge 
                                    :variant="$rsvp->attendance_status->value === 'attending' ? 'attending' : ($rsvp->attendance_status->value === 'not_attending' ? 'not_attending' : 'maybe')"
                                    size="sm"
                                >
                                    {{ ucfirst(str_replace('_', ' ', $rsvp->attendance_status->value)) }}
                                </x-premium.badge>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($recentRsvps->count() > 5)
                        <div class="mt-4 pt-4 border-t border-ivory-200">
                            <a href="{{ $recentInvitations->first() ? route('invitations.analytics.index', $recentInvitations->first()) : route('invitations.index') }}" class="text-sm font-medium text-gold-600 hover:text-gold-700 transition-colors">
                                View all RSVPs &rarr;
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-6">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-ivory-200 flex items-center justify-center">
                            <svg class="w-8 h-8 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-charcoal-500">No responses yet</p>
                        <p class="text-xs text-charcoal-400 mt-1">RSVPs will appear here</p>
                    </div>
                @endif
            </x-premium.card>

            {{-- Weekly Analytics Preview --}}
            <x-premium.card>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="section-title">This Week</h3>
                    <a href="{{ $recentInvitations->first() ? route('invitations.analytics.index', $recentInvitations->first()) : route('invitations.index') }}" class="text-xs font-medium text-gold-600 hover:text-gold-700 transition-colors">
                        Details
                    </a>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-3 rounded-xl bg-ivory-100">
                        <p class="text-2xl font-display font-bold text-charcoal-800">{{ number_format($analyticsSummary['weekly']['page_views']) }}</p>
                        <p class="text-xs text-charcoal-500 mt-1">Page Views</p>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-ivory-100">
                        <p class="text-2xl font-display font-bold text-charcoal-800">{{ number_format($analyticsSummary['weekly']['unique_visitors']) }}</p>
                        <p class="text-xs text-charcoal-500 mt-1">Visitors</p>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-ivory-100">
                        <p class="text-2xl font-display font-bold text-charcoal-800">{{ number_format($analyticsSummary['weekly']['rsvp_submissions']) }}</p>
                        <p class="text-xs text-charcoal-500 mt-1">RSVPs</p>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-ivory-100">
                        <p class="text-2xl font-display font-bold text-charcoal-800">{{ number_format($analyticsSummary['weekly']['whatsapp_shares']) }}</p>
                        <p class="text-xs text-charcoal-500 mt-1">Shares</p>
                    </div>
                </div>

                {{-- Today's highlight --}}
                <div class="mt-4 p-3 rounded-xl bg-gold-50 border border-gold-200">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                        </svg>
                        <span class="text-xs font-semibold text-gold-700 uppercase tracking-wide">Today</span>
                    </div>
                    <p class="text-sm text-charcoal-700">
                        <span class="font-semibold">{{ $analyticsSummary['today']['page_views'] }}</span> views, 
                        <span class="font-semibold">{{ $analyticsSummary['today']['unique_visitors'] }}</span> visitors, 
                        <span class="font-semibold">{{ $analyticsSummary['today']['rsvp_submissions'] }}</span> RSVPs
                    </p>
                </div>
            </x-premium.card>
        </div>
    </div>
</x-app-layout>
