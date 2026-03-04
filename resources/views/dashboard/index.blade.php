<x-layouts::app title="Dashboard">
    <div class="px-6 py-4 bg-[#f8f9fa] min-h-screen font-sans text-gray-700">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Dashboard</h1>
        
        <!-- Revenue Stats -->
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
            <div class="bg-white rounded shadow-sm border border-gray-200 p-6 flex flex-col items-center justify-center text-center">
                <div class="text-[#3498db] mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Total Customers</h3>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalCustomers }}</p>
            </div>

            <div class="bg-white rounded shadow-sm border border-gray-200 p-6 flex flex-col items-center justify-center text-center">
                <div class="text-[#2ecc71] mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Total Revenue</h3>
                <p class="text-3xl font-bold text-gray-800 mt-1">₱{{ number_format($totalRevenue, 2) }}</p>
            </div>

            <div class="bg-white rounded shadow-sm border border-gray-200 p-6 flex flex-col items-center justify-center text-center">
                <div class="text-[#f39c12] mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Pending Revenue</h3>
                <p class="text-3xl font-bold text-gray-800 mt-1">₱{{ number_format($pendingRevenue, 2) }}</p>
            </div>

            <div class="bg-white rounded shadow-sm border border-gray-200 p-6 flex flex-col items-center justify-center text-center">
                <div class="text-[#9b59b6] mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Total Consumption</h3>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalConsumption, 2) }} L</p>
            </div>
        </div>

        <!-- Charts Row: Added line charts (Flow Chart / Line Trend charts) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded shadow-sm border border-gray-200 p-4">
                <h3 class="text-[#337ab7] font-semibold text-base mb-4 border-b border-gray-100 pb-2">Monthly Revenue Trend (Flow Chart)</h3>
                <div style="position: relative; height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded shadow-sm border border-gray-200 p-4">
                <h3 class="text-[#337ab7] font-semibold text-base mb-4 border-b border-gray-100 pb-2">Water Usage Trend (Flow Chart)</h3>
                <div style="position: relative; height: 300px;">
                    <canvas id="usageChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Additional Charts Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded shadow-sm border border-gray-200 p-4">
                <h3 class="text-[#337ab7] font-semibold text-base mb-4 border-b border-gray-100 pb-2">Customer Distribution</h3>
                <div style="position: relative; height: 250px;">
                    <canvas id="customerTypesChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded shadow-sm border border-gray-200 p-4">
                <h3 class="text-[#337ab7] font-semibold text-base mb-4 border-b border-gray-100 pb-2">Revenue by Customer Type</h3>
                <div style="position: relative; height: 250px;">
                    <canvas id="revenueByTypeChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- Chart.js setup for trend flow charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if charts are already initialized to prevent errors on double loads
            
            // Monthly Revenue Chart - Styled as a curved line (flow) chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: {!! json_encode($monthlyRevenue->pluck('total')) !!},
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.2)',
                        borderWidth: 3,
                        pointBackgroundColor: '#2980b9',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4 // This makes it a smooth "flow" curve
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'top' },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Usage Trend Chart - Styled as a curved line (flow) chart
            const usageCtx = document.getElementById('usageChart').getContext('2d');
            new Chart(usageCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($usageTrend->pluck('month')) !!},
                    datasets: [{
                        label: 'Usage (L)',
                        data: {!! json_encode($usageTrend->pluck('total_usage')) !!},
                        borderColor: '#2ecc71',
                        backgroundColor: 'rgba(46, 204, 113, 0.2)',
                        borderWidth: 3,
                        pointBackgroundColor: '#27ae60',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4 // Smooth flow curve
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'top' },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Customer Types Doughnut Chart
            const customerTypesCtx = document.getElementById('customerTypesChart').getContext('2d');
            new Chart(customerTypesCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($customerTypes->pluck('type')) !!},
                    datasets: [{
                        data: {!! json_encode($customerTypes->pluck('count')) !!},
                        backgroundColor: ['#3498db', '#f1c40f', '#e74c3c'],
                        borderWidth: 1,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            });

            // Revenue by Type Bar Chart
            const revenueByTypeCtx = document.getElementById('revenueByTypeChart').getContext('2d');
            new Chart(revenueByTypeCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($revenueByType->pluck('type')) !!},
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: {!! json_encode($revenueByType->pluck('total')) !!},
                        backgroundColor: ['#9b59b6', '#34495e'],
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</x-layouts::app>
