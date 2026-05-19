<x-app-layout>
    {{-- Page Header --}}
    <x-premium.page-header 
        title="{{ $invitation->bride_name }} & {{ $invitation->groom_name }}"
        description="View invitation details and manage your wedding."
    >
        <x-slot:actions>
            <x-premium.button href="{{ route('invitations.index') }}" variant="ghost" icon="arrow-left">
                Back
            </x-premium.button>
            <x-premium.button href="{{ route('invitations.edit', $invitation) }}" variant="outline" icon="edit">
                Edit
            </x-premium.button>
            @if($invitation->status->value === 'published')
                <x-premium.button href="{{ route('invitations.preview', $invitation) }}" variant="primary" icon="external-link" target="_blank">
                    View Live
                </x-premium.button>
            @endif
        </x-slot:actions>
    </x-premium.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Status & Quick Actions Card --}}
            <x-premium.card>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <x-premium.badge 
                            :variant="$invitation->status->value === 'published' ? 'published' : ($invitation->status->value === 'draft' ? 'draft' : 'expired')"
                            size="lg"
                            :dot="true"
                        >
                            {{ ucfirst($invitation->status->value) }}
                        </x-premium.badge>
                        
                        @if($invitation->event_date)
                            <div class="text-sm text-charcoal-500">
                                @if($invitation->days_until_event > 0)
                                    <span class="font-semibold text-gold-600">{{ $invitation->days_until_event }}</span> days until wedding
                                @elseif($invitation->days_until_event === 0)
                                    <span class="font-semibold text-emerald-600">Today is the day!</span>
                                @else
                                    <span class="text-charcoal-400">Wedding has passed</span>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @if($invitation->status->value === 'draft')
                            <form method="POST" action="{{ route('invitations.publish', $invitation) }}">
                                @csrf
                                @method('PATCH')
                                <x-premium.button type="submit" variant="primary" size="sm">
                                    Publish Now
                                </x-premium.button>
                            </form>
                        @elseif($invitation->status->value === 'published')
                            <form method="POST" action="{{ route('invitations.unpublish', $invitation) }}">
                                @csrf
                                @method('PATCH')
                                <x-premium.button type="submit" variant="ghost" size="sm">
                                    Unpublish
                                </x-premium.button>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('invitations.duplicate', $invitation) }}">
                            @csrf
                            <x-premium.button type="submit" variant="ghost" size="sm" icon="duplicate">
                                Duplicate
                            </x-premium.button>
                        </form>
                    </div>
                </div>
                
                @if($invitation->status->value === 'published')
                    <div class="mt-4 p-4 bg-ivory-100 rounded-xl">
                        <p class="text-sm text-charcoal-600 mb-2">Public URL:</p>
                        <div class="flex items-center gap-2">
                            <code class="flex-1 px-3 py-2 bg-white rounded-lg text-sm text-charcoal-700 border border-ivory-200 truncate">
                                {{ $invitation->public_url }}
                            </code>
                            <button 
                                type="button"
                                onclick="navigator.clipboard.writeText('{{ $invitation->public_url }}'); alert('URL copied!');"
                                class="btn btn-sm btn-ghost"
                            >
                                Copy
                            </button>
                        </div>
                    </div>
                @endif
            </x-premium.card>

            {{-- Invitation Details --}}
            <x-premium.card>
                <h3 class="section-title mb-6">Invitation Details</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-charcoal-500 mb-1">Bride</p>
                        <p class="font-medium text-charcoal-800">{{ $invitation->bride_name }}</p>
                        @if($invitation->bride_parent)
                            <p class="text-sm text-charcoal-500 mt-1">Daughter of {{ $invitation->bride_parent }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <p class="text-sm text-charcoal-500 mb-1">Groom</p>
                        <p class="font-medium text-charcoal-800">{{ $invitation->groom_name }}</p>
                        @if($invitation->groom_parent)
                            <p class="text-sm text-charcoal-500 mt-1">Son of {{ $invitation->groom_parent }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <p class="text-sm text-charcoal-500 mb-1">Wedding Date</p>
                        <p class="font-medium text-charcoal-800">
                            {{ $invitation->event_date?->format('l, d F Y') ?? 'Not set' }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-charcoal-500 mb-1">Location</p>
                        <p class="font-medium text-charcoal-800">{{ $invitation->location ?? 'Not set' }}</p>
                        @if($invitation->google_maps_url)
                            <a href="{{ $invitation->google_maps_url }}" target="_blank" class="text-sm text-gold-600 hover:underline">
                                View on Maps &rarr;
                            </a>
                        @endif
                    </div>
                    
                    @if($invitation->dress_code)
                        <div>
                            <p class="text-sm text-charcoal-500 mb-1">Dress Code</p>
                            <p class="font-medium text-charcoal-800">{{ $invitation->dress_code }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <p class="text-sm text-charcoal-500 mb-1">Template</p>
                        <p class="font-medium text-charcoal-800">{{ $invitation->template?->name ?? 'Default' }}</p>
                    </div>
                </div>
                
                @if($invitation->opening_message)
                    <x-premium.divider />
                    <div>
                        <p class="text-sm text-charcoal-500 mb-2">Opening Message</p>
                        <p class="text-charcoal-700 italic font-accent">{{ $invitation->opening_message }}</p>
                    </div>
                @endif
            </x-premium.card>

            {{-- Events --}}
            <x-premium.card>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="section-title">Events</h3>
                    <x-premium.button href="{{ route('events.create', ['invitation' => $invitation->id]) }}" variant="outline" size="sm" icon="plus">
                        Add Event
                    </x-premium.button>
                </div>
                
                @if($invitation->events->count() > 0)
                    <div class="space-y-4">
                        @foreach($invitation->events as $event)
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-ivory-50 border border-ivory-200">
                                <div class="w-12 h-12 rounded-xl bg-gold-100 text-gold-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-semibold text-charcoal-800">{{ $event->title }}</h4>
                                        <x-premium.badge variant="gold" size="sm">{{ ucfirst($event->type->value) }}</x-premium.badge>
                                    </div>
                                    <p class="text-sm text-charcoal-600">
                                        {{ $event->event_date?->format('d M Y') }} 
                                        @if($event->start_time)
                                            at {{ $event->start_time->format('H:i') }}
                                        @endif
                                    </p>
                                    @if($event->venue)
                                        <p class="text-sm text-charcoal-500 mt-1">{{ $event->venue }}</p>
                                    @endif
                                </div>
                                <x-premium.dropdown-action>
                                    <x-premium.dropdown-item :href="route('events.edit', $event)" icon="edit">
                                        Edit
                                    </x-premium.dropdown-item>
                                    <x-premium.dropdown-item 
                                        :href="route('events.destroy', $event)" 
                                        method="DELETE"
                                        icon="trash" 
                                        :danger="true"
                                    >
                                        Delete
                                    </x-premium.dropdown-item>
                                </x-premium.dropdown-action>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-premium.empty-state
                        icon="calendar"
                        title="No events added"
                        description="Add events like ceremony, reception, etc."
                        :action="route('events.create', ['invitation' => $invitation->id])"
                        actionLabel="Add Event"
                    />
                @endif
            </x-premium.card>

            {{-- Gift Accounts --}}
            <x-premium.card>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="section-title">Gift Accounts</h3>
                    <x-premium.button href="{{ route('gift-accounts.create', ['invitation' => $invitation->id]) }}" variant="outline" size="sm" icon="plus">
                        Add Account
                    </x-premium.button>
                </div>
                
                @if($invitation->giftAccounts->count() > 0)
                    <div class="space-y-3">
                        @foreach($invitation->giftAccounts as $account)
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-ivory-50 border border-ivory-200">
                                <div class="w-10 h-10 rounded-lg bg-gold-100 text-gold-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-charcoal-800">{{ $account->account_name }}</p>
                                    <p class="text-sm text-charcoal-500 truncate">{{ $account->account_number }}</p>
                                </div>
                                <x-premium.badge variant="neutral" size="sm">{{ ucfirst(str_replace('_', ' ', $account->account_type->value)) }}</x-premium.badge>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-premium.empty-state
                        icon="gift"
                        title="No gift accounts"
                        description="Add bank accounts or e-wallets for gifts."
                    />
                @endif
            </x-premium.card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Stats Card --}}
            <x-premium.card>
                <h3 class="section-title mb-4">Statistics</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-ivory-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <span class="text-charcoal-600">Total Views</span>
                        </div>
                        <span class="text-xl font-display font-bold text-charcoal-800">{{ number_format($invitation->view_count) }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-3 border-b border-ivory-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <span class="text-charcoal-600">Unique Visitors</span>
                        </div>
                        <span class="text-xl font-display font-bold text-charcoal-800">{{ number_format($invitation->unique_visitor_count) }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gold-100 text-gold-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <span class="text-charcoal-600">Total Guests</span>
                        </div>
                        <span class="text-xl font-display font-bold text-charcoal-800">{{ $rsvpStats['total_invited'] }}</span>
                    </div>
                </div>
            </x-premium.card>

            {{-- RSVP Summary --}}
            <x-premium.card>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="section-title">RSVP Summary</h3>
                    <a href="{{ route('invitations.guests.index', ['invitation' => $invitation->id]) }}" class="text-sm text-gold-600 hover:text-gold-700">
                        View All
                    </a>
                </div>
                
                @if($rsvpStats['total_invited'] > 0)
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                <span class="text-sm text-charcoal-600">Attending</span>
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-charcoal-800">{{ $rsvpStats['attending'] }}</span>
                                <span class="text-xs text-charcoal-400 ml-1">({{ $rsvpStats['attending_guests'] }} guests)</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                <span class="text-sm text-charcoal-600">Not Attending</span>
                            </div>
                            <span class="font-semibold text-charcoal-800">{{ $rsvpStats['not_attending'] }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                <span class="text-sm text-charcoal-600">Maybe</span>
                            </div>
                            <span class="font-semibold text-charcoal-800">{{ $rsvpStats['maybe'] }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-charcoal-300"></span>
                                <span class="text-sm text-charcoal-600">Pending</span>
                            </div>
                            <span class="font-semibold text-charcoal-800">{{ $rsvpStats['total_invited'] - $rsvpStats['total_responded'] }}</span>
                        </div>
                    </div>
                    
                    <x-premium.divider />
                    
                    <div class="text-center">
                        <p class="text-sm text-charcoal-500">Estimated Attendance</p>
                        <p class="text-3xl font-display font-bold text-gold-600">{{ $rsvpStats['attending_guests'] }}</p>
                        <p class="text-xs text-charcoal-400">people</p>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-sm text-charcoal-500">No guests added yet</p>
                        <x-premium.button href="{{ route('guests.create', ['invitation' => $invitation->id]) }}" variant="outline" size="sm" class="mt-3">
                            Add Guests
                        </x-premium.button>
                    </div>
                @endif
            </x-premium.card>

            {{-- Quick Links --}}
            <x-premium.card>
                <h3 class="section-title mb-4">Quick Links</h3>
                
                <div class="space-y-2">
                    <a href="{{ route('invitations.guests.index', ['invitation' => $invitation->id]) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-ivory-100 transition-colors">
                        <svg class="w-5 h-5 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="text-sm text-charcoal-700">Manage Guests</span>
                    </a>
                    
                    <a href="{{ route('events.index', ['invitation' => $invitation->id]) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-ivory-100 transition-colors">
                        <svg class="w-5 h-5 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm text-charcoal-700">Manage Events</span>
                    </a>
                    
                    <a href="{{ route('gift-accounts.index', ['invitation' => $invitation->id]) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-ivory-100 transition-colors">
                        <svg class="w-5 h-5 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                        </svg>
                        <span class="text-sm text-charcoal-700">Gift Accounts</span>
                    </a>
                    
                    <a href="{{ route('analytics.index', ['invitation' => $invitation->id]) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-ivory-100 transition-colors">
                        <svg class="w-5 h-5 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="text-sm text-charcoal-700">View Analytics</span>
                    </a>
                </div>
            </x-premium.card>

            {{-- Danger Zone --}}
            <x-premium.card class="border-red-200">
                <h3 class="text-red-600 font-semibold mb-4">Danger Zone</h3>
                
                <form method="POST" action="{{ route('invitations.destroy', $invitation) }}" onsubmit="return confirm('Are you sure you want to delete this invitation? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <x-premium.button type="submit" variant="danger" class="w-full">
                        Delete Invitation
                    </x-premium.button>
                </form>
            </x-premium.card>
        </div>
    </div>
</x-app-layout>
