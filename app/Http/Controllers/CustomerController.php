<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::paginate(15);
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('status', 'active')->count();
        
        // Get customer growth data for the chart
        $customerGrowth = Customer::selectRaw("strftime('%Y-%m', created_at) as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return view('customers.index', compact('customers', 'totalCustomers', 'activeCustomers', 'customerGrowth'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:Regular,Commercial',
            'email' => 'required|email|unique:customers',
            'address' => 'required|string',
        ]);

        // Auto-generate customer ID if not provided, format as 1001, 1002 etc.
        $nextId = (Customer::max('id') ?? 1000) + 1;
        $validated['customer_id'] = sprintf('%d', $nextId);
        $validated['phone'] = $request->input('phone', 'N/A');

        $customer = Customer::create($validated);

        // automatically create initial bill
        // base charge depends on customer type
        $baseCharge = $customer->type === 'Commercial' ? 250 : 100;
        // initial usage 0, so usage charge = max(0 - 10,0) * rate = 0
        $rate = $customer->type === 'Commercial' ? 25 : 15;
        $usageCharge = 0;
        $totalAmount = $baseCharge + $usageCharge;

        
        
        \App\Models\Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => now(),
            'usage_units' => 0,
            'base_charge' => $baseCharge,
            'usage_charge' => $usageCharge,
            'total_amount' => $totalAmount,
            'status' => 'Pending',
            'due_date' => now()->addDays(30),
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully');
    }

    public function show(Customer $customer): View
    {
        $customer->load(['waterUsages', 'bills']);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:Regular,Commercial',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'address' => 'required|string',
        ]);
        
        $validated['phone'] = $request->input('phone', 'N/A');

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully');
    }

    public function createAccount(Customer $customer)
    {
        if ($customer->user) {
            return back()->with('error', 'Account already exists.');
        }

        $password = \Illuminate\Support\Str::random(8);

        \App\Models\User::create([
            'name' => $customer->name,
            'email' => $customer->email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'role' => 'consumer',
            'customer_id' => $customer->id,
            'email_verified_at' => now(),
        ]);

        return back()->with('success', 'Account created successfully. Default password is: ' . $password);
    }
}
