<x-app-layout>
    {{-- Page Header --}}
    <x-premium.page-header 
        title="My Invitations"
        description="Manage all your wedding invitations in one place."
    >
        <x-slot:actions>
            <x-premium.button href="{{ route('invitations.create') }}" variant="primary" icon="plus">
                Create Invitation
            </x-premium.button>
        </x-slot:actions>
    </x-premium.page-header>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="card card-body text-center">
            <p class="text-2xl font-display font-bold text-charcoal-800">{{ $stats['total'] }}</p>
            <p class="text-sm text-charcoal-500">Total</p>
        </div>
        <div class="card card-body text-center">
            <p class="text-2xl font-display font-bold text-emerald-600">{{ $stats['published'] }}</p>
            <p class="text-sm text-charcoal-500">Published</p>
        </div>
        <div class="card card-body text-center">
            <p class="text-2xl font-display font-bold text-amber-600">{{ $stats['draft'] }}</p>
            <p class="text-sm text-charcoal-500">Drafts</p>
        </div>
        <div class="card card-body text-center">
            <p class="text-2xl font-display font-bold text-charcoal-400">{{ $stats['archived'] }}</p>
            <p class="text-sm text-charcoal-500">Archived</p>
        </div>
    </div>

    {{-- Filters --}}
    <x-premium.card class="mb-6">
        <form method="GET" action="{{ route('invitations.index') }}" class="flex flex-col sm:flex-row gap-4">
            {{-- Search --}}
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        type="search" 
                        name="search" 
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search by couple name..." 
                        class="form-input pl-10"
                    >
                </div>
            </div>
            
            {{-- Status Filter --}}
            <div class="w-full sm:w-48">
                <select name="status" class="form-input">
                    <option value="">All Status</option>
                    <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ ($filters['status'] ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ ($filters['status'] ?? '') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            
            {{-- Sort --}}
            <div class="w-full sm:w-48">
                <select name="sort_by" class="form-input">
                    <option value="created_at" {{ ($filters['sort_by'] ?? 'created_at') === 'created_at' ? 'selected' : '' }}>Newest First</option>
                    <option value="event_date" {{ ($filters['sort_by'] ?? '') === 'event_date' ? 'selected' : '' }}>Event Date</option>
                    <option value="view_count" {{ ($filters['sort_by'] ?? '') === 'view_count' ? 'selected' : '' }}>Most Views</option>
                </select>
            </div>
            
            {{-- Submit --}}
            <x-premium.button type="submit" variant="secondary">
                Filter
            </x-premium.button>
            
            @if(!empty(array_filter($filters)))
                <a href="{{ route('invitations.index') }}" class="btn btn-ghost">
                    Clear
                </a>
            @endif
        </form>
    </x-premium.card>

    {{-- Invitations Grid --}}
    @if($invitations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($invitations as $invitation)
                <div class="card card-hover overflow-hidden group">
                    {{-- Cover Image / Gradient --}}
                    <div class="relative h-40 bg-gradient-to-br from-gold-400 via-gold-500 to-gold-600">
                        @if($invitation->cover_image_url)
                            <img 
                                src="{{ $invitation->cover_image_url }}" 
                                alt="{{ $invitation->couple_name }}"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                        @endif
                        
                        {{-- Status Badge --}}
                        <div class="absolute top-3 left-3">
                            <x-premium.badge 
                                :variant="$invitation->status->value === 'published' ? 'published' : ($invitation->status->value === 'draft' ? 'draft' : 'expired')"
                                :dot="true"
                            >
                                {{ ucfirst($invitation->status->value) }}
                            </x-premium.badge>
                        </div>
                        
                        {{-- Quick Actions Overlay --}}
                        <div class="absolute inset-0 bg-charcoal-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                            <a 
                                href="{{ route('invitations.show', $invitation) }}" 
                                class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white hover:bg-white/30 transition-colors"
                                title="View"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a 
                                href="{{ route('invitations.edit', $invitation) }}" 
                                class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white hover:bg-white/30 transition-colors"
                                title="Edit"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @if($invitation->status->value === 'published')
                                <a 
                                    href="{{ route('invitations.preview', $invitation) }}" 
                                    target="_blank"
                                    class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white hover:bg-white/30 transition-colors"
                                    title="Preview"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Content --}}
                    <div class="p-5">
                        <h3 class="font-display font-semibold text-lg text-charcoal-800 mb-1 truncate">
                            {{ $invitation->bride_name }} & {{ $invitation->groom_name }}
                        </h3>
                        
                        <p class="text-sm text-charcoal-500 mb-4 truncate">
                            {{ $invitation->slug }}
                        </p>
                        
                        {{-- Event Date --}}
                        @if($invitation->event_date)
                            <div class="flex items-center gap-2 text-sm text-charcoal-600 mb-4">
                                <svg class="w-4 h-4 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $invitation->event_date->format('d M Y') }}</span>
                                @if($invitation->days_until_event !== null)
                                    @if($invitation->days_until_event > 0)
                                        <span class="text-gold-600">({{ $invitation->days_until_event }} days left)</span>
                                    @elseif($invitation->days_until_event === 0)
                                        <span class="text-emerald-600">(Today!)</span>
                                    @else
                                        <span class="text-charcoal-400">(Past)</span>
                                    @endif
                                @endif
                            </div>
                        @endif
                        
                        {{-- Stats Row --}}
                        <div class="flex items-center gap-4 text-xs text-charcoal-500 pb-4 border-b border-ivory-200">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ number_format($invitation->view_count) }} views
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                {{ $invitation->guests_count ?? 0 }} guests
                            </span>
                        </div>
                        
                        {{-- Actions --}}
                        <div class="flex items-center justify-between pt-4">
                            <div class="flex items-center gap-2">
                                @if($invitation->status->value === 'draft')
                                    <form method="POST" action="{{ route('invitations.publish', $invitation) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Publish
                                        </button>
                                    </form>
                                @elseif($invitation->status->value === 'published')
                                    <form method="POST" action="{{ route('invitations.unpublish', $invitation) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-ghost">
                                            Unpublish
                                        </button>
                                    </form>
                                @endif
                            </div>
                            
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
                                <form method="POST" action="{{ route('invitations.duplicate', $invitation) }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="dropdown-item w-full text-left">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        Duplicate
                                    </button>
                                </form>
                                <hr class="my-2 border-ivory-200">
                                <x-premium.dropdown-item 
                                    :href="route('invitations.destroy', $invitation)" 
                                    method="DELETE"
                                    icon="trash" 
                                    :danger="true"
                                    onclick="return confirm('Are you sure you want to delete this invitation?')"
                                >
                                    Delete
                                </x-premium.dropdown-item>
                            </x-premium.dropdown-action>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        <div class="mt-8">
            {{ $invitations->withQueryString()->links() }}
        </div>
    @else
        <x-premium.empty-state
            icon="envelope"
            title="No invitations found"
            description="Create your first wedding invitation to get started."
            :action="route('invitations.create')"
            actionLabel="Create Invitation"
        />
    @endif
</x-app-layout>
