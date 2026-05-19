<x-app-layout>
    {{-- Page Header --}}
    <x-premium.page-header 
        title="Create New Invitation"
        description="Start by filling in the basic details of your wedding invitation."
    >
        <x-slot:actions>
            <x-premium.button href="{{ route('invitations.index') }}" variant="ghost" icon="arrow-left">
                Back to List
            </x-premium.button>
        </x-slot:actions>
    </x-premium.page-header>

    <form method="POST" action="{{ route('invitations.store') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Couple Information --}}
                <x-premium.card>
                    <h3 class="section-title mb-6">Couple Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-premium.form-input 
                            name="bride_name" 
                            label="Bride's Name" 
                            placeholder="Enter bride's full name"
                            :required="true"
                        />
                        
                        <x-premium.form-input 
                            name="groom_name" 
                            label="Groom's Name" 
                            placeholder="Enter groom's full name"
                            :required="true"
                        />
                        
                        <x-premium.form-input 
                            name="bride_parent" 
                            label="Bride's Parents"
                            placeholder="e.g., Mr. & Mrs. Smith"
                        />
                        
                        <x-premium.form-input 
                            name="groom_parent" 
                            label="Groom's Parents"
                            placeholder="e.g., Mr. & Mrs. Johnson"
                        />
                    </div>
                </x-premium.card>

                {{-- Event Details --}}
                <x-premium.card>
                    <h3 class="section-title mb-6">Event Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-premium.form-input 
                            type="date"
                            name="event_date" 
                            label="Wedding Date"
                            :required="true"
                        />
                        
                        <x-premium.form-input 
                            name="location" 
                            label="Venue / Location"
                            placeholder="e.g., Grand Ballroom, Hotel Name"
                        />
                        
                        <x-premium.form-input 
                            name="google_maps_url" 
                            label="Google Maps URL"
                            placeholder="https://maps.google.com/..."
                            hint="Paste the share link from Google Maps"
                        />
                        
                        <x-premium.form-input 
                            name="dress_code" 
                            label="Dress Code"
                            placeholder="e.g., Formal / Black Tie"
                        />
                    </div>
                </x-premium.card>

                {{-- Invitation Content --}}
                <x-premium.card>
                    <h3 class="section-title mb-6">Invitation Content</h3>
                    
                    <div class="space-y-6">
                        <x-premium.form-input 
                            name="title" 
                            label="Invitation Title"
                            placeholder="e.g., The Wedding of Sarah & John"
                            hint="This will be displayed as the main title"
                        />
                        
                        <x-premium.form-textarea 
                            name="opening_message" 
                            label="Opening Message"
                            placeholder="Write a heartfelt message to your guests..."
                            hint="This message will appear at the top of your invitation"
                            :rows="4"
                        />
                    </div>
                </x-premium.card>

                {{-- Cover Image --}}
                <x-premium.card>
                    <h3 class="section-title mb-6">Cover Image</h3>
                    
                    <div 
                        x-data="{ 
                            preview: null,
                            handleFileSelect(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => this.preview = e.target.result;
                                    reader.readAsDataURL(file);
                                }
                            }
                        }"
                        class="space-y-4"
                    >
                        <div 
                            class="relative border-2 border-dashed border-ivory-300 rounded-xl p-8 text-center hover:border-gold-400 transition-colors cursor-pointer"
                            x-on:click="$refs.coverInput.click()"
                        >
                            <template x-if="!preview">
                                <div>
                                    <svg class="w-12 h-12 mx-auto text-charcoal-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-charcoal-600 font-medium mb-1">Click to upload cover image</p>
                                    <p class="text-sm text-charcoal-400">PNG, JPG, WEBP up to 5MB</p>
                                </div>
                            </template>
                            
                            <template x-if="preview">
                                <div class="relative">
                                    <img :src="preview" class="max-h-48 mx-auto rounded-lg">
                                    <button 
                                        type="button"
                                        x-on:click.stop="preview = null; $refs.coverInput.value = ''"
                                        class="absolute top-2 right-2 w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            
                            <input 
                                type="file" 
                                name="cover_image" 
                                x-ref="coverInput"
                                x-on:change="handleFileSelect($event)"
                                accept="image/png,image/jpeg,image/webp"
                                class="hidden"
                            >
                        </div>
                        
                        @error('cover_image')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </x-premium.card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Template Selection --}}
                <x-premium.card>
                    <h3 class="section-title mb-4">Choose Template</h3>
                    
                    <div class="space-y-3">
                        @forelse($templates as $template)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-ivory-200 cursor-pointer hover:border-gold-300 has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50 transition-colors">
                                <input 
                                    type="radio" 
                                    name="template_id" 
                                    value="{{ $template->id }}"
                                    class="w-4 h-4 text-gold-500 border-charcoal-300 focus:ring-gold-500"
                                    {{ old('template_id') == $template->id ? 'checked' : '' }}
                                >
                                <div class="flex-1">
                                    <p class="font-medium text-charcoal-800">{{ $template->name }}</p>
                                    <p class="text-xs text-charcoal-500">{{ $template->description ?? 'Elegant design' }}</p>
                                </div>
                                @if($template->is_premium)
                                    <x-premium.badge variant="gold" size="sm">Premium</x-premium.badge>
                                @endif
                            </label>
                        @empty
                            <p class="text-sm text-charcoal-500">No templates available</p>
                        @endforelse
                    </div>
                    
                    @error('template_id')
                        <p class="form-error mt-2">{{ $message }}</p>
                    @enderror
                </x-premium.card>

                {{-- Settings --}}
                <x-premium.card>
                    <h3 class="section-title mb-4">Settings</h3>
                    
                    <div class="space-y-4">
                        <label class="flex items-center justify-between">
                            <span class="text-sm text-charcoal-700">Enable RSVP</span>
                            <input 
                                type="checkbox" 
                                name="settings[rsvp_enabled]" 
                                value="1"
                                checked
                                class="w-5 h-5 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                            >
                        </label>
                        
                        <label class="flex items-center justify-between">
                            <span class="text-sm text-charcoal-700">Enable Gift Section</span>
                            <input 
                                type="checkbox" 
                                name="settings[gift_enabled]" 
                                value="1"
                                checked
                                class="w-5 h-5 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                            >
                        </label>
                        
                        <label class="flex items-center justify-between">
                            <span class="text-sm text-charcoal-700">Show Countdown</span>
                            <input 
                                type="checkbox" 
                                name="settings[countdown_enabled]" 
                                value="1"
                                checked
                                class="w-5 h-5 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                            >
                        </label>
                        
                        <label class="flex items-center justify-between">
                            <span class="text-sm text-charcoal-700">Enable Guest Book</span>
                            <input 
                                type="checkbox" 
                                name="settings[guest_book_enabled]" 
                                value="1"
                                checked
                                class="w-5 h-5 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                            >
                        </label>
                    </div>
                </x-premium.card>

                {{-- Actions --}}
                <x-premium.card>
                    <div class="space-y-3">
                        <x-premium.button type="submit" variant="primary" class="w-full">
                            Create Invitation
                        </x-premium.button>
                        
                        <x-premium.button href="{{ route('invitations.index') }}" variant="ghost" class="w-full">
                            Cancel
                        </x-premium.button>
                    </div>
                    
                    <p class="text-xs text-charcoal-400 text-center mt-4">
                        Your invitation will be saved as a draft. You can publish it later.
                    </p>
                </x-premium.card>
            </div>
        </div>
    </form>
</x-app-layout>
