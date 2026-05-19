<x-admin-layout>
    <x-slot name="header">Orders</x-slot>
    <x-slot name="title">Orders</x-slot>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border p-4">
            <p class="text-sm text-gray-500">Total Orders</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border p-4">
            <p class="text-sm text-gray-500">Pending Review</p>
            <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_review']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border p-4">
            <p class="text-sm text-gray-500">Approved</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['approved']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border p-4">
            <p class="text-sm text-gray-500">Completed</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['completed']) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search order number, name, email..."
                       class="w-full rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500">
            </div>
            <div>
                <select name="status" class="rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500">
                    <option value="">All Status</option>
                    @foreach(\App\Enums\OrderStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500" 
                       placeholder="From Date">
            </div>
            <div>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="rounded-lg border-gray-300 focus:border-gold-500 focus:ring-gold-500"
                       placeholder="To Date">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">
                Filter
            </button>
            <a href="{{ route('admin.orders.export', request()->query()) }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Export CSV
            </a>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</p>
                                <p class="text-sm text-gray-500">{{ $order->customer_email }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $order->package->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $order->formatted_total_price }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status->badgeClasses() }}">
                                {{ $order->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</x-admin-layout>
