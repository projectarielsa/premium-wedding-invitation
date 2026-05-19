<x-admin-layout>
    <x-slot name="header">Packages</x-slot>
    <x-slot name="title">Packages</x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600">Manage pricing packages and their features.</p>
        <a href="{{ route('admin.packages.create') }}" class="px-4 py-2 bg-gold-600 text-white rounded-lg hover:bg-gold-700 transition">
            + Add Package
        </a>
    </div>

    <!-- Packages Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($packages as $package)
            <div class="bg-white rounded-xl shadow-sm border {{ $package->is_featured ? 'ring-2 ring-gold-400' : '' }} overflow-hidden">
                <!-- Header -->
                <div class="p-6 {{ $package->is_featured ? 'bg-gradient-to-r from-gold-400 to-gold-600 text-white' : 'bg-gray-50' }}">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold">{{ $package->name }}</h3>
                        @if($package->badge)
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $package->is_featured ? 'bg-white/20' : 'bg-gold-100 text-gold-800' }}">
                                {{ $package->badge }}
                            </span>
                        @endif
                    </div>
                    <div class="mt-4">
                        <span class="text-3xl font-bold">{{ $package->formatted_price }}</span>
                        @if($package->has_discount)
                            <span class="ml-2 text-sm line-through opacity-75">{{ $package->formatted_original_price }}</span>
                        @endif
                        <span class="text-sm opacity-75">/ {{ $package->duration_label }}</span>
                    </div>
                </div>

                <!-- Stats -->
                <div class="px-6 py-4 border-b flex items-center justify-between text-sm">
                    <span class="text-gray-500">Active Users</span>
                    <span class="font-medium text-gray-900">{{ $package->users_count }}</span>
                </div>

                <!-- Features Preview -->
                <div class="p-6">
                    <p class="text-sm text-gray-500 mb-3">Key Limits:</p>
                    <ul class="space-y-2 text-sm">
                        <li class="flex justify-between">
                            <span class="text-gray-600">Invitations</span>
                            <span class="font-medium">{{ $package->max_invitations_display }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">Guests/Invitation</span>
                            <span class="font-medium">{{ $package->max_guests_display }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">Export</span>
                            <span class="font-medium">{{ $package->export_enabled ? 'Yes' : 'No' }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">Analytics</span>
                            <span class="font-medium">{{ $package->analytics_enabled ? 'Yes' : 'No' }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="w-2 h-2 rounded-full {{ $package->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                        <span class="ml-2 text-sm {{ $package->is_active ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $package->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.packages.edit', $package) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Edit
                        </a>
                        <form action="{{ route('admin.packages.toggle-active', $package) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                {{ $package->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-admin-layout>
