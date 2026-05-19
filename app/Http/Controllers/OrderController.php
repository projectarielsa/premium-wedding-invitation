<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Package;
use App\Models\PaymentSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display user's orders.
     */
    public function index(Request $request): View
    {
        $orders = Order::forUser($request->user())
            ->with('package')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show checkout page for a package.
     */
    public function checkout(Package $package): View
    {
        if (!$package->is_active) {
            abort(404, 'Package is not available.');
        }

        $user = auth()->user();

        // Check if user already has a pending order for this package
        $existingOrder = Order::forUser($user)
            ->where('package_id', $package->id)
            ->active()
            ->first();

        if ($existingOrder) {
            return redirect()
                ->route('orders.show', $existingOrder)
                ->with('info', 'You already have a pending order for this package.');
        }

        return view('orders.checkout', compact('package', 'user'));
    }

    /**
     * Store a new order.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_whatsapp' => 'nullable|string|max:20',
            'wedding_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        $package = Package::findOrFail($validated['package_id']);

        if (!$package->is_active) {
            return back()->with('error', 'Package is not available.');
        }

        $user = $request->user();

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_whatsapp' => $validated['customer_whatsapp'] ?? null,
            'wedding_date' => $validated['wedding_date'] ?? null,
            'package_price' => $package->price,
            'discount_amount' => $package->original_price 
                ? ($package->original_price - $package->price) 
                : 0,
            'total_price' => $package->price,
            'currency' => $package->currency,
            'status' => OrderStatus::WaitingPayment,
            'payment_status' => PaymentStatus::Unpaid,
            'notes' => $validated['notes'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Log activity
        $order->logActivity('created', null, 'waiting_payment', 'Order created', $user);

        return redirect()
            ->route('orders.payment', $order)
            ->with('success', 'Order created! Please complete your payment.');
    }

    /**
     * Display order details.
     */
    public function show(Order $order): View
    {
        // Ensure user owns this order
        $this->authorize('view', $order);

        $order->load(['package', 'activities']);

        return view('orders.show', compact('order'));
    }

    /**
     * Show payment page.
     */
    public function payment(Order $order): View
    {
        $this->authorize('view', $order);

        if ($order->isFinal()) {
            return redirect()->route('orders.show', $order);
        }

        $paymentMethods = PaymentSetting::active()
            ->ordered()
            ->get()
            ->groupBy('type');

        return view('orders.payment', compact('order', 'paymentMethods'));
    }

    /**
     * Upload payment proof.
     */
    public function uploadPaymentProof(Request $request, Order $order): RedirectResponse
    {
        $this->authorize('update', $order);

        if (!$order->canUploadPaymentProof()) {
            return back()->with('error', 'Cannot upload payment proof for this order.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:5120', // 5MB max
            'payment_method' => 'nullable|string|max:100',
            'payment_notes' => 'nullable|string|max:500',
        ]);

        // Delete old payment proof if exists
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        // Store new payment proof
        $path = $request->file('payment_proof')->store('payment-proofs/' . $order->order_number, 'public');

        // Update order
        $order->markAsPaid(
            $path,
            $request->payment_method,
            $request->payment_notes
        );

        // Log activity
        $order->logActivity(
            'payment_uploaded',
            null,
            'paid',
            'Payment proof uploaded',
            $request->user()
        );

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Payment proof uploaded! We will verify your payment shortly.');
    }

    /**
     * Cancel an order.
     */
    public function cancel(Request $request, Order $order): RedirectResponse
    {
        $this->authorize('update', $order);

        if (!$order->status->canBeCancelled()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $request->validate([
            'cancel_reason' => 'nullable|string|max:500',
        ]);

        $order->cancel($request->cancel_reason);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order has been cancelled.');
    }

    /**
     * Download invoice/receipt.
     */
    public function invoice(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load('package');

        return view('orders.invoice', compact('order'));
    }
}
