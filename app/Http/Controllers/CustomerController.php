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
        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|unique:customers',
            'name' => 'required|string',
            'type' => 'required|in:Regular,Commercial',
            'email' => 'required|email|unique:customers',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

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
            'customer_id' => 'required|unique:customers,customer_id,' . $customer->id,
            'name' => 'required|string',
            'type' => 'required|in:Regular,Commercial',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

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
}
