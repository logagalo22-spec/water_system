<x-layouts::app title="Customers">
    <div class="flex items-center justify-between mb-6">
        <flux:heading>Customers</flux:heading>
        <flux:button href="{{ route('customers.create') }}" variant="primary" wire:navigate>
            <flux:icon icon="plus" variant="micro" />
            New Customer
        </flux:button>
    </div>

    @if ($customers->count())
        <flux:card>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-zinc-200 dark:border-zinc-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Customer ID</th>
                            <th class="px-4 py-3 text-left font-semibold">Name</th>
                            <th class="px-4 py-3 text-left font-semibold">Type</th>
                            <th class="px-4 py-3 text-left font-semibold">Email</th>
                            <th class="px-4 py-3 text-left font-semibold">Phone</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach ($customers as $customer)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-900">
                                <td class="px-4 py-3">{{ $customer->customer_id }}</td>
                                <td class="px-4 py-3">{{ $customer->name }}</td>
                                <td class="px-4 py-3">
                                    <flux:badge
                                        :color="$customer->type === 'Regular' ? 'blue' : 'amber'"
                                        :label="$customer->type"
                                    />
                                </td>
                                <td class="px-4 py-3">{{ $customer->email }}</td>
                                <td class="px-4 py-3">{{ $customer->phone }}</td>
                                <td class="px-4 py-3">
                                    <flux:badge
                                        :color="$customer->status === 'active' ? 'green' : 'red'"
                                        :label="ucfirst($customer->status)"
                                    />
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <flux:dropdown>
                                        <flux:button icon="ellipsis-horizontal" variant="subtle" size="sm" />
                                        <flux:menu>
                                            <flux:menu.item
                                                :href="route('customers.show', $customer)"
                                                icon="eye"
                                                wire:navigate
                                            >
                                                View
                                            </flux:menu.item>
                                            <flux:menu.item
                                                :href="route('customers.edit', $customer)"
                                                icon="pencil"
                                                wire:navigate
                                            >
                                                Edit
                                            </flux:menu.item>
                                            <flux:menu.separator />
                                            <flux:menu.item
                                                icon="trash"
                                                class="text-red-600 dark:text-red-400"
                                                onclick="confirm('Are you sure?') && document.getElementById('delete-{{ $customer->id }}').submit()"
                                            >
                                                Delete
                                            </flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                    <form id="delete-{{ $customer->id }}" action="{{ route('customers.destroy', $customer) }}" method="POST" style="display: none;">
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
            {{ $customers->links() }}
        </div>
    @else
        <flux:card>
            <div class="text-center py-12">
                <flux:icon icon="users" class="w-16 h-16 text-zinc-300 dark:text-zinc-600 mx-auto mb-4" />
                <flux:heading size="md">No customers yet</flux:heading>
                <flux:text class="mt-2">Create your first customer to get started</flux:text>
                <flux:button class="mt-4" href="{{ route('customers.create') }}" variant="primary" wire:navigate>
                    Create Customer
                </flux:button>
            </div>
        </flux:card>
    @endif
</x-layouts::app>
