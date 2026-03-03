<x-layouts::app title="Create Customer">
    <div class="max-w-2xl">
        <flux:heading class="mb-6">Create Customer</flux:heading>

        <flux:card>
            <form method="POST" action="{{ route('customers.store') }}">
                @csrf

                <flux:fieldset>
                    <flux:field>
                        <flux:label for="customer_id">Customer ID</flux:label>
                        <flux:input
                            id="customer_id"
                            name="customer_id"
                            type="text"
                            required
                            value="{{ old('customer_id') }}"
                        />
                        @error('customer_id')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="name">Full Name</flux:label>
                        <flux:input
                            id="name"
                            name="name"
                            type="text"
                            required
                            value="{{ old('name') }}"
                        />
                        @error('name')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="type">Customer Type</flux:label>
                        <flux:select
                            id="type"
                            name="type"
                            required
                        >
                            <option value="">Select Type</option>
                            <option value="Regular" {{ old('type') === 'Regular' ? 'selected' : '' }}>Regular</option>
                            <option value="Commercial" {{ old('type') === 'Commercial' ? 'selected' : '' }}>Commercial</option>
                        </flux:select>
                        @error('type')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="email">Email</flux:label>
                        <flux:input
                            id="email"
                            name="email"
                            type="email"
                            required
                            value="{{ old('email') }}"
                        />
                        @error('email')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="phone">Phone</flux:label>
                        <flux:input
                            id="phone"
                            name="phone"
                            type="text"
                            required
                            value="{{ old('phone') }}"
                        />
                        @error('phone')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="address">Address</flux:label>
                        <flux:textarea
                            id="address"
                            name="address"
                            required
                        >{{ old('address') }}</flux:textarea>
                        @error('address')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                </flux:fieldset>

                <div class="flex gap-3 mt-6">
                    <flux:button type="submit" variant="primary">Create Customer</flux:button>
                    <flux:button :href="route('customers.index')" variant="ghost" wire:navigate>Cancel</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
