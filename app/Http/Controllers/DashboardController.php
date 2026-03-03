<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Bill;
use App\Models\WaterUsage;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalCustomers = Customer::count();
        $totalRevenue = Bill::where('status', 'Paid')->sum('total_amount');
        $pendingRevenue = Bill::where('status', 'Pending')->sum('total_amount');
        $totalConsumption = WaterUsage::sum('usage');

        // Get monthly revenue data for chart
        $monthlyRevenue = Bill::where('status', 'Paid')
            ->selectRaw("strftime('%Y-%m', billing_date) as month, SUM(total_amount) as total")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Get usage trend data
        $usageTrend = WaterUsage::selectRaw("strftime('%Y-%m', reading_date) as month, SUM(usage) as total_usage")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Customer type distribution
        $customerTypes = Customer::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();

        // Revenue by customer type
        $revenueByType = Bill::where('bills.status', 'Paid')
            ->join('customers', 'bills.customer_id', '=', 'customers.id')
            ->selectRaw('customers.type, SUM(bills.total_amount) as total')
            ->groupBy('customers.type')
            ->get();

        return view('dashboard.index', [
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'pendingRevenue' => $pendingRevenue,
            'totalConsumption' => $totalConsumption,
            'monthlyRevenue' => $monthlyRevenue,
            'usageTrend' => $usageTrend,
            'customerTypes' => $customerTypes,
            'revenueByType' => $revenueByType,
        ]);
    }
}
