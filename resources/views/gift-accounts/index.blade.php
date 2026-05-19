<x-app-layout>
    {{-- Page Header --}}
    <x-premium.page-header 
        title="Gift Accounts"
        description="Manage digital gift accounts for your wedding invitation."
    >
        <x-slot:actions>
            <x-premium.button 
                x-data
                x-on:click="$dispatch('open-modal', 'add-gift-account')"
                variant="primary" 
                icon="plus"
            >
                Add Account
            </x-premium.button>
        </x-slot:actions>
    </x-premium.page-header>

    {{-- Account Type Tabs --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        {{-- Bank Transfer --}}
        <div class="card card-body">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-charcoal-800">Bank Transfer</h3>
                    <p class="text-sm text-charcoal-500">Traditional bank accounts</p>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-charcoal-800">
                {{ $giftAccounts->where('account_type', \App\Enums\GiftAccountType::BankTransfer)->count() }}
            </p>
        </div>

        {{-- E-Wallet --}}
        <div class="card card-body">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-charcoal-800">E-Wallet</h3>
                    <p class="text-sm text-charcoal-500">Digital wallet apps</p>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-charcoal-800">
                {{ $giftAccounts->where('account_type', \App\Enums\GiftAccountType::EWallet)->count() }}
            </p>
        </div>

        {{-- QRIS --}}
        <div class="card card-body">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-charcoal-800">QRIS</h3>
                    <p class="text-sm text-charcoal-500">QR code payments</p>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-charcoal-800">
                {{ $giftAccounts->where('account_type', \App\Enums\GiftAccountType::Qris)->count() }}
            </p>
        </div>
    </div>

    {{-- Gift Accounts List --}}
    @if($giftAccounts->count() > 0)
        <div class="space-y-4">
            @foreach($giftAccounts as $account)
                <x-premium.card class="overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        {{-- Account Icon/Logo --}}
                        <div class="flex-shrink-0">
                            @if($account->provider_logo_url)
                                <img src="{{ $account->provider_logo_url }}" alt="{{ $account->provider_name }}" class="w-14 h-14 rounded-xl object-contain bg-ivory-100 p-2">
                            @else
                                <div class="w-14 h-14 rounded-xl flex items-center justify-center
                                    @if($account->account_type === \App\Enums\GiftAccountType::BankTransfer) bg-blue-100 text-blue-600
                                    @elseif($account->account_type === \App\Enums\GiftAccountType::EWallet) bg-emerald-100 text-emerald-600
                                    @else bg-purple-100 text-purple-600 @endif
                                ">
                                    @if($account->account_type === \App\Enums\GiftAccountType::BankTransfer)
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    @elseif($account->account_type === \App\Enums\GiftAccountType::EWallet)
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    @else
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Account Details --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-semibold text-charcoal-800">{{ $account->provider_name }}</h4>
                                <x-premium.badge 
                                    :variant="$account->is_active ? 'success' : 'neutral'"
                                    size="sm"
                                >
                                    {{ $account->is_active ? 'Active' : 'Inactive' }}
                                </x-premium.badge>
                                <x-premium.badge variant="info" size="sm">
                                    {{ $account->account_type->label() }}
                                </x-premium.badge>
                            </div>
                            
                            <p class="text-lg font-medium text-charcoal-700 mb-1">{{ $account->account_name }}</p>
                            
                            @if($account->account_number)
                                <div class="flex items-center gap-2">
                                    <code class="px-2 py-1 bg-ivory-100 rounded text-sm text-charcoal-700 font-mono">
                                        {{ $account->account_number }}
                                    </code>
                                    <button 
                                        type="button"
                                        onclick="navigator.clipboard.writeText('{{ $account->account_number }}'); alert('Account number copied!');"
                                        class="text-xs text-gold-600 hover:text-gold-700 font-medium"
                                    >
                                        Copy
                                    </button>
                                </div>
                            @endif

                            @if($account->qr_image_url)
                                <div class="mt-3">
                                    <img src="{{ $account->qr_image_url }}" alt="QRIS Code" class="w-24 h-24 rounded-lg border border-ivory-200">
                                </div>
                            @endif
                        </div>

                        {{-- Stats --}}
                        <div class="flex items-center gap-6 text-center">
                            <div>
                                <p class="text-xl font-display font-bold text-charcoal-800">{{ $account->view_count }}</p>
                                <p class="text-xs text-charcoal-500">Views</p>
                            </div>
                            <div>
                                <p class="text-xl font-display font-bold text-charcoal-800">{{ $account->copy_count }}</p>
                                <p class="text-xs text-charcoal-500">Copies</p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('invitations.gift-accounts.toggle-active', [$invitation ?? $account->invitation, $account]) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-ghost">
                                    {{ $account->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            
                            <x-premium.dropdown-action>
                                <x-premium.dropdown-item 
                                    x-data
                                    x-on:click="$dispatch('open-modal', 'edit-account-{{ $account->id }}')"
                                    icon="edit"
                                >
                                    Edit
                                </x-premium.dropdown-item>
                                <hr class="my-2 border-ivory-200">
                                <x-premium.dropdown-item 
                                    :href="route('invitations.gift-accounts.destroy', [$invitation ?? $account->invitation, $account])" 
                                    method="DELETE"
                                    icon="trash" 
                                    :danger="true"
                                    onclick="return confirm('Delete this gift account?')"
                                >
                                    Delete
                                </x-premium.dropdown-item>
                            </x-premium.dropdown-action>
                        </div>
                    </div>
                </x-premium.card>
            @endforeach
        </div>
    @else
        <x-premium.empty-state
            icon="gift"
            title="No gift accounts yet"
            description="Add bank accounts, e-wallets, or QRIS codes for your guests to send gifts."
        >
            <div class="flex items-center justify-center gap-3 mt-6">
                <x-premium.button 
                    x-data
                    x-on:click="$dispatch('open-modal', 'add-gift-account')"
                    variant="primary" 
                    icon="plus"
                >
                    Add Gift Account
                </x-premium.button>
            </div>
        </x-premium.empty-state>
    @endif

    {{-- Add Gift Account Modal --}}
    <x-premium.modal name="add-gift-account" title="Add Gift Account" maxWidth="lg">
        <form method="POST" action="{{ route('invitations.gift-accounts.store', $invitation ?? request()->route('invitation')) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <x-premium.form-select 
                name="account_type" 
                label="Account Type"
                :required="true"
                :options="\App\Enums\GiftAccountType::options()"
            />
            
            <x-premium.form-input 
                name="provider_name" 
                label="Provider / Bank Name"
                placeholder="e.g., BCA, GoPay, QRIS"
                :required="true"
            />
            
            <x-premium.form-input 
                name="account_name" 
                label="Account Holder Name"
                placeholder="Name on the account"
                :required="true"
            />
            
            <x-premium.form-input 
                name="account_number" 
                label="Account Number"
                placeholder="Account or phone number"
                hint="Required for Bank Transfer and E-Wallet"
            />
            
            <div>
                <label class="form-label">Provider Logo (Optional)</label>
                <input 
                    type="file" 
                    name="provider_logo" 
                    accept="image/png,image/jpeg,image/webp"
                    class="form-input mt-2"
                >
            </div>
            
            <div>
                <label class="form-label">QR Code Image (for QRIS)</label>
                <input 
                    type="file" 
                    name="qr_image" 
                    accept="image/png,image/jpeg,image/webp"
                    class="form-input mt-2"
                >
            </div>
            
            <label class="flex items-center gap-2">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    value="1"
                    checked
                    class="w-4 h-4 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                >
                <span class="text-sm text-charcoal-700">Active (visible to guests)</span>
            </label>
        
            <x-slot:footer>
                <x-premium.button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'add-gift-account')">
                    Cancel
                </x-premium.button>
                <x-premium.button type="submit" variant="primary">
                    Add Account
                </x-premium.button>
            </x-slot:footer>
        </form>
    </x-premium.modal>

    {{-- Edit Modals --}}
    @foreach($giftAccounts as $account)
        <x-premium.modal name="edit-account-{{ $account->id }}" title="Edit Gift Account" maxWidth="lg">
            <form method="POST" action="{{ route('invitations.gift-accounts.update', [$invitation ?? $account->invitation, $account]) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                
                <x-premium.form-select 
                    name="account_type" 
                    label="Account Type"
                    :value="$account->account_type->value"
                    :required="true"
                    :options="\App\Enums\GiftAccountType::options()"
                />
                
                <x-premium.form-input 
                    name="provider_name" 
                    label="Provider / Bank Name"
                    :value="$account->provider_name"
                    :required="true"
                />
                
                <x-premium.form-input 
                    name="account_name" 
                    label="Account Holder Name"
                    :value="$account->account_name"
                    :required="true"
                />
                
                <x-premium.form-input 
                    name="account_number" 
                    label="Account Number"
                    :value="$account->account_number"
                />
                
                <div>
                    <label class="form-label">Provider Logo</label>
                    @if($account->provider_logo_url)
                        <div class="mb-2">
                            <img src="{{ $account->provider_logo_url }}" alt="" class="w-12 h-12 rounded object-contain bg-ivory-100">
                        </div>
                    @endif
                    <input 
                        type="file" 
                        name="provider_logo" 
                        accept="image/png,image/jpeg,image/webp"
                        class="form-input"
                    >
                </div>
                
                <div>
                    <label class="form-label">QR Code Image</label>
                    @if($account->qr_image_url)
                        <div class="mb-2">
                            <img src="{{ $account->qr_image_url }}" alt="" class="w-20 h-20 rounded border border-ivory-200">
                        </div>
                    @endif
                    <input 
                        type="file" 
                        name="qr_image" 
                        accept="image/png,image/jpeg,image/webp"
                        class="form-input"
                    >
                </div>
                
                <label class="flex items-center gap-2">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1"
                        {{ $account->is_active ? 'checked' : '' }}
                        class="w-4 h-4 rounded text-gold-500 border-charcoal-300 focus:ring-gold-500"
                    >
                    <span class="text-sm text-charcoal-700">Active (visible to guests)</span>
                </label>
            
                <x-slot:footer>
                    <x-premium.button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'edit-account-{{ $account->id }}')">
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
