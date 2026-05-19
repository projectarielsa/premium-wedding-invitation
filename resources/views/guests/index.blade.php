<x-app-layout>
    {{-- Page Header --}}
    <x-premium.page-header 
        title="Guest List"
        description="Manage guests for {{ $invitation->couple_name }}'s wedding invitation."
    >
        <x-slot:actions>
            <x-premium.button href="{{ route('invitations.show', $invitation) }}" variant="ghost" icon="arrow-left">
                Back to Invitation
            </x-premium.button>
            <x-premium.button 
                x-data
                x-on:click="$dispatch('open-modal', 'import-guests')"
                variant="outline"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import CSV
            </x-premium.button>
            <x-premium.button 
                href="{{ route('invitations.guests.export', $invitation) }}" 
                variant="outline"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export
            </x-premium.button>
            <x-premium.button 
                x-data
                x-on:click="$dispatch('open-modal', 'add-guest')"
                variant="primary" 
                icon="plus"
            >
                Add Guest
            </x-premium.button>
        </x-slot:actions>
    </x-premium.page-header>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
        <div class="card card-body text-center">
            <p class="text-2xl font-display font-bold text-charcoal-800">{{ $guests->total() }}</p>
            <p class="text-xs text-charcoal-500">Total Guests</p>
        </div>
        @foreach($categoryCounts as $category => $count)
            <div class="card card-body text-center">
                <p class="text-2xl font-display font-bold text-charcoal-800">{{ $count }}</p>
                <p class="text-xs text-charcoal-500">{{ ucfirst($category) }}</p>
            </div>
        @endforeach
    </div>

    {{-- Filters & Search --}}
    <x-premium.card class="mb-6">
        <form method="GET" action="{{ route('invitations.guests.index', $invitation) }}" class="flex flex-col lg:flex-row gap-4">
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
                        value="{{ request('search') }}"
                        placeholder="Search by name, phone, or email..." 
                        class="form-input pl-10"
                    >
                </div>
            </div>
            
            {{-- Category Filter --}}
            <div class="w-full lg:w-40">
                <select name="category" class="form-input">
                    <option value="">All Categories</option>
                    @foreach(\App\Enums\GuestCategory::cases() as $cat)
                        <option value="{{ $cat->value }}" {{ request('category') === $cat->value ? 'selected' : '' }}>
                            {{ $cat->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- Status Filter --}}
            <div class="w-full lg:w-40">
                <select name="status" class="form-input">
                    <option value="">All Status</option>
                    <option value="responded" {{ request('status') === 'responded' ? 'selected' : '' }}>Responded</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="whatsapp_sent" {{ request('status') === 'whatsapp_sent' ? 'selected' : '' }}>WhatsApp Sent</option>
                    <option value="whatsapp_pending" {{ request('status') === 'whatsapp_pending' ? 'selected' : '' }}>WhatsApp Pending</option>
                    <option value="checked_in" {{ request('status') === 'checked_in' ? 'selected' : '' }}>Checked In</option>
                </select>
            </div>
            
            {{-- Sort --}}
            <div class="w-full lg:w-40">
                <select name="sort_by" class="form-input">
                    <option value="created_at" {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Newest First</option>
                    <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="priority" {{ request('sort_by') === 'priority' ? 'selected' : '' }}>Priority</option>
                </select>
            </div>
            
            <x-premium.button type="submit" variant="secondary">
                Filter
            </x-premium.button>
            
            @if(request()->hasAny(['search', 'category', 'status', 'sort_by']))
                <a href="{{ route('invitations.guests.index', $invitation) }}" class="btn btn-ghost">
                    Clear
                </a>
            @endif
        </form>
    </x-premium.card>

    {{-- Guest List --}}
    @if($guests->count() > 0)
        <x-premium.card :padding="false">
            {{-- Bulk Actions --}}
            <div 
                x-data="{ 
                    selectedGuests: [],
                    selectAll: false,
                    toggleAll() {
                        if (this.selectAll) {
                            this.selectedGuests = [...document.querySelectorAll('input[name=\'guest_ids[]\']')].map(el => el.value);
                        } else {
                            this.selectedGuests = [];
                        }
                    }
                }"
                class="w-full"
            >
                {{-- Bulk Action Bar --}}
                <div 
                    x-show="selectedGuests.length > 0"
                    x-cloak
                    class="flex items-center justify-between px-6 py-3 bg-gold-50 border-b border-gold-200"
                >
                    <span class="text-sm text-gold-700">
                        <span x-text="selectedGuests.length"></span> guest(s) selected
                    </span>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('invitations.guests.bulk-whatsapp-sent', $invitation) }}" class="inline">
                            @csrf
                            <template x-for="id in selectedGuests" :key="id">
                                <input type="hidden" name="guest_ids[]" :value="id">
                            </template>
                            <button type="submit" class="btn btn-sm btn-ghost text-emerald-600">
                                Mark WhatsApp Sent
                            </button>
                        </form>
                        <form method="POST" action="{{ route('invitations.guests.bulk-delete', $invitation) }}" class="inline" onsubmit="return confirm('Delete selected guests?')">
                            @csrf
                            @method('DELETE')
                            <template x-for="id in selectedGuests" :key="id">
                                <input type="hidden" name="guest_ids[]" :value="id">
                            </template>
                            <button type="submit" class="btn btn-sm btn-ghost text-red-600">
                                Delete Selected
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th class="w-12">
                                    <input 
                                        type="checkbox" 
                                        x-model="selectAll"
                                        x-on:change="toggleAll()"
                                        class="w-4 h-4 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                                    >
                                </th>
                                <th>Guest</th>
                                <th>Contact</th>
                                <th>Category</th>
                                <th>RSVP Status</th>
                                <th>WhatsApp</th>
                                <th class="w-20"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guests as $guest)
                                <tr>
                                    <td>
                                        <input 
                                            type="checkbox" 
                                            name="guest_ids[]"
                                            value="{{ $guest->id }}"
                                            x-model="selectedGuests"
                                            class="w-4 h-4 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                                        >
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <x-premium.avatar :name="$guest->name" size="sm" />
                                            <div>
                                                <p class="font-medium text-charcoal-800">{{ $guest->name }}</p>
                                                @if($guest->unique_visit_count > 0)
                                                    <p class="text-xs text-charcoal-400">
                                                        Visited {{ $guest->unique_visit_count }}x
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="space-y-1">
                                            @if($guest->phone_number || $guest->whatsapp)
                                                <p class="text-sm text-charcoal-600">{{ $guest->whatsapp ?? $guest->phone_number }}</p>
                                            @endif
                                            @if($guest->email)
                                                <p class="text-xs text-charcoal-400">{{ $guest->email }}</p>
                                            @endif
                                            @if(!$guest->phone_number && !$guest->whatsapp && !$guest->email)
                                                <span class="text-xs text-charcoal-400">No contact info</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <x-premium.badge :variant="$guest->category->value === 'vip' ? 'gold' : 'neutral'" size="sm">
                                            {{ $guest->category->label() }}
                                        </x-premium.badge>
                                    </td>
                                    <td>
                                        @if($guest->rsvp && $guest->rsvp->responded_at)
                                            <x-premium.badge 
                                                :variant="$guest->rsvp->attendance_status->value === 'attending' ? 'attending' : ($guest->rsvp->attendance_status->value === 'not_attending' ? 'not_attending' : 'maybe')"
                                                size="sm"
                                                :dot="true"
                                            >
                                                {{ $guest->rsvp->attendance_status->label() }}
                                                @if($guest->rsvp->attendance_count > 1)
                                                    ({{ $guest->rsvp->attendance_count }})
                                                @endif
                                            </x-premium.badge>
                                        @else
                                            <x-premium.badge variant="pending" size="sm" :dot="true">
                                                Pending
                                            </x-premium.badge>
                                        @endif
                                    </td>
                                    <td>
                                        @if($guest->whatsapp_number)
                                            @if($guest->whatsapp_sent_at)
                                                <span class="text-xs text-emerald-600 flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Sent
                                                </span>
                                            @else
                                                <a 
                                                    href="{{ $guest->whatsapp_share_link }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-emerald-700 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors"
                                                    onclick="fetch('{{ route('invitations.guests.whatsapp', [$invitation, $guest]) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })"
                                                >
                                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                    </svg>
                                                    Send
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-xs text-charcoal-400">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <x-premium.dropdown-action>
                                            <x-premium.dropdown-item 
                                                x-data
                                                x-on:click="$dispatch('open-modal', 'edit-guest-{{ $guest->id }}')"
                                                icon="edit"
                                            >
                                                Edit
                                            </x-premium.dropdown-item>
                                            <button 
                                                type="button"
                                                class="dropdown-item w-full text-left"
                                                onclick="navigator.clipboard.writeText('{{ $guest->personalized_url }}'); alert('Link copied!');"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                                Copy Link
                                            </button>
                                            <hr class="my-2 border-ivory-200">
                                            <x-premium.dropdown-item 
                                                :href="route('invitations.guests.destroy', [$invitation, $guest])" 
                                                method="DELETE"
                                                icon="trash" 
                                                :danger="true"
                                                onclick="return confirm('Delete this guest?')"
                                            >
                                                Delete
                                            </x-premium.dropdown-item>
                                        </x-premium.dropdown-action>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </x-premium.card>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $guests->withQueryString()->links() }}
        </div>
    @else
        <x-premium.empty-state
            icon="users"
            title="No guests found"
            description="Add guests manually or import from a CSV file."
        >
            <div class="flex items-center justify-center gap-3 mt-6">
                <x-premium.button 
                    x-data
                    x-on:click="$dispatch('open-modal', 'add-guest')"
                    variant="primary" 
                    icon="plus"
                >
                    Add Guest
                </x-premium.button>
                <x-premium.button 
                    x-data
                    x-on:click="$dispatch('open-modal', 'import-guests')"
                    variant="outline"
                >
                    Import CSV
                </x-premium.button>
            </div>
        </x-premium.empty-state>
    @endif

    {{-- Add Guest Modal --}}
    <x-premium.modal name="add-guest" title="Add New Guest" maxWidth="lg">
        <form method="POST" action="{{ route('invitations.guests.store', $invitation) }}" class="space-y-4">
            @csrf
            
            <x-premium.form-input 
                name="name" 
                label="Guest Name" 
                placeholder="Enter full name"
                :required="true"
            />
            
            <div class="grid grid-cols-2 gap-4">
                <x-premium.form-input 
                    name="phone_number" 
                    label="Phone Number"
                    placeholder="08123456789"
                />
                
                <x-premium.form-input 
                    name="whatsapp" 
                    label="WhatsApp Number"
                    placeholder="08123456789"
                    hint="Leave empty if same as phone"
                />
            </div>
            
            <x-premium.form-input 
                type="email"
                name="email" 
                label="Email Address"
                placeholder="guest@email.com"
            />
            
            <div class="grid grid-cols-2 gap-4">
                <x-premium.form-select 
                    name="category" 
                    label="Category"
                    :options="collect(\App\Enums\GuestCategory::cases())->mapWithKeys(fn($c) => [$c->value => $c->label()])->toArray()"
                />
                
                <x-premium.form-input 
                    type="number"
                    name="max_attendees" 
                    label="Max Attendees"
                    value="2"
                    min="1"
                    max="10"
                />
            </div>
            
            <x-premium.form-textarea 
                name="notes" 
                label="Notes"
                placeholder="Optional notes about this guest..."
                :rows="2"
            />
        
            <x-slot:footer>
                <x-premium.button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'add-guest')">
                    Cancel
                </x-premium.button>
                <x-premium.button type="submit" variant="primary">
                    Add Guest
                </x-premium.button>
            </x-slot:footer>
        </form>
    </x-premium.modal>

    {{-- Import Guests Modal --}}
    <x-premium.modal name="import-guests" title="Import Guests from CSV" maxWidth="lg">
        <form method="POST" action="{{ route('invitations.guests.import', $invitation) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <x-premium.alert type="info">
                Upload a CSV file with columns: Name, Phone, WhatsApp, Email, Category, Max Attendees, Notes
            </x-premium.alert>
            
            <div>
                <label class="form-label">CSV File</label>
                <input 
                    type="file" 
                    name="file" 
                    accept=".csv,.txt"
                    required
                    class="form-input mt-2"
                >
            </div>
            
            <label class="flex items-center gap-2">
                <input 
                    type="checkbox" 
                    name="skip_header" 
                    value="1"
                    checked
                    class="w-4 h-4 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                >
                <span class="text-sm text-charcoal-700">Skip first row (header)</span>
            </label>
        
            <x-slot:footer>
                <x-premium.button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'import-guests')">
                    Cancel
                </x-premium.button>
                <x-premium.button type="submit" variant="primary">
                    Import
                </x-premium.button>
            </x-slot:footer>
        </form>
    </x-premium.modal>

    {{-- Edit Guest Modals (one per guest) --}}
    @foreach($guests as $guest)
        <x-premium.modal name="edit-guest-{{ $guest->id }}" title="Edit Guest" maxWidth="lg">
            <form method="POST" action="{{ route('invitations.guests.update', [$invitation, $guest]) }}" class="space-y-4">
                @csrf
                @method('PUT')
                
                <x-premium.form-input 
                    name="name" 
                    label="Guest Name" 
                    :value="$guest->name"
                    :required="true"
                />
                
                <div class="grid grid-cols-2 gap-4">
                    <x-premium.form-input 
                        name="phone_number" 
                        label="Phone Number"
                        :value="$guest->phone_number"
                    />
                    
                    <x-premium.form-input 
                        name="whatsapp" 
                        label="WhatsApp Number"
                        :value="$guest->whatsapp"
                    />
                </div>
                
                <x-premium.form-input 
                    type="email"
                    name="email" 
                    label="Email Address"
                    :value="$guest->email"
                />
                
                <div class="grid grid-cols-2 gap-4">
                    <x-premium.form-select 
                        name="category" 
                        label="Category"
                        :value="$guest->category->value"
                        :options="collect(\App\Enums\GuestCategory::cases())->mapWithKeys(fn($c) => [$c->value => $c->label()])->toArray()"
                    />
                    
                    <x-premium.form-input 
                        type="number"
                        name="max_attendees" 
                        label="Max Attendees"
                        :value="$guest->max_attendees"
                        min="1"
                        max="10"
                    />
                </div>
                
                <x-premium.form-textarea 
                    name="notes" 
                    label="Notes"
                    :value="$guest->notes"
                    :rows="2"
                />
            
                <x-slot:footer>
                    <x-premium.button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'edit-guest-{{ $guest->id }}')">
                        Cancel
                    </x-premium.button>
                    <x-premium.button type="submit" variant="primary">
                        Save Changes
                    </x-premium.button>
                </x-slot:footer>
            </form>
        </x-premium.modal>
    @endforeach
</x-app-layout>
