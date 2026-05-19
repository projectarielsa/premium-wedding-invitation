<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminPaymentSettingsController extends Controller
{
    /**
     * Display a listing of payment settings.
     */
    public function index(): View
    {
        $paymentSettings = PaymentSetting::ordered()->get()->groupBy('type');

        return view('admin.payment-settings.index', compact('paymentSettings'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create(): View
    {
        return view('admin.payment-settings.create');
    }

    /**
     * Store a newly created payment method.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:bank_transfer,e_wallet,qris',
            'name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:1024',
            'qr_code_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'instructions' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('payment-logos', 'public');
        }

        // Handle QR code image upload
        if ($request->hasFile('qr_code_image')) {
            $validated['qr_code_image'] = $request->file('qr_code_image')->store('payment-qr', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        PaymentSetting::create($validated);

        return redirect()
            ->route('admin.payment-settings.index')
            ->with('success', 'Payment method created successfully.');
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentSetting $paymentSetting): View
    {
        return view('admin.payment-settings.edit', compact('paymentSetting'));
    }

    /**
     * Update the specified payment method.
     */
    public function update(Request $request, PaymentSetting $paymentSetting): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:bank_transfer,e_wallet,qris',
            'name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:1024',
            'qr_code_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'instructions' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($paymentSetting->logo) {
                Storage::disk('public')->delete($paymentSetting->logo);
            }
            $validated['logo'] = $request->file('logo')->store('payment-logos', 'public');
        }

        // Handle QR code image upload
        if ($request->hasFile('qr_code_image')) {
            // Delete old QR code
            if ($paymentSetting->qr_code_image) {
                Storage::disk('public')->delete($paymentSetting->qr_code_image);
            }
            $validated['qr_code_image'] = $request->file('qr_code_image')->store('payment-qr', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $paymentSetting->update($validated);

        return redirect()
            ->route('admin.payment-settings.index')
            ->with('success', 'Payment method updated successfully.');
    }

    /**
     * Remove the specified payment method.
     */
    public function destroy(PaymentSetting $paymentSetting): RedirectResponse
    {
        // Delete associated files
        if ($paymentSetting->logo) {
            Storage::disk('public')->delete($paymentSetting->logo);
        }
        if ($paymentSetting->qr_code_image) {
            Storage::disk('public')->delete($paymentSetting->qr_code_image);
        }

        $paymentSetting->delete();

        return redirect()
            ->route('admin.payment-settings.index')
            ->with('success', 'Payment method deleted successfully.');
    }

    /**
     * Toggle payment method active status.
     */
    public function toggleActive(PaymentSetting $paymentSetting): RedirectResponse
    {
        $paymentSetting->update(['is_active' => !$paymentSetting->is_active]);

        $status = $paymentSetting->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Payment method {$status} successfully.");
    }

    /**
     * Reorder payment methods.
     */
    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:payment_settings,id',
        ]);

        foreach ($request->order as $index => $id) {
            PaymentSetting::where('id', $id)->update(['sort_order' => $index]);
        }

        return back()->with('success', 'Payment methods reordered successfully.');
    }
}
