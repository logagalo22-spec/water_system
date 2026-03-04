<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Bill;

class RecoveryController extends Controller
{
    public function index()
    {
        $deletedCustomers = Customer::onlyTrashed()->get();
        $deletedBills = Bill::onlyTrashed()->with('customer')->get();

        return view('recovery.index', compact('deletedCustomers', 'deletedBills'));
    }

    public function restoreCustomer($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->restore();

        return redirect()->route('recovery.index')->with('success', 'Customer restored successfully.');
    }

    public function restoreBill($id)
    {
        $bill = Bill::onlyTrashed()->findOrFail($id);
        $bill->restore();

        return redirect()->route('recovery.index')->with('success', 'Bill restored successfully.');
    }
}
