<x-layouts::app title="Dashboard">
    <flux:heading class="mb-6">Dashboard</flux:heading>
    
    <!-- Revenue Stats -->
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
        <flux:card>
            <div class="flex items-center justify-between">
                <div>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Total Customers</flux:text>
                    <flux:heading size="lg" class="mt-2">{{ $totalCustomers }}</flux:heading>
                </div>
                <flux:icon icon="users" class="w-12 h-12 text-blue-500" />
            </div>
        </flux:card>

        <flux:card>
            <div class="flex items-center justify-between">
                <div>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Total Revenue</flux:text>
                    <flux:heading size="lg" class="mt-2">₱{{ number_format($totalRevenue, 2) }}</flux:heading>
                </div>
                <flux:icon icon="wallet" class="w-12 h-12 text-green-500" />
            </div>
        </flux:card>

        <flux:card>
            <div class="flex items-center justify-between">
                <div>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Pending Revenue</flux:text>
                    <flux:heading size="lg" class="mt-2">₱{{ number_format($pendingRevenue, 2) }}</flux:heading>
                </div>
                <flux:icon icon="clock" class="w-12 h-12 text-orange-500" />
            </div>
        </flux:card>

        <flux:card>
            <div class="flex items-center justify-between">
                <div>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Total Consumption</flux:text>
                    <flux:heading size="lg" class="mt-2">{{ number_format($totalConsumption, 2) }} L</flux:heading>
                </div>
                <flux:icon icon="inbox" class="w-12 h-12 text-cyan-500" />
            </div>
        </flux:card>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
        <!-- Monthly Revenue Chart -->
        <flux:card>
            <flux:heading size="md" class="mb-4">Monthly Revenue Trend</flux:heading>
            <div style="position: relative; height: 350px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </flux:card>

        <!-- Usage Trend Chart -->
        <flux:card>
            <flux:heading size="md" class="mb-4">Water Usage Trend</flux:heading>
            <div style="position: relative; height: 350px;">
                <canvas id="usageChart"></canvas>
            </div>
        </flux:card>
    </div>

    <!-- Distribution Charts -->
    <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
        <!-- Customer Type Distribution -->
        <flux:card>
            <flux:heading size="md" class="mb-4">Customer Distribution</flux:heading>
            <div style="position: relative; height: 350px;">
                <canvas id="customerTypesChart"></canvas>
            </div>
        </flux:card>

        <!-- Revenue by Type -->
        <flux:card>
            <flux:heading size="md" class="mb-4">Revenue by Customer Type</flux:heading>
            <div style="position: relative; height: 350px;">
                <canvas id="revenueByTypeChart"></canvas>
            </div>
        </flux:card>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Monthly Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: 'Revenue (₱)',
                data: {!! json_encode($monthlyRevenue->pluck('total')) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
            }]
        };
        new Chart(revenueCtx, {
            type: 'line',
            data: revenueData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });

        // Usage Trend Chart
        const usageCtx = document.getElementById('usageChart').getContext('2d');
        const usageData = {
            labels: {!! json_encode($usageTrend->pluck('month')) !!},
            datasets: [{
                label: 'Usage (cubic meters)',
                data: {!! json_encode($usageTrend->pluck('total_usage')) !!},
                borderColor: '#06b6d4',
                backgroundColor: 'rgba(6, 182, 212, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
            }]
        };
        new Chart(usageCtx, {
            type: 'line',
            data: usageData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });

        // Customer Types Pie Chart
        const customerTypesCtx = document.getElementById('customerTypesChart').getContext('2d');
        const customerTypesData = {
            labels: {!! json_encode($customerTypes->pluck('type')) !!},
            datasets: [{
                data: {!! json_encode($customerTypes->pluck('count')) !!},
                backgroundColor: [
                    '#3b82f6',
                    '#f59e0b',
                ],
                borderColor: '#fff',
                borderWidth: 2,
            }]
        };
        new Chart(customerTypesCtx, {
            type: 'doughnut',
            data: customerTypesData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    }
                }
            }
        });

        // Revenue by Type Chart
        const revenueByTypeCtx = document.getElementById('revenueByTypeChart').getContext('2d');
        const revenueByTypeData = {
            labels: {!! json_encode($revenueByType->pluck('type')) !!},
            datasets: [{
                label: 'Revenue (₱)',
                data: {!! json_encode($revenueByType->pluck('total')) !!},
                backgroundColor: [
                    '#6366f1',
                    '#ec4899',
                ],
                borderColor: '#fff',
                borderWidth: 2,
            }]
        };
        new Chart(revenueByTypeCtx, {
            type: 'bar',
            data: revenueByTypeData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                    }
                }
            }
        });
    </script>
</x-layouts::app>
