<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPackageController;
use App\Http\Controllers\Admin\AdminPaymentSettingsController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GiftAccountController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RsvpController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// =========================================================================
// PUBLIC MARKETING ROUTES
// =========================================================================

// Homepage - Marketing Landing Page
Route::get('/', [MarketingController::class, 'index'])->name('home');

// Demo Page
Route::get('/demo', [MarketingController::class, 'demo'])->name('demo');

// Articles / Blog
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

// SEO Routes
Route::get('/robots.txt', [MarketingController::class, 'robots']);
Route::get('/sitemap.xml', [MarketingController::class, 'sitemap']);

// WhatsApp tracking (API)
Route::post('/api/track-whatsapp-click', [MarketingController::class, 'trackWhatsappClick']);

// Pricing Page (Public)
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/pricing/compare', [PricingController::class, 'compare'])->name('pricing.compare');
Route::get('/pricing/{package}', [PricingController::class, 'show'])->name('pricing.show');
Route::get('/api/packages', [PricingController::class, 'packagesJson'])->name('api.packages');

// Public invitation viewing
Route::prefix('invite')->name('invitation.')->group(function () {
    // RSVP routes (must be before /{slug}/{guestToken} to avoid capture)
    Route::get('/{slug}/rsvp', [RsvpController::class, 'show'])
        ->name('public.rsvp');
    Route::get('/{slug}/rsvp/{guestToken}', [RsvpController::class, 'show'])
        ->name('public.rsvp.guest');
    Route::post('/{invitation:slug}/rsvp', [RsvpController::class, 'submit'])
        ->name('public.rsvp.submit');
    Route::get('/{invitation:slug}/rsvp/{guestToken}/status', [RsvpController::class, 'status'])
        ->name('public.rsvp.status');
    
    // View invitation by slug
    Route::get('/{slug}', [InvitationController::class, 'publicShow'])
        ->name('public');
    
    // View invitation with guest personalization (must be last - catches /{slug}/{guestToken})
    Route::get('/{slug}/{guestToken}', [InvitationController::class, 'publicShowWithGuest'])
        ->name('public.guest');
});

// Public analytics tracking (called via JavaScript)
Route::post('/analytics/{slug}/track', [AnalyticsController::class, 'track'])
    ->name('analytics.track');

// Public gift account tracking
Route::post('/invite/{slug}/gift/{giftAccount}/copy', [GiftAccountController::class, 'trackCopy'])
    ->name('gift.track.copy');
Route::post('/invite/{slug}/gift/{giftAccount}/view', [GiftAccountController::class, 'trackView'])
    ->name('gift.track.view');

// Public check-in via QR code (can be accessed without auth for quick scanning)
Route::get('/checkin/{token}', [CheckInController::class, 'process'])
    ->name('checkin.process');



// =========================================================================
// AUTHENTICATED ROUTES (OTP VERIFIED)
// =========================================================================

Route::middleware(['auth', 'otp.verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // =========================================================================
    // INVITATIONS
    // =========================================================================
    
    Route::prefix('invitations')->name('invitations.')->group(function () {
        // List & Create
        Route::get('/', [InvitationController::class, 'index'])->name('index');
        Route::get('/create', [InvitationController::class, 'create'])->name('create');
        Route::post('/', [InvitationController::class, 'store'])->name('store');
        
        // Single invitation routes (using slug binding)
        Route::prefix('{invitation}')->group(function () {
            Route::get('/', [InvitationController::class, 'show'])->name('show');
            Route::get('/edit', [InvitationController::class, 'edit'])->name('edit');
            Route::put('/', [InvitationController::class, 'update'])->name('update');
            Route::delete('/', [InvitationController::class, 'destroy'])->name('destroy');
            
            // Special actions
            Route::post('/duplicate', [InvitationController::class, 'duplicate'])->name('duplicate');
            Route::post('/publish', [InvitationController::class, 'publish'])->name('publish');
            Route::post('/unpublish', [InvitationController::class, 'unpublish'])->name('unpublish');
            Route::get('/preview', [InvitationController::class, 'preview'])->name('preview');
            
            // =========================================================================
            // EVENTS (nested under invitation)
            // =========================================================================
            
            Route::prefix('events')->name('events.')->group(function () {
                Route::post('/', [EventController::class, 'store'])->name('store');
                Route::put('/{event}', [EventController::class, 'update'])->name('update');
                Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
                Route::post('/reorder', [EventController::class, 'reorder'])->name('reorder');
                Route::post('/{event}/toggle-active', [EventController::class, 'toggleActive'])->name('toggle-active');
            });
            
            // =========================================================================
            // GUESTS (nested under invitation)
            // =========================================================================
            
            Route::prefix('guests')->name('guests.')->group(function () {
                Route::get('/', [GuestController::class, 'index'])->name('index');
                Route::post('/', [GuestController::class, 'store'])->name('store');
                Route::put('/{guest}', [GuestController::class, 'update'])->name('update');
                Route::delete('/{guest}', [GuestController::class, 'destroy'])->name('destroy');
                
                // Import & Export
                Route::post('/import', [GuestController::class, 'import'])->name('import');
                Route::get('/export', [GuestController::class, 'export'])->name('export');
                
                // WhatsApp link generation
                Route::get('/{guest}/whatsapp', [GuestController::class, 'whatsappLink'])->name('whatsapp');
                
                // Bulk actions
                Route::post('/bulk-delete', [GuestController::class, 'bulkDelete'])->name('bulk-delete');
                Route::post('/bulk-category', [GuestController::class, 'bulkUpdateCategory'])->name('bulk-category');
                Route::post('/bulk-whatsapp-sent', [GuestController::class, 'bulkMarkWhatsappSent'])->name('bulk-whatsapp-sent');
            });


            
            // =========================================================================
            // GIFT ACCOUNTS (nested under invitation)
            // =========================================================================
            
            Route::prefix('gift-accounts')->name('gift-accounts.')->group(function () {
                Route::post('/', [GiftAccountController::class, 'store'])->name('store');
                Route::put('/{giftAccount}', [GiftAccountController::class, 'update'])->name('update');
                Route::delete('/{giftAccount}', [GiftAccountController::class, 'destroy'])->name('destroy');
                Route::post('/reorder', [GiftAccountController::class, 'reorder'])->name('reorder');
                Route::post('/{giftAccount}/toggle-active', [GiftAccountController::class, 'toggleActive'])->name('toggle-active');
            });
            
            // =========================================================================
            // RSVPS (admin management, nested under invitation)
            // =========================================================================
            
            Route::prefix('rsvps')->name('rsvps.')->group(function () {
                Route::get('/', [RsvpController::class, 'index'])->name('index');
                Route::patch('/{rsvp}/notes', [RsvpController::class, 'updateNotes'])->name('update-notes');
            });
            
            // =========================================================================
            // ANALYTICS (nested under invitation)
            // =========================================================================
            
            Route::prefix('analytics')->name('analytics.')->group(function () {
                Route::get('/', [AnalyticsController::class, 'index'])->name('index');
                Route::get('/rsvp-stats', [AnalyticsController::class, 'rsvpStats'])->name('rsvp-stats');
                Route::get('/visitor-stats', [AnalyticsController::class, 'visitorStats'])->name('visitor-stats');
                Route::get('/chart-data', [AnalyticsController::class, 'chartData'])->name('chart-data');
            });
            
            // =========================================================================
            // CHECK-IN (nested under invitation)
            // =========================================================================
            
            Route::prefix('checkin')->name('checkin.')->group(function () {
                Route::get('/', [CheckInController::class, 'index'])->name('index');
                Route::get('/stats', [CheckInController::class, 'stats'])->name('stats');
                Route::post('/{guest}/check-in', [CheckInController::class, 'checkIn'])->name('check-in');
                Route::post('/{guest}/undo', [CheckInController::class, 'undoCheckIn'])->name('undo');
                Route::post('/bulk', [CheckInController::class, 'bulkCheckIn'])->name('bulk');
                
                // QR Code generation
                Route::get('/{guest}/qr', [CheckInController::class, 'getQrDataUri'])->name('qr');
                Route::get('/{guest}/qr/download', [CheckInController::class, 'downloadQr'])->name('qr.download');
                Route::post('/{guest}/qr/generate', [CheckInController::class, 'generateQr'])->name('qr.generate');
                Route::post('/qr/bulk-generate', [CheckInController::class, 'bulkGenerateQr'])->name('qr.bulk-generate');
            });
            
            // =========================================================================
            // EXPORTS (nested under invitation)
            // =========================================================================
            
            Route::prefix('export')->name('export.')->group(function () {
                Route::get('/guests', [ExportController::class, 'guests'])->name('guests');
                Route::get('/rsvps', [ExportController::class, 'rsvps'])->name('rsvps');
                Route::get('/analytics', [ExportController::class, 'analytics'])->name('analytics');
                Route::get('/summary', [ExportController::class, 'summary'])->name('summary');
            });
        });
        
        // Restore soft-deleted invitation (uses ID since slug might conflict)
        Route::post('/{id}/restore', [InvitationController::class, 'restore'])
            ->name('restore')
            ->where('id', '[0-9]+');
    });
});

// =========================================================================
// AUTH ROUTES
// =========================================================================

require __DIR__.'/auth.php';

// =========================================================================
// ORDERS & BILLING (Authenticated)
// =========================================================================

Route::middleware(['auth', 'otp.verified', 'not.suspended'])->group(function () {
    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/checkout/{package}', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/payment', [OrderController::class, 'payment'])->name('payment');
        Route::post('/{order}/payment', [OrderController::class, 'uploadPaymentProof'])->name('upload-payment');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::get('/{order}/invoice', [OrderController::class, 'invoice'])->name('invoice');
    });
});

// =========================================================================
// ADMIN ROUTES
// =========================================================================

Route::middleware(['auth', 'otp.verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/stats', [AdminDashboardController::class, 'stats'])->name('stats');
    
    // Package Management
    Route::resource('packages', AdminPackageController::class);
    Route::post('/packages/{package}/toggle-active', [AdminPackageController::class, 'toggleActive'])
        ->name('packages.toggle-active');
    
    // Order Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/pending', [AdminOrderController::class, 'pending'])->name('orders.pending');
    Route::get('/orders/export', [AdminOrderController::class, 'export'])->name('orders.export');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/approve', [AdminOrderController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/{order}/reject', [AdminOrderController::class, 'reject'])->name('orders.reject');
    Route::post('/orders/{order}/complete', [AdminOrderController::class, 'complete'])->name('orders.complete');
    Route::patch('/orders/{order}/notes', [AdminOrderController::class, 'updateNotes'])->name('orders.update-notes');
    Route::get('/orders/{order}/payment-proof', [AdminOrderController::class, 'viewPaymentProof'])
        ->name('orders.payment-proof');
    
    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/export', [AdminUserController::class, 'export'])->name('users.export');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/unsuspend', [AdminUserController::class, 'unsuspend'])->name('users.unsuspend');
    Route::post('/users/{user}/assign-package', [AdminUserController::class, 'assignPackage'])
        ->name('users.assign-package');
    Route::post('/users/{user}/remove-package', [AdminUserController::class, 'removePackage'])
        ->name('users.remove-package');
    Route::post('/users/{user}/extend-package', [AdminUserController::class, 'extendPackage'])
        ->name('users.extend-package');
    
    // Payment Settings
    Route::resource('payment-settings', AdminPaymentSettingsController::class)
        ->except(['show']);
    Route::post('/payment-settings/{paymentSetting}/toggle-active', [AdminPaymentSettingsController::class, 'toggleActive'])
        ->name('payment-settings.toggle-active');
    Route::post('/payment-settings/reorder', [AdminPaymentSettingsController::class, 'reorder'])
        ->name('payment-settings.reorder');
});
