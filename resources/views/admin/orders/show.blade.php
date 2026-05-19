<x-admin-layout>
    <x-slot name="header">Order {{ $order->order_number }}</x-slot>
    <x-slot name="title">Order Details</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Info -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Order Information</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->status->badgeClasses() }}">
                        {{ $order->status->label() }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Order Number</p>
                        <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Created At</p>
                        <p class="font-medium text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Customer Name</p>
                        <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">WhatsApp</p>
                        <p class="font-medium text-gray-900">{{ $order->customer_whatsapp ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Wedding Date</p>
                        <p class="font-medium text-gray-900">{{ $order->wedding_date?->format('d M Y') ?? '-' }}</p>
                    </div>
                </div>

                @if($order->notes)
                    <div class="mt-6 pt-6 border-t">
                        <p class="text-sm text-gray-500 mb-2">Customer Notes</p>
                        <p class="text-gray-900">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Package & Pricing -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Package & Pricing</h2>

                <div class="flex items-center justify-between py-4 border-b">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($order->package->name, 0, 1)) }}
                        </div>
                        <div class="ml-4">
                            <p class="font-medium text-gray-900">{{ $order->package->name }}</p>
                            <p class="text-sm text-gray-500">{{ $order->package->duration_label }}</p>
                        </div>
                    </div>
                    <p class="font-medium text-gray-900">{{ $order->formatted_package_price }}</p>
                </div>

                @if($order->discount_amount > 0)
                    <div class="flex items-center justify-between py-3">
                        <p class="text-gray-500">Discount</p>
                        <p class="text-green-600">- {{ $order->formatted_discount }}</p>
                    </div>
                @endif

                <div class="flex items-center justify-between py-4 border-t mt-2">
                    <p class="font-semibold text-gray-900">Total</p>
                    <p class="text-xl font-bold text-gray-900">{{ $order->formatted_total_price }}</p>
                </div>
            </div>

            <!-- Payment Proof -->
            @if($order->payment_proof)
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Payment Proof</h2>
                    
                    <div class="border rounded-lg overflow-hidden">
                        <img src="{{ $order->payment_proof_url }}" alt="Payment Proof" class="w-full max-h-96 object-contain">
                    </div>
                    
                    <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                        <span>Uploaded: {{ $order->payment_uploaded_at?->format('d M Y H:i') }}</span>
                        <a href="{{ $order->payment_proof_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            View Full Size
                        </a>
                    </div>

                    @if($order->payment_notes)
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Payment Notes</p>
                            <p class="text-gray-900">{{ $order->payment_notes }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Admin Actions -->
            @if($order->status === \App\Enums\OrderStatus::Paid)
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Admin Actions</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Approve Form -->
                        <form action="{{ route('admin.orders.approve', $order) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notes (Optional)</label>
                                <textarea name="admin_notes" rows="3" 
                                          class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                                          placeholder="Add notes for this approval..."></textarea>
                            </div>
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to approve this order? This will activate the package for the customer.')"
                                    class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                Approve Order
                            </button>
                        </form>

                        <!-- Reject Form -->
                        <form action="{{ route('admin.orders.reject', $order) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason *</label>
                                <textarea name="rejection_reason" rows="3" required
                                          class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"
                                          placeholder="Explain why this order is being rejected..."></textarea>
                            </div>
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to reject this order?')"
                                    class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                                Reject Order
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Activity Log -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Activity Log</h2>
                
                <div class="space-y-4">
                    @forelse($order->activities as $activity)
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $activity->action_label }}</p>
                                @if($activity->description)
                                    <p class="text-sm text-gray-500">{{ $activity->description }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $activity->performer_name }} - {{ $activity->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No activity logged yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Order Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status->badgeClasses() }}">
                            {{ $order->status->label() }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Payment Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->payment_status->badgeClasses() }}">
                            {{ $order->payment_status->label() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Customer Card -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Customer</h3>
                <div class="flex items-center">
                    <img src="{{ $order->user->getAvatarUrl() }}" alt="{{ $order->user->name }}" class="w-12 h-12 rounded-full">
                    <div class="ml-3">
                        <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.users.show', $order->user) }}" class="text-sm text-blue-600 hover:text-blue-800">
                        View Customer Profile &rarr;
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.orders.index') }}" class="block w-full px-4 py-2 text-center text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        Back to Orders
                    </a>
                    @if($order->status === \App\Enums\OrderStatus::Approved)
                        <form action="{{ route('admin.orders.complete', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                                Mark as Completed
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Timestamps -->
            @if($order->approved_at || $order->rejected_at)
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Timestamps</h3>
                    <div class="space-y-3 text-sm">
                        @if($order->approved_at)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Approved At</span>
                                <span class="text-gray-900">{{ $order->approved_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Approved By</span>
                                <span class="text-gray-900">{{ $order->approver?->name ?? 'N/A' }}</span>
                            </div>
                        @endif
                        @if($order->rejected_at)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Rejected At</span>
                                <span class="text-gray-900">{{ $order->rejected_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Rejected By</span>
                                <span class="text-gray-900">{{ $order->rejecter?->name ?? 'N/A' }}</span>
                            </div>
                            @if($order->rejection_reason)
                                <div class="mt-3 p-3 bg-red-50 rounded-lg">
                                    <p class="text-xs text-red-600 mb-1">Rejection Reason</p>
                                    <p class="text-sm text-red-800">{{ $order->rejection_reason }}</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
