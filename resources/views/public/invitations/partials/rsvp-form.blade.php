{{-- RSVP Form Component --}}
@props([
    'invitation',
    'guest' => null,
    'theme' => 'light', // light, dark, gold
    'maxAttendees' => 5
])

@php
    $themes = [
        'light' => [
            'card' => 'bg-white border border-ivory-200 shadow-card',
            'title' => 'text-charcoal-800',
            'text' => 'text-charcoal-600',
            'muted' => 'text-charcoal-400',
            'label' => 'text-charcoal-700',
            'input' => 'bg-ivory-100 border-ivory-300 text-charcoal-800 focus:border-gold-500 focus:ring-gold-500/20',
            'radio' => 'text-gold-500 focus:ring-gold-500/20 border-ivory-400',
            'radioLabel' => 'text-charcoal-700 hover:bg-ivory-100',
            'button' => 'bg-gold-500 hover:bg-gold-600 text-white shadow-gold',
            'success' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
            'error' => 'bg-red-50 border-red-200 text-red-600'
        ],
        'dark' => [
            'card' => 'bg-charcoal-800/90 border border-charcoal-700 shadow-xl backdrop-blur-sm',
            'title' => 'text-white',
            'text' => 'text-charcoal-300',
            'muted' => 'text-charcoal-500',
            'label' => 'text-charcoal-200',
            'input' => 'bg-charcoal-900 border-charcoal-600 text-white focus:border-gold-500 focus:ring-gold-500/20 placeholder-charcoal-500',
            'radio' => 'text-gold-500 focus:ring-gold-500/20 border-charcoal-500 bg-charcoal-900',
            'radioLabel' => 'text-charcoal-200 hover:bg-charcoal-700',
            'button' => 'bg-gold-500 hover:bg-gold-400 text-charcoal-900 shadow-gold',
            'success' => 'bg-emerald-900/50 border-emerald-500/30 text-emerald-300',
            'error' => 'bg-red-900/50 border-red-500/30 text-red-300'
        ],
        'gold' => [
            'card' => 'bg-gradient-to-br from-charcoal-900 to-charcoal-800 border border-gold-500/30 shadow-gold-lg',
            'title' => 'text-gold-400',
            'text' => 'text-ivory-200',
            'muted' => 'text-ivory-400',
            'label' => 'text-gold-300',
            'input' => 'bg-charcoal-800 border-gold-500/30 text-ivory-100 focus:border-gold-500 focus:ring-gold-500/20 placeholder-ivory-500',
            'radio' => 'text-gold-500 focus:ring-gold-500/20 border-gold-500/50 bg-charcoal-800',
            'radioLabel' => 'text-ivory-200 hover:bg-charcoal-700',
            'button' => 'bg-gold-500 hover:bg-gold-400 text-charcoal-900 shadow-gold',
            'success' => 'bg-emerald-900/50 border-emerald-500/30 text-emerald-300',
            'error' => 'bg-red-900/50 border-red-500/30 text-red-300'
        ]
    ];
    
    $t = $themes[$theme] ?? $themes['light'];
@endphp

<div {{ $attributes->merge(['class' => $t['card'] . ' rounded-2xl overflow-hidden']) }}>
    <div class="p-6 md:p-8">
        {{-- Success State --}}
        <div x-show="rsvpSuccess" x-cloak class="text-center py-8">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-emerald-500/10 flex items-center justify-center">
                <svg class="w-10 h-10 text-emerald-500 animate-scale-in" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-2xl font-display font-bold {{ $t['title'] }} mb-3">Thank You!</h3>
            <p class="{{ $t['text'] }}">Your RSVP has been submitted successfully.</p>
            <p class="{{ $t['muted'] }} text-sm mt-2">We look forward to celebrating with you!</p>
        </div>

        {{-- Form --}}
        <form x-show="!rsvpSuccess" x-on:submit.prevent="submitRsvp()" class="space-y-6">
            {{-- Header --}}
            <div class="text-center mb-8">
                <h3 class="text-2xl font-display font-bold {{ $t['title'] }} mb-2">RSVP</h3>
                <p class="{{ $t['text'] }}">Please let us know if you'll be joining us</p>
                @if($guest)
                    <p class="{{ $t['muted'] }} text-sm mt-2">Responding for: <span class="font-medium">{{ $guest->name }}</span></p>
                @endif
            </div>

            {{-- Error Message --}}
            <div x-show="rsvpError" x-cloak class="{{ $t['error'] }} border rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
                <p x-text="rsvpError" class="text-sm"></p>
            </div>

            {{-- Guest Name (only if no guest token) --}}
            @if(!$guest)
                <div>
                    <label class="block text-sm font-medium {{ $t['label'] }} mb-2">Your Name *</label>
                    <input 
                        type="text" 
                        x-model="rsvpForm.guest_name"
                        required
                        placeholder="Enter your full name"
                        class="w-full px-4 py-3 rounded-xl border transition-all duration-200 {{ $t['input'] }}"
                    >
                </div>
            @endif

            {{-- Attendance Status --}}
            <div>
                <label class="block text-sm font-medium {{ $t['label'] }} mb-3">Will you attend? *</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    {{-- Attending --}}
                    <label class="relative cursor-pointer">
                        <input 
                            type="radio" 
                            name="attendance_status" 
                            value="attending"
                            x-model="rsvpForm.attendance_status"
                            class="peer sr-only"
                            required
                        >
                        <div class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 transition-all duration-200 {{ $t['radioLabel'] }} peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 border-current/10">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="font-medium">Yes, I'll be there</span>
                        </div>
                    </label>

                    {{-- Not Attending --}}
                    <label class="relative cursor-pointer">
                        <input 
                            type="radio" 
                            name="attendance_status" 
                            value="not_attending"
                            x-model="rsvpForm.attendance_status"
                            class="peer sr-only"
                        >
                        <div class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 transition-all duration-200 {{ $t['radioLabel'] }} peer-checked:border-red-500 peer-checked:bg-red-500/10 border-current/10">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="font-medium">Can't make it</span>
                        </div>
                    </label>

                    {{-- Maybe --}}
                    <label class="relative cursor-pointer">
                        <input 
                            type="radio" 
                            name="attendance_status" 
                            value="maybe"
                            x-model="rsvpForm.attendance_status"
                            class="peer sr-only"
                        >
                        <div class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 transition-all duration-200 {{ $t['radioLabel'] }} peer-checked:border-amber-500 peer-checked:bg-amber-500/10 border-current/10">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Maybe</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Number of Guests (only show if attending) --}}
            <div x-show="rsvpForm.attendance_status === 'attending'" x-transition>
                <label class="block text-sm font-medium {{ $t['label'] }} mb-2">Number of Guests</label>
                <div class="flex items-center gap-4">
                    <button 
                        type="button"
                        x-on:click="rsvpForm.attendance_count = Math.max(1, rsvpForm.attendance_count - 1)"
                        class="w-12 h-12 rounded-xl border {{ $t['input'] }} flex items-center justify-center hover:opacity-80 transition-opacity"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>
                    <div class="flex-1 text-center">
                        <span x-text="rsvpForm.attendance_count" class="text-3xl font-display font-bold {{ $t['title'] }}"></span>
                        <p class="{{ $t['muted'] }} text-sm">person(s)</p>
                    </div>
                    <button 
                        type="button"
                        x-on:click="rsvpForm.attendance_count = Math.min({{ $maxAttendees }}, rsvpForm.attendance_count + 1)"
                        class="w-12 h-12 rounded-xl border {{ $t['input'] }} flex items-center justify-center hover:opacity-80 transition-opacity"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>
                <p class="{{ $t['muted'] }} text-xs text-center mt-2">Maximum {{ $maxAttendees }} guests allowed</p>
            </div>

            {{-- Message/Wishes --}}
            <div>
                <label class="block text-sm font-medium {{ $t['label'] }} mb-2">Message / Wishes (Optional)</label>
                <textarea 
                    x-model="rsvpForm.message"
                    rows="4"
                    placeholder="Share your wishes for the happy couple..."
                    class="w-full px-4 py-3 rounded-xl border transition-all duration-200 resize-none {{ $t['input'] }}"
                ></textarea>
                <p class="{{ $t['muted'] }} text-xs text-right mt-1">
                    <span x-text="(rsvpForm.message || '').length"></span> / 1000
                </p>
            </div>

            {{-- Submit Button --}}
            <button 
                type="submit"
                :disabled="rsvpSubmitting || !rsvpForm.attendance_status"
                class="w-full py-4 rounded-xl font-semibold text-lg transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center gap-2 {{ $t['button'] }}"
            >
                <span x-show="!rsvpSubmitting">Send RSVP</span>
                <span x-show="rsvpSubmitting" class="flex items-center gap-2">
                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Submitting...
                </span>
            </button>
        </form>
    </div>
</div>
