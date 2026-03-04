<x-layouts::app title="Dashboard">
    <div class="px-6 py-4 bg-[#f8f9fa] min-h-screen font-sans text-gray-700">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Dashboard</h1>
        
        <!-- Revenue Stats -->
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
            <div class="bg-white rounded shadow-sm border border-gray-200">
                <div class="p-4 bg-[#42a5f5] text-white rounded-t flex justify-between items-center">
                    <h3 class="text-sm font-semibold uppercase tracking-wider">Total Customers</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="p-6 text-center">
                    <p class="text-3xl font-bold text-gray-800">{{ $totalCustomers }}</p>
                </div>
            </div>

            <div class="bg-white rounded shadow-sm border border-gray-200">
                <div class="p-4 bg-[#5cb85c] text-white rounded-t flex justify-between items-center">
                    <h3 class="text-sm font-semibold uppercase tracking-wider">Total Revenue</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="p-6 text-center">
                    <p class="text-3xl font-bold text-gray-800">₱{{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>

            <div class="bg-white rounded shadow-sm border border-gray-200">
                <div class="p-4 bg-[#f0ad4e] text-white rounded-t flex justify-between items-center">
                    <h3 class="text-sm font-semibold uppercase tracking-wider">Pending Revenue</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="p-6 text-center">
                    <p class="text-3xl font-bold text-gray-800">₱{{ number_format($pendingRevenue, 2) }}</p>
                </div>
            </div>

            <div class="bg-white rounded shadow-sm border border-gray-200">
                <div class="p-4 bg-[#5bc0de] text-white rounded-t flex justify-between items-center">
                    <h3 class="text-sm font-semibold uppercase tracking-wider">Total Usage</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="p-6 text-center">
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($totalConsumption, 2) }} L</p>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded shadow-sm overflow-hidden border border-gray-200">
                <div class="bg-[#42a5f5] px-4 py-3 text-white font-medium">
                    Monthly Revenue Trend (Flow Chart)
                </div>
                <div class="p-4" style="position: relative; height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded shadow-sm overflow-hidden border border-gray-200">
                <div class="bg-[#42a5f5] px-4 py-3 text-white font-medium">
                    Water Usage Trend (Flow Chart)
                </div>
                <div class="p-4" style="position: relative; height: 300px;">
                    <canvas id="usageChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if charts are already initialized to prevent errors on double loads
            
            // Monthly Revenue Chart - Styled as a curved line (flow) chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyRevenue->pluck('month')->count() ? $monthlyRevenue->pluck('month') : ['Current']) !!},
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: {!! json_encode($monthlyRevenue->pluck('total')->count() ? $monthlyRevenue->pluck('total') : [0]) !!},
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
                        legend: { display: false },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#f0f0f0' },
                            title: { display: true, text: 'Revenue (₱)' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Usage Trend Chart - Styled as a curved line (flow) chart
            const usageCtx = document.getElementById('usageChart').getContext('2d');
            new Chart(usageCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($usageTrend->pluck('month')->count() ? $usageTrend->pluck('month') : ['Current']) !!},
                    datasets: [{
                        label: 'Usage (L)',
                        data: {!! json_encode($usageTrend->pluck('total_usage')->count() ? $usageTrend->pluck('total_usage') : [0]) !!},
                        borderColor: '#2ecc71',
                        backgroundColor: 'rgba(46, 204, 113, 0.2)',
                        borderWidth: 3,
                        pointBackgroundColor: '#27ae60',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#f0f0f0' },
                            title: { display: true, text: 'Usage (L)' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</x-layouts::app>
