<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'package']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total' => Order::count(),
            'pending_review' => Order::where('status', OrderStatus::Paid)->count(),
            'approved' => Order::where('status', OrderStatus::Approved)->count(),
            'completed' => Order::where('status', OrderStatus::Completed)->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display pending orders for review.
     */
    public function pending(): View
    {
        $orders = Order::with(['user', 'package'])
            ->where('status', OrderStatus::Paid)
            ->latest()
            ->paginate(20);

        return view('admin.orders.pending', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'package', 'activities.user', 'approver', 'rejecter']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Approve an order.
     */
    public function approve(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($order->status !== OrderStatus::Paid) {
            return back()->with('error', 'Order cannot be approved. Current status: ' . $order->status->label());
        }

        $order->approve($request->user(), $request->admin_notes);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order approved successfully. Package has been activated for the customer.');
    }

    /**
     * Reject an order.
     */
    public function reject(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        if ($order->status !== OrderStatus::Paid) {
            return back()->with('error', 'Order cannot be rejected. Current status: ' . $order->status->label());
        }

        $order->reject($request->user(), $request->rejection_reason);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order rejected successfully.');
    }

    /**
     * Mark order as completed.
     */
    public function complete(Order $order): RedirectResponse
    {
        if ($order->status !== OrderStatus::Approved) {
            return back()->with('error', 'Order cannot be completed. Current status: ' . $order->status->label());
        }

        $order->complete();

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order marked as completed.');
    }

    /**
     * Update admin notes.
     */
    public function updateNotes(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $order->update(['admin_notes' => $request->admin_notes]);
        $order->logActivity('notes_updated', null, null, 'Admin notes updated', $request->user());

        return back()->with('success', 'Admin notes updated.');
    }

    /**
     * View payment proof image.
     */
    public function viewPaymentProof(Order $order): View
    {
        if (!$order->payment_proof) {
            abort(404, 'Payment proof not found.');
        }

        return view('admin.orders.payment-proof', compact('order'));
    }

    /**
     * Export orders to CSV.
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'package']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->get();

        $filename = 'orders_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Email',
                'WhatsApp',
                'Package',
                'Total Price',
                'Status',
                'Payment Status',
                'Created At',
                'Approved At',
                'Wedding Date',
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_whatsapp,
                    $order->package->name,
                    $order->total_price,
                    $order->status->englishLabel(),
                    $order->payment_status->label(),
                    $order->created_at->format('Y-m-d H:i'),
                    $order->approved_at?->format('Y-m-d H:i'),
                    $order->wedding_date?->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
