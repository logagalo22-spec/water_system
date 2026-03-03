<x-layouts::app title="Billing Reports">
    <div class="flex items-center justify-between mb-6">
        <flux:heading>Billing Reports</flux:heading>
        <flux:button href="{{ route('billing.create') }}" variant="primary" wire:navigate>
            <flux:icon icon="plus" variant="micro" />
            New Bill
        </flux:button>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
        <flux:card>
            <div class="flex items-center justify-between">
                <div>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Total Billed</flux:text>
                    <flux:heading size="lg" class="mt-2">₱{{ number_format($totalBilled, 2) }}</flux:heading>
                </div>
                <flux:icon icon="wallet" class="w-12 h-12 text-green-500" />
            </div>
        </flux:card>

        <flux:card>
            <div class="flex items-center justify-between">
                <div>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Paid Bills</flux:text>
                    <flux:heading size="lg" class="mt-2">{{ $paidCount }}</flux:heading>
                </div>
                <flux:icon icon="check-circle" class="w-12 h-12 text-green-500" />
            </div>
        </flux:card>

        <flux:card>
            <div class="flex items-center justify-between">
                <div>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Pending Bills</flux:text>
                    <flux:heading size="lg" class="mt-2">{{ $pendingCount }}</flux:heading>
                </div>
                <flux:icon icon="clock" class="w-12 h-12 text-orange-500" />
            </div>
        </flux:card>
    </div>

    <!-- Bills Table -->
    @if ($bills->count())
        <flux:card>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-zinc-200 dark:border-zinc-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Customer</th>
                            <th class="px-4 py-3 text-left font-semibold">Billing Date</th>
                            <th class="px-4 py-3 text-left font-semibold">Usage (m³)</th>
                            <th class="px-4 py-3 text-left font-semibold">Total Amount</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold">Due Date</th>
                            <th class="px-4 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach ($bills as $bill)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-900">
                                <td class="px-4 py-3">
                                    <a href="{{ route('customers.show', $bill->customer) }}" class="text-blue-600 hover:underline" wire:navigate>
                                        {{ $bill->customer->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">{{ $bill->billing_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3">{{ $bill->usage_units }}</td>
                                <td class="px-4 py-3 font-semibold">₱{{ number_format($bill->total_amount, 2) }}</td>
                                <td class="px-4 py-3">
                                    <flux:badge
                                        :label="$bill->status"
                                        :color="$bill->status === 'Paid' ? 'green' : 'orange'"
                                    />
                                </td>
                                <td class="px-4 py-3">{{ $bill->due_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <flux:dropdown>
                                        <flux:button icon="ellipsis-horizontal" variant="subtle" size="sm" />
                                        <flux:menu>
                                            <flux:menu.item
                                                :href="route('billing.show', $bill)"
                                                icon="eye"
                                                wire:navigate
                                            >
                                                View
                                            </flux:menu.item>
                                            @if ($bill->status !== 'Paid')
                                                <flux:menu.item
                                                    icon="check-circle"
                                                    onclick="confirm('Mark this bill as paid?') && document.getElementById('mark-paid-{{ $bill->id }}').submit()"
                                                >
                                                    Mark as Paid
                                                </flux:menu.item>
                                                <form id="mark-paid-{{ $bill->id }}" action="{{ route('billing.mark-paid', $bill) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('PATCH')
                                                </form>
                                            @endif
                                            <flux:menu.separator />
                                            <flux:menu.item
                                                icon="trash"
                                                class="text-red-600 dark:text-red-400"
                                                onclick="confirm('Are you sure?') && document.getElementById('delete-{{ $bill->id }}').submit()"
                                            >
                                                Delete
                                            </flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                    <form id="delete-{{ $bill->id }}" action="{{ route('billing.destroy', $bill) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </flux:card>

        <div class="mt-6">
            {{ $bills->links() }}
        </div>
    @else
        <flux:card>
            <div class="text-center py-12">
                <flux:icon icon="inbox" class="w-16 h-16 text-zinc-300 dark:text-zinc-600 mx-auto mb-4" />
                <flux:heading size="md">No bills yet</flux:heading>
                <flux:text class="mt-2">Create your first bill to get started</flux:text>
                <flux:button class="mt-4" href="{{ route('billing.create') }}" variant="primary" wire:navigate>
                    Create Bill
                </flux:button>
            </div>
        </flux:card>
    @endif
</x-layouts::app>
