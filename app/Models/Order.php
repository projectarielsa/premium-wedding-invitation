<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Order model for package purchases.
 *
 * @property int $id
 * @property int $user_id
 * @property int $package_id
 * @property string $order_number
 * @property OrderStatus $status
 * @property string $customer_name
 * @property string $customer_email
 * @property string|null $customer_whatsapp
 * @property \Illuminate\Support\Carbon|null $wedding_date
 * @property float $package_price
 * @property float $discount_amount
 * @property float $total_price
 * @property string $currency
 * @property PaymentStatus $payment_status
 * @property string|null $payment_method
 * @property string|null $payment_proof
 * @property \Illuminate\Support\Carbon|null $payment_uploaded_at
 * @property string|null $payment_notes
 * @property string|null $notes
 * @property string|null $admin_notes
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $rejected_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $cancelled_at
 * @property int|null $approved_by
 * @property int|null $rejected_by
 * @property array|null $metadata
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user
 * @property-read Package $package
 * @property-read User|null $approver
 * @property-read User|null $rejecter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrderActivity> $activities
 */
class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'package_id',
        'order_number',
        'status',
        'customer_name',
        'customer_email',
        'customer_whatsapp',
        'wedding_date',
        'package_price',
        'discount_amount',
        'total_price',
        'currency',
        'payment_status',
        'payment_method',
        'payment_proof',
        'payment_uploaded_at',
        'payment_notes',
        'notes',
        'admin_notes',
        'rejection_reason',
        'approved_at',
        'rejected_at',
        'completed_at',
        'cancelled_at',
        'approved_by',
        'rejected_by',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'wedding_date' => 'date',
        'package_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'payment_uploaded_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'currency' => 'IDR',
        'discount_amount' => 0,
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Order $order): void {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));
        
        $number = "{$prefix}{$date}{$random}";
        
        // Ensure uniqueness
        while (self::where('order_number', $number)->exists()) {
            $random = strtoupper(Str::random(4));
            $number = "{$prefix}{$date}{$random}";
        }
        
        return $number;
    }

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the user who placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package for this order.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the admin who approved this order.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the admin who rejected this order.
     */
    public function rejecter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get order activities/history.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(OrderActivity::class)->orderByDesc('created_at');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope to filter by status.
     */
    public function scopeStatus(Builder $query, OrderStatus $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter pending orders.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::Pending);
    }

    /**
     * Scope to filter orders awaiting payment.
     */
    public function scopeAwaitingPayment(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::WaitingPayment);
    }

    /**
     * Scope to filter paid orders (awaiting admin review).
     */
    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::Paid);
    }

    /**
     * Scope to filter approved orders.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::Approved);
    }

    /**
     * Scope to filter completed orders.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::Completed);
    }

    /**
     * Scope to filter active orders (not final).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            OrderStatus::Completed,
            OrderStatus::Rejected,
            OrderStatus::Cancelled,
        ]);
    }

    /**
     * Scope to filter orders requiring admin action.
     */
    public function scopeNeedsAdminAction(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::Paid);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Scope to order by most recent.
     */
    public function scopeLatest(Builder $query, string $column = 'created_at'): Builder
    {
        return $query->orderByDesc($column);
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get formatted total price.
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Get formatted package price.
     */
    public function getFormattedPackagePriceAttribute(): string
    {
        return 'Rp ' . number_format($this->package_price, 0, ',', '.');
    }

    /**
     * Get formatted discount amount.
     */
    public function getFormattedDiscountAttribute(): string
    {
        return 'Rp ' . number_format($this->discount_amount, 0, ',', '.');
    }

    /**
     * Get payment proof URL.
     */
    public function getPaymentProofUrlAttribute(): ?string
    {
        if (!$this->payment_proof) {
            return null;
        }

        if (str_starts_with($this->payment_proof, 'http')) {
            return $this->payment_proof;
        }

        return asset('storage/' . $this->payment_proof);
    }

    /**
     * Check if order has payment proof.
     */
    public function getHasPaymentProofAttribute(): bool
    {
        return !empty($this->payment_proof);
    }

    /**
     * Get status badge HTML.
     */
    public function getStatusBadgeAttribute(): string
    {
        return sprintf(
            '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium %s">%s</span>',
            $this->status->badgeClasses(),
            $this->status->label()
        );
    }

    /**
     * Get payment status badge HTML.
     */
    public function getPaymentStatusBadgeAttribute(): string
    {
        return sprintf(
            '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium %s">%s</span>',
            $this->payment_status->badgeClasses(),
            $this->payment_status->label()
        );
    }

    // =========================================================================
    // STATUS TRANSITION METHODS
    // =========================================================================

    /**
     * Check if order can transition to given status.
     */
    public function canTransitionTo(OrderStatus $status): bool
    {
        return $this->status->canTransitionTo($status);
    }

    /**
     * Transition order to waiting payment status.
     */
    public function markAsWaitingPayment(): bool
    {
        if (!$this->canTransitionTo(OrderStatus::WaitingPayment)) {
            return false;
        }

        return $this->update(['status' => OrderStatus::WaitingPayment]);
    }

    /**
     * Mark order as paid (payment proof uploaded).
     */
    public function markAsPaid(string $paymentProof, ?string $paymentMethod = null, ?string $notes = null): bool
    {
        return $this->update([
            'status' => OrderStatus::Paid,
            'payment_status' => PaymentStatus::Pending,
            'payment_proof' => $paymentProof,
            'payment_method' => $paymentMethod,
            'payment_uploaded_at' => now(),
            'payment_notes' => $notes,
        ]);
    }

    /**
     * Approve the order.
     */
    public function approve(User $admin, ?string $adminNotes = null): bool
    {
        if (!$this->canTransitionTo(OrderStatus::Approved)) {
            return false;
        }

        $result = $this->update([
            'status' => OrderStatus::Approved,
            'payment_status' => PaymentStatus::Verified,
            'approved_at' => now(),
            'approved_by' => $admin->id,
            'admin_notes' => $adminNotes,
        ]);

        if ($result) {
            // Activate package for user
            $this->user->activatePackage($this->package);
            
            // Log activity
            $this->logActivity('approved', null, 'approved', 'Order approved by ' . $admin->name, $admin);
        }

        return $result;
    }

    /**
     * Reject the order.
     */
    public function reject(User $admin, string $reason): bool
    {
        if (!$this->canTransitionTo(OrderStatus::Rejected)) {
            return false;
        }

        $result = $this->update([
            'status' => OrderStatus::Rejected,
            'payment_status' => PaymentStatus::Failed,
            'rejected_at' => now(),
            'rejected_by' => $admin->id,
            'rejection_reason' => $reason,
        ]);

        if ($result) {
            $this->logActivity('rejected', null, 'rejected', $reason, $admin);
        }

        return $result;
    }

    /**
     * Cancel the order.
     */
    public function cancel(?string $reason = null): bool
    {
        if (!$this->status->canBeCancelled()) {
            return false;
        }

        $result = $this->update([
            'status' => OrderStatus::Cancelled,
            'cancelled_at' => now(),
            'notes' => $reason ?? $this->notes,
        ]);

        if ($result) {
            $this->logActivity('cancelled', null, 'cancelled', $reason);
        }

        return $result;
    }

    /**
     * Mark order as completed.
     */
    public function complete(): bool
    {
        if (!$this->canTransitionTo(OrderStatus::Completed)) {
            return false;
        }

        return $this->update([
            'status' => OrderStatus::Completed,
            'completed_at' => now(),
        ]);
    }

    // =========================================================================
    // ACTIVITY LOGGING
    // =========================================================================

    /**
     * Log an activity for this order.
     */
    public function logActivity(
        string $action,
        ?string $oldValue = null,
        ?string $newValue = null,
        ?string $description = null,
        ?User $user = null
    ): OrderActivity {
        return $this->activities()->create([
            'user_id' => $user?->id,
            'action' => $action,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Check if order is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === OrderStatus::Paid;
    }

    /**
     * Check if order is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === OrderStatus::Approved;
    }

    /**
     * Check if order is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::Completed;
    }

    /**
     * Check if order is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === OrderStatus::Rejected;
    }

    /**
     * Check if order is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === OrderStatus::Cancelled;
    }

    /**
     * Check if order is in final state.
     */
    public function isFinal(): bool
    {
        return $this->status->isFinal();
    }

    /**
     * Check if payment proof can be uploaded.
     */
    public function canUploadPaymentProof(): bool
    {
        return $this->status->canUploadPayment();
    }

    /**
     * Check if order belongs to user.
     */
    public function belongsToUser(User $user): bool
    {
        return $this->user_id === $user->id;
    }
}
