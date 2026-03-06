<x-layouts::app title="Billing Reports">
    <div class="px-6 py-4 bg-[#f8f9fa] min-h-screen font-sans text-gray-700">
        
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Billing Reports</h1>
        
        <div class="mb-6">
            <input type="text" placeholder="Search by Consumer No. or Name" class="w-full max-w-lg px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-400">
        </div>

        <h2 class="text-lg font-semibold mb-3">Pending Bills</h2>
        
        <div class="bg-white rounded shadow-sm overflow-hidden mb-8 border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#42a5f5] text-white">
                        <th class="px-4 py-3 font-medium">Period</th>
                        <th class="px-4 py-3 font-medium">No.</th>
                        <th class="px-4 py-3 font-medium">Customer</th>
                        <th class="px-4 py-3 font-medium">Usage</th>
                        <th class="px-4 py-3 font-medium">Bill</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($bills->where('status', '!=', 'Paid') as $bill)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $bill->billing_date->format('F Y') }}</td>
                        <td class="px-4 py-3">{{ $bill->customer->customer_number ?? $bill->customer->id }}</td>
                        <td class="px-4 py-3">{{ $bill->customer->name }}</td>
                        @php
                            $cType = $bill->customer->type ?? 'Regular';
                            $greenMax = $thresholds[$cType]['green_max'] ?? 12;
                            $orangeMax = $thresholds[$cType]['orange_max'] ?? 14;
                            $bgClass = $bill->usage_units <= $greenMax ? 'bg-[#5cb85c]' : ($bill->usage_units <= $orangeMax ? 'bg-[#f0ad4e]' : 'bg-[#d9534f]');
                        @endphp
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium text-white {{ $bgClass }}">
                                {{ $bill->usage_units }} L
                            </span>
                        </td>
                        <td class="px-4 py-3">₱{{ number_format($bill->total_amount, 0) }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('billing.mark-paid', $bill) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-[#5cb85c] hover:bg-[#4cae4c] text-white px-3 py-1 rounded text-sm font-medium">
                                    Mark as Paid
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">No pending bills</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h2 class="text-lg font-semibold mb-3">Payment History (Paid)</h2>
        
        <div class="bg-white rounded shadow-sm overflow-hidden mb-8 border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#42a5f5] text-white">
                        <th class="px-4 py-3 font-medium">Period</th>
                        <th class="px-4 py-3 font-medium">No.</th>
                        <th class="px-4 py-3 font-medium">Customer</th>
                        <th class="px-4 py-3 font-medium">Usage</th>
                        <th class="px-4 py-3 font-medium">Bill</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($bills->where('status', 'Paid') as $bill)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $bill->billing_date->format('F Y') }}</td>
                        <td class="px-4 py-3">{{ $bill->customer->customer_number ?? $bill->customer->id }}</td>
                        <td class="px-4 py-3">{{ $bill->customer->name }}</td>
                        @php
                            $cType = $bill->customer->type ?? 'Regular';
                            $greenMax = $thresholds[$cType]['green_max'] ?? 12;
                            $orangeMax = $thresholds[$cType]['orange_max'] ?? 14;
                            $bgClass = $bill->usage_units <= $greenMax ? 'bg-[#5cb85c]' : ($bill->usage_units <= $orangeMax ? 'bg-[#f0ad4e]' : 'bg-[#d9534f]');
                        @endphp
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium text-white {{ $bgClass }}">
                                {{ $bill->usage_units }} L
                            </span>
                        </td>
                        <td class="px-4 py-3">₱{{ number_format($bill->total_amount, 0) }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('billing.show', $bill) }}" class="text-[#337ab7] hover:underline text-sm">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">No payment history</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
