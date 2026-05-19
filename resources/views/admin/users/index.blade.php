<x-admin-layout>
    <x-slot name="header">Users</x-slot>
    <x-slot name="title">Users</x-slot>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border p-4">
            <p class="text-sm text-gray-500">Total Users</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border p-4">
            <p class="text-sm text-gray-500">Customers</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['customers']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border p-4">
            <p class="text-sm text-gray-500">Admins</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['admins']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border p-4">
            <p class="text-sm text-gray-500">With Package</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['with_package']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border p-4">
            <p class="text-sm text-gray-500">Suspended</p>
            <p class="text-2xl font-bold text-red-600">{{ number_format($stats['suspended']) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search name or email..."
                       class="w-full rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500">
            </div>
            <div>
                <select name="role" class="rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500">
                    <option value="">All Roles</option>
                    @foreach(\App\Enums\UserRole::cases() as $role)
                        <option value="{{ $role->value }}" {{ request('role') === $role->value ? 'selected' : '' }}>
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="package_id" class="rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500">
                    <option value="">All Packages</option>
                    <option value="none" {{ request('package_id') === 'none' ? 'selected' : '' }}>No Package</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>
                            {{ $package->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">
                Filter
            </button>
            <a href="{{ route('admin.users.export', request()->query()) }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Export CSV
            </a>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 {{ $user->is_suspended ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full">
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->role === \App\Enums\UserRole::SuperAdmin ? 'bg-red-100 text-red-800' : '' }}
                                {{ $user->role === \App\Enums\UserRole::Admin ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $user->role === \App\Enums\UserRole::Customer ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ $user->role->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->activePackage)
                                <span class="text-sm font-medium text-gray-900">{{ $user->activePackage->name }}</span>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($user->package_expires_at)
                                @if($user->package_expires_at->isPast())
                                    <span class="text-red-600">Expired</span>
                                @else
                                    {{ $user->package_expires_at->format('d M Y') }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_suspended)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Suspended
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-admin-layout>
