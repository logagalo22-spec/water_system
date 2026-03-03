<x-layouts::app title="Customer Details">
    <div class="max-w-4xl">
        <div class="flex items-center justify-between mb-6">
            <flux:heading>{{ $customer->name }}</flux:heading>
            <div class="flex gap-2">
                <flux:button :href="route('customers.edit', $customer)" variant="primary" wire:navigate>
                    <flux:icon icon="pencil" variant="micro" />
                    Edit
                </flux:button>
                <flux:button :href="route('customers.index')" variant="ghost" wire:navigate>Back</flux:button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-6">
            <flux:card>
                <flux:heading size="sm" class="mb-4">Customer Information</flux:heading>
                <div class="space-y-3">
                    <div>
                        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Customer ID</flux:text>
                        <flux:text class="font-semibold">{{ $customer->customer_id }}</flux:text>
                    </div>
                    <div>
                        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Type</flux:text>
                        <flux:badge :label="$customer->type" :color="$customer->type === 'Regular' ? 'blue' : 'amber'" />
                    </div>
                    <div>
                        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Status</flux:text>
                        <flux:badge :label="ucfirst($customer->status)" :color="$customer->status === 'active' ? 'green' : 'red'" />
                    </div>
                </div>
            </flux:card>

            <flux:card>
                <flux:heading size="sm" class="mb-4">Contact Information</flux:heading>
                <div class="space-y-3">
                    <div>
                        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Email</flux:text>
                        <flux:text class="font-semibold">{{ $customer->email }}</flux:text>
                    </div>
                    <div>
                        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Phone</flux:text>
                        <flux:text class="font-semibold">{{ $customer->phone }}</flux:text>
                    </div>
                    <div>
                        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Address</flux:text>
                        <flux:text class="font-semibold">{{ $customer->address }}</flux:text>
                    </div>
                </div>
            </flux:card>
        </div>

        <!-- Water Usages -->
        <flux:card class="mb-6">
            <flux:heading size="sm" class="mb-4">Water Usage History</flux:heading>
            @if ($customer->waterUsages->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-zinc-200 dark:border-zinc-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Date</th>
                                <th class="px-4 py-2 text-left">Previous Reading</th>
                                <th class="px-4 py-2 text-left">Current Reading</th>
                                <th class="px-4 py-2 text-left">Usage (m³)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach ($customer->waterUsages as $usage)
                                <tr>
                                    <td class="px-4 py-2">{{ $usage->reading_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-2">{{ $usage->previous_reading }}</td>
                                    <td class="px-4 py-2">{{ $usage->current_reading }}</td>
                                    <td class="px-4 py-2">{{ $usage->usage }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <flux:text class="text-zinc-500">No water usage records yet</flux:text>
            @endif
        </flux:card>

        <!-- Bills -->
        <flux:card>
            <flux:heading size="sm" class="mb-4">Bills</flux:heading>
            @if ($customer->bills->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-zinc-200 dark:border-zinc-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Billing Date</th>
                                <th class="px-4 py-2 text-left">Usage</th>
                                <th class="px-4 py-2 text-left">Total Amount</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Due Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach ($customer->bills as $bill)
                                <tr>
                                    <td class="px-4 py-2">{{ $bill->billing_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-2">{{ $bill->usage_units }} m³</td>
                                    <td class="px-4 py-2">₱{{ number_format($bill->total_amount, 2) }}</td>
                                    <td class="px-4 py-2">
                                        <flux:badge
                                            :label="$bill->status"
                                            :color="$bill->status === 'Paid' ? 'green' : 'orange'"
                                        />
                                    </td>
                                    <td class="px-4 py-2">{{ $bill->due_date->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <flux:text class="text-zinc-500">No bills yet</flux:text>
            @endif
        </flux:card>
    </div>
</x-layouts::app>
