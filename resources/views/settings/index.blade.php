<x-layouts::app title="System Settings">
    <div class="max-w-2xl">
        <flux:heading class="mb-6">System Settings</flux:heading>

        <flux:card>
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf

                <flux:fieldset>
                    <flux:field>
                        <flux:label for="base_charge">Base Charge (₱)</flux:label>
                        <flux:input
                            id="base_charge"
                            name="base_charge"
                            type="number"
                            step="0.01"
                            required
                            value="{{ old('base_charge', $settings['base_charge']) }}"
                        />
                        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm mt-2">
                            The fixed monthly charge for all customers
                        </flux:text>
                        @error('base_charge')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="usage_rate">Usage Rate (₱/m³)</flux:label>
                        <flux:input
                            id="usage_rate"
                            name="usage_rate"
                            type="number"
                            step="0.01"
                            required
                            value="{{ old('usage_rate', $settings['usage_rate']) }}"
                        />
                        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm mt-2">
                            The cost per cubic meter of water consumed
                        </flux:text>
                        @error('usage_rate')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="alert_threshold">Alert Threshold (m³)</flux:label>
                        <flux:input
                            id="alert_threshold"
                            name="alert_threshold"
                            type="number"
                            step="0.01"
                            required
                            value="{{ old('alert_threshold', $settings['alert_threshold']) }}"
                        />
                        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm mt-2">
                            Usage threshold for sending alerts
                        </flux:text>
                        @error('alert_threshold')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="alert_email">Alert Email Address</flux:label>
                        <flux:input
                            id="alert_email"
                            name="alert_email"
                            type="email"
                            required
                            value="{{ old('alert_email', $settings['alert_email']) }}"
                        />
                        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm mt-2">
                            Email address for receiving system alerts
                        </flux:text>
                        @error('alert_email')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:fieldset class="mt-4">
                        <flux:heading size="sm" class="mb-2">Water Usage Color Indicators</flux:heading>
                        <flux:text class="text-zinc-600 dark:text-zinc-300 text-sm mb-4">
                            Configure the water usage increase thresholds that determine the color codes displayed when creating bills.
                            Colors are based on the <strong>increase in water usage</strong> (amount above the 10L minimum).
                        </flux:text>

                        <flux:field>
                            <flux:label>Regular Customer - Green Threshold (L)</flux:label>
                            <flux:input
                                id="regular_green_max"
                                name="regular_green_max"
                                type="number"
                                step="1"
                                required
                                value="{{ old('regular_green_max', $settings['regular_green_max']) }}"
                            />
                            <flux:text class="text-zinc-500 dark:text-zinc-400 text-xs mt-1">
                                🟢 Usage increase up to this amount appears in green (1 - {{ $settings['regular_green_max'] }} L)
                            </flux:text>
                        </flux:field>

                        <flux:field>
                            <flux:label>Regular Customer - Orange Threshold (L)</flux:label>
                            <flux:input
                                id="regular_orange_max"
                                name="regular_orange_max"
                                type="number"
                                step="1"
                                required
                                value="{{ old('regular_orange_max', $settings['regular_orange_max']) }}"
                            />
                            <flux:text class="text-zinc-500 dark:text-zinc-400 text-xs mt-1">
                                🟠 Usage increase in this range appears in orange ({{ intval($settings['regular_green_max']) + 1 }} - {{ $settings['regular_orange_max'] }} L)
                            </flux:text>
                        </flux:field>

                        <flux:field>
                            <flux:label>Commercial Customer - Green Threshold (L)</flux:label>
                            <flux:input
                                id="commercial_green_max"
                                name="commercial_green_max"
                                type="number"
                                step="1"
                                required
                                value="{{ old('commercial_green_max', $settings['commercial_green_max']) }}"
                            />
                            <flux:text class="text-zinc-500 dark:text-zinc-400 text-xs mt-1">
                                🟢 Usage increase up to this amount appears in green (1 - {{ $settings['commercial_green_max'] }} L)
                            </flux:text>
                        </flux:field>

                        <flux:field>
                            <flux:label>Commercial Customer - Orange Threshold (L)</flux:label>
                            <flux:input
                                id="commercial_orange_max"
                                name="commercial_orange_max"
                                type="number"
                                step="1"
                                required
                                value="{{ old('commercial_orange_max', $settings['commercial_orange_max']) }}"
                            />
                            <flux:text class="text-zinc-500 dark:text-zinc-400 text-xs mt-1">
                                🟠 Usage increase in this range appears in orange ({{ intval($settings['commercial_green_max']) + 1 }} - {{ $settings['commercial_orange_max'] }} L)
                            </flux:text>
                            <flux:text class="text-zinc-500 dark:text-zinc-400 text-xs mt-1">
                                🔴 Usage increase above {{ $settings['commercial_orange_max'] }} L appears in red
                            </flux:text>
                        </flux:field>
                    </flux:fieldset>
                </flux:fieldset>

                <div class="flex gap-3 mt-6">
                    <flux:button type="submit" variant="primary">Save Settings</flux:button>
                    <flux:button :href="route('dashboard')" variant="ghost" wire:navigate>Back</flux:button>
                </div>
            </form>
        </flux:card>

        <flux:card class="mt-6">
            <flux:heading size="sm" class="mb-4">Settings Information</flux:heading>
            <div class="space-y-4 text-sm">
                <div>
                    <flux:text class="font-semibold">Base Charge</flux:text>
                    <flux:text class="text-zinc-500 dark:text-zinc-400">
                        The fixed monthly charge applied to all water bills regardless of usage.
                    </flux:text>
                </div>
                <div>
                    <flux:text class="font-semibold">Usage Rate</flux:text>
                    <flux:text class="text-zinc-500 dark:text-zinc-400">
                        The cost per cubic meter of water consumed. This is multiplied by the customer's water usage to determine usage charges.
                    </flux:text>
                </div>
                <div>
                    <flux:text class="font-semibold">Alert Threshold</flux:text>
                    <flux:text class="text-zinc-500 dark:text-zinc-400">
                        When a customer's water usage exceeds this threshold, an alert will be sent to the alert email address.
                    </flux:text>
                </div>
                <div>
                    <flux:text class="font-semibold">Water Usage Color Indicators</flux:text>
                    <flux:text class="text-zinc-500 dark:text-zinc-400">
                        Color codes help administrators quickly identify high usage levels when creating bills:
                    </flux:text>
                    <div class="mt-2 ml-4 space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="text-green-600 font-bold">●</span>
                            <flux:text class="text-zinc-500 dark:text-zinc-400">
                                <strong>Green</strong>: Normal usage (1 to green threshold)
                            </flux:text>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-orange-600 font-bold">●</span>
                            <flux:text class="text-zinc-500 dark:text-zinc-400">
                                <strong>Orange</strong>: Elevated usage (green + 1 to orange threshold)
                            </flux:text>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-red-600 font-bold">●</span>
                            <flux:text class="text-zinc-500 dark:text-zinc-400">
                                <strong>Red</strong>: High usage (above orange threshold)
                            </flux:text>
                        </div>
                    </div>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 mt-2">
                        Different thresholds can be configured for regular and commercial customers to reflect their typical usage patterns.
                    </flux:text>
                </div>
            </div>
        </flux:card>
    </div>
</x-layouts::app>
