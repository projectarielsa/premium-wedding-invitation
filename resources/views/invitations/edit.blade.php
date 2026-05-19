<x-app-layout>
    {{-- Page Header --}}
    <x-premium.page-header 
        title="Edit Invitation"
        description="Update the details of {{ $invitation->couple_name }}'s wedding invitation."
    >
        <x-slot:actions>
            <x-premium.button href="{{ route('invitations.show', $invitation) }}" variant="ghost" icon="eye">
                View
            </x-premium.button>
            @if($invitation->status->value === 'published')
                <x-premium.button href="{{ route('invitations.preview', $invitation) }}" variant="outline" icon="external-link" target="_blank">
                    Preview Live
                </x-premium.button>
            @endif
        </x-slot:actions>
    </x-premium.page-header>

    {{-- Status Alert --}}
    @if($invitation->status->value === 'draft')
        <x-premium.alert type="info" class="mb-6">
            This invitation is currently a <strong>draft</strong>. Publish it to make it accessible to your guests.
        </x-premium.alert>
    @elseif($invitation->status->value === 'published')
        <x-premium.alert type="success" class="mb-6">
            This invitation is <strong>live</strong> and accessible at: 
            <a href="{{ $invitation->public_url }}" target="_blank" class="underline font-medium">{{ $invitation->public_url }}</a>
        </x-premium.alert>
    @endif

    <form method="POST" action="{{ route('invitations.update', $invitation) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        {{-- Tab Navigation --}}
        <div 
            x-data="{ activeTab: 'basic' }" 
            class="space-y-6"
        >
            <div class="card overflow-hidden">
                <div class="flex overflow-x-auto border-b border-ivory-200 scrollbar-hide">
                    <button 
                        type="button"
                        x-on:click="activeTab = 'basic'"
                        :class="activeTab === 'basic' ? 'border-gold-500 text-gold-600 bg-gold-50' : 'border-transparent text-charcoal-500 hover:text-charcoal-700'"
                        class="flex-shrink-0 px-6 py-4 text-sm font-medium border-b-2 transition-colors"
                    >
                        Basic Info
                    </button>
                    <button 
                        type="button"
                        x-on:click="activeTab = 'content'"
                        :class="activeTab === 'content' ? 'border-gold-500 text-gold-600 bg-gold-50' : 'border-transparent text-charcoal-500 hover:text-charcoal-700'"
                        class="flex-shrink-0 px-6 py-4 text-sm font-medium border-b-2 transition-colors"
                    >
                        Content
                    </button>
                    <button 
                        type="button"
                        x-on:click="activeTab = 'media'"
                        :class="activeTab === 'media' ? 'border-gold-500 text-gold-600 bg-gold-50' : 'border-transparent text-charcoal-500 hover:text-charcoal-700'"
                        class="flex-shrink-0 px-6 py-4 text-sm font-medium border-b-2 transition-colors"
                    >
                        Media
                    </button>
                    <button 
                        type="button"
                        x-on:click="activeTab = 'seo'"
                        :class="activeTab === 'seo' ? 'border-gold-500 text-gold-600 bg-gold-50' : 'border-transparent text-charcoal-500 hover:text-charcoal-700'"
                        class="flex-shrink-0 px-6 py-4 text-sm font-medium border-b-2 transition-colors"
                    >
                        SEO
                    </button>
                    <button 
                        type="button"
                        x-on:click="activeTab = 'settings'"
                        :class="activeTab === 'settings' ? 'border-gold-500 text-gold-600 bg-gold-50' : 'border-transparent text-charcoal-500 hover:text-charcoal-700'"
                        class="flex-shrink-0 px-6 py-4 text-sm font-medium border-b-2 transition-colors"
                    >
                        Settings
                    </button>
                </div>

                {{-- Basic Info Tab --}}
                <div x-show="activeTab === 'basic'" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-premium.form-input 
                            name="bride_name" 
                            label="Bride's Name" 
                            :value="$invitation->bride_name"
                            :required="true"
                        />
                        
                        <x-premium.form-input 
                            name="groom_name" 
                            label="Groom's Name" 
                            :value="$invitation->groom_name"
                            :required="true"
                        />
                        
                        <x-premium.form-input 
                            name="bride_parent" 
                            label="Bride's Parents"
                            :value="$invitation->bride_parent"
                        />
                        
                        <x-premium.form-input 
                            name="groom_parent" 
                            label="Groom's Parents"
                            :value="$invitation->groom_parent"
                        />
                    </div>
                    
                    <x-premium.divider />
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-premium.form-input 
                            type="date"
                            name="event_date" 
                            label="Wedding Date"
                            :value="$invitation->event_date?->format('Y-m-d')"
                            :required="true"
                        />
                        
                        <x-premium.form-input 
                            name="location" 
                            label="Venue / Location"
                            :value="$invitation->location"
                        />
                        
                        <x-premium.form-input 
                            name="google_maps_url" 
                            label="Google Maps URL"
                            :value="$invitation->google_maps_url"
                        />
                        
                        <x-premium.form-input 
                            name="dress_code" 
                            label="Dress Code"
                            :value="$invitation->dress_code"
                        />
                    </div>
                </div>

                {{-- Content Tab --}}
                <div x-show="activeTab === 'content'" x-cloak class="p-6 space-y-6">
                    <x-premium.form-input 
                        name="title" 
                        label="Invitation Title"
                        :value="$invitation->title"
                        hint="The main title displayed on your invitation"
                    />
                    
                    <x-premium.form-textarea 
                        name="opening_message" 
                        label="Opening Message"
                        :value="$invitation->opening_message"
                        :rows="4"
                    />
                    
                    <x-premium.form-input 
                        name="music_url" 
                        label="Background Music URL"
                        :value="$invitation->music_url"
                        placeholder="https://..."
                        hint="Direct link to an MP3 file"
                    />
                </div>

                {{-- Media Tab --}}
                <div x-show="activeTab === 'media'" x-cloak class="p-6 space-y-6">
                    {{-- Cover Image --}}
                    <div>
                        <label class="form-label">Cover Image</label>
                        
                        <div 
                            x-data="{ 
                                preview: '{{ $invitation->cover_image_url }}',
                                removeImage: false,
                                handleFileSelect(event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            this.preview = e.target.result;
                                            this.removeImage = false;
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                }
                            }"
                            class="mt-2"
                        >
                            <div 
                                class="relative border-2 border-dashed border-ivory-300 rounded-xl p-8 text-center hover:border-gold-400 transition-colors cursor-pointer"
                                x-on:click="$refs.coverInput.click()"
                            >
                                <template x-if="!preview || removeImage">
                                    <div>
                                        <svg class="w-12 h-12 mx-auto text-charcoal-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-charcoal-600 font-medium mb-1">Click to upload</p>
                                        <p class="text-sm text-charcoal-400">PNG, JPG, WEBP up to 5MB</p>
                                    </div>
                                </template>
                                
                                <template x-if="preview && !removeImage">
                                    <div class="relative">
                                        <img :src="preview" class="max-h-48 mx-auto rounded-lg">
                                        <button 
                                            type="button"
                                            x-on:click.stop="removeImage = true; preview = null; $refs.coverInput.value = ''"
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
                                <input type="hidden" name="remove_cover_image" :value="removeImage ? '1' : '0'">
                            </div>
                        </div>
                    </div>
                    
                    {{-- Gallery --}}
                    <div>
                        <label class="form-label">Gallery Images</label>
                        <p class="text-sm text-charcoal-500 mb-4">Add multiple photos to showcase in your invitation gallery.</p>
                        
                        @if($invitation->gallery && count($invitation->gallery) > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                                @foreach($invitation->gallery as $image)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $image) }}" class="w-full h-24 object-cover rounded-lg">
                                        <label class="absolute inset-0 bg-red-500/80 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer rounded-lg">
                                            <input type="checkbox" name="remove_gallery_images[]" value="{{ $image }}" class="sr-only">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <input 
                            type="file" 
                            name="gallery[]" 
                            multiple
                            accept="image/png,image/jpeg,image/webp"
                            class="form-input"
                        >
                    </div>
                </div>

                {{-- SEO Tab --}}
                <div x-show="activeTab === 'seo'" x-cloak class="p-6 space-y-6">
                    <x-premium.form-input 
                        name="seo_title" 
                        label="SEO Title"
                        :value="$invitation->seo_title"
                        placeholder="Leave empty for auto-generated title"
                    />
                    
                    <x-premium.form-textarea 
                        name="seo_description" 
                        label="SEO Description"
                        :value="$invitation->seo_description"
                        placeholder="Leave empty for auto-generated description"
                        :rows="3"
                    />
                    
                    <div>
                        <label class="form-label">SEO Image</label>
                        <input 
                            type="file" 
                            name="seo_image" 
                            accept="image/png,image/jpeg,image/webp"
                            class="form-input mt-2"
                        >
                        @if($invitation->seo_image)
                            <p class="text-sm text-charcoal-500 mt-2">Current: {{ $invitation->seo_image }}</p>
                        @endif
                    </div>
                </div>

                {{-- Settings Tab --}}
                <div x-show="activeTab === 'settings'" x-cloak class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Template Selection --}}
                        <div>
                            <h4 class="font-semibold text-charcoal-800 mb-4">Template</h4>
                            <div class="space-y-3">
                                @foreach($templates as $template)
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-ivory-200 cursor-pointer hover:border-gold-300 has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50 transition-colors">
                                        <input 
                                            type="radio" 
                                            name="template_id" 
                                            value="{{ $template->id }}"
                                            class="w-4 h-4 text-gold-500 border-charcoal-300 focus:ring-gold-500"
                                            {{ $invitation->template_id == $template->id ? 'checked' : '' }}
                                        >
                                        <div class="flex-1">
                                            <p class="font-medium text-charcoal-800">{{ $template->name }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        
                        {{-- Feature Toggles --}}
                        <div>
                            <h4 class="font-semibold text-charcoal-800 mb-4">Features</h4>
                            <div class="space-y-4">
                                <label class="flex items-center justify-between p-3 rounded-xl bg-ivory-50">
                                    <span class="text-sm text-charcoal-700">Enable RSVP</span>
                                    <input 
                                        type="checkbox" 
                                        name="settings[rsvp_enabled]" 
                                        value="1"
                                        {{ $invitation->rsvp_enabled ? 'checked' : '' }}
                                        class="w-5 h-5 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                                    >
                                </label>
                                
                                <label class="flex items-center justify-between p-3 rounded-xl bg-ivory-50">
                                    <span class="text-sm text-charcoal-700">Enable Gift Section</span>
                                    <input 
                                        type="checkbox" 
                                        name="settings[gift_enabled]" 
                                        value="1"
                                        {{ $invitation->gift_enabled ? 'checked' : '' }}
                                        class="w-5 h-5 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                                    >
                                </label>
                                
                                <label class="flex items-center justify-between p-3 rounded-xl bg-ivory-50">
                                    <span class="text-sm text-charcoal-700">Show Countdown</span>
                                    <input 
                                        type="checkbox" 
                                        name="settings[countdown_enabled]" 
                                        value="1"
                                        {{ $invitation->countdown_enabled ? 'checked' : '' }}
                                        class="w-5 h-5 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                                    >
                                </label>
                                
                                <label class="flex items-center justify-between p-3 rounded-xl bg-ivory-50">
                                    <span class="text-sm text-charcoal-700">Enable Guest Book</span>
                                    <input 
                                        type="checkbox" 
                                        name="settings[guest_book_enabled]" 
                                        value="1"
                                        {{ $invitation->guest_book_enabled ? 'checked' : '' }}
                                        class="w-5 h-5 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                                    >
                                </label>
                                
                                <label class="flex items-center justify-between p-3 rounded-xl bg-ivory-50">
                                    <span class="text-sm text-charcoal-700">Music Autoplay</span>
                                    <input 
                                        type="checkbox" 
                                        name="settings[music_autoplay]" 
                                        value="1"
                                        {{ $invitation->music_autoplay ? 'checked' : '' }}
                                        class="w-5 h-5 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                                    >
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 card">
                <div class="flex items-center gap-3">
                    @if($invitation->status->value === 'draft')
                        <span class="text-sm text-charcoal-500">Status:</span>
                        <x-premium.badge variant="draft" :dot="true">Draft</x-premium.badge>
                    @elseif($invitation->status->value === 'published')
                        <span class="text-sm text-charcoal-500">Status:</span>
                        <x-premium.badge variant="published" :dot="true">Published</x-premium.badge>
                    @endif
                </div>
                
                <div class="flex items-center gap-3">
                    <x-premium.button href="{{ route('invitations.index') }}" variant="ghost">
                        Cancel
                    </x-premium.button>
                    <x-premium.button type="submit" variant="primary">
                        Save Changes
                    </x-premium.button>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
