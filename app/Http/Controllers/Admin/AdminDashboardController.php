<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Summary Statistics
        $stats = [
            'total_users' => User::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::whereIn('status', [OrderStatus::Paid])->count(),
            'total_revenue' => Order::whereIn('status', [OrderStatus::Approved, OrderStatus::Completed])
                ->sum('total_price'),
            'monthly_revenue' => Order::whereIn('status', [OrderStatus::Approved, OrderStatus::Completed])
                ->whereMonth('approved_at', now()->month)
                ->whereYear('approved_at', now()->year)
                ->sum('total_price'),
            'total_invitations' => Invitation::count(),
            'published_invitations' => Invitation::where('status', 'published')->count(),
            'active_packages' => User::whereNotNull('active_package_id')
                ->where(function ($q) {
                    $q->whereNull('package_expires_at')
                      ->orWhere('package_expires_at', '>', now());
                })
                ->count(),
        ];

        // Recent Orders requiring attention
        $pendingOrders = Order::with(['user', 'package'])
            ->where('status', OrderStatus::Paid)
            ->latest()
            ->take(5)
            ->get();

        // Recent Activity
        $recentOrders = Order::with(['user', 'package'])
            ->latest()
            ->take(10)
            ->get();

        // Package Distribution
        $packageDistribution = Package::withCount(['users' => function ($q) {
            $q->whereNotNull('active_package_id')
              ->where(function ($q) {
                  $q->whereNull('package_expires_at')
                    ->orWhere('package_expires_at', '>', now());
              });
        }])
            ->active()
            ->ordered()
            ->get();

        // Recent Users
        $recentUsers = User::where('role', 'customer')
            ->latest()
            ->take(5)
            ->get();

        // Revenue Chart Data (Last 6 months)
        $revenueChart = Order::whereIn('status', [OrderStatus::Approved, OrderStatus::Completed])
            ->where('approved_at', '>=', now()->subMonths(6)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(approved_at, '%Y-%m') as month"),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'pendingOrders',
            'recentOrders',
            'packageDistribution',
            'recentUsers',
            'revenueChart'
        ));
    }

    /**
     * Get dashboard stats as JSON (for AJAX refresh).
     */
    public function stats(Request $request)
    {
        $stats = [
            'pending_orders' => Order::where('status', OrderStatus::Paid)->count(),
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'monthly_revenue' => Order::whereIn('status', [OrderStatus::Approved, OrderStatus::Completed])
                ->whereMonth('approved_at', now()->month)
                ->whereYear('approved_at', now()->year)
                ->sum('total_price'),
        ];

        return response()->json($stats);
    }
}
