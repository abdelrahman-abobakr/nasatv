@extends('layouts.admin')

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Users Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Users</p>
                <p class="text-3xl font-bold text-gray-800">{{ \App\Models\User::count() }}</p>
            </div>
            <div class="bg-primary bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="mt-4 text-primary hover:underline text-sm inline-block">
            Manage Users →
        </a>
    </div>

    <!-- Subscriptions Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Subscriptions</p>
                <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Subscription::count() }}</p>
            </div>
            <div class="bg-primary bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.subscriptions.index') }}" class="mt-4 text-primary hover:underline text-sm inline-block">
            View Subscriptions →
        </a>
    </div>

    <!-- Plans Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Plans</p>
                <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Plan::count() }}</p>
            </div>
            <div class="bg-primary bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.plans.index') }}" class="mt-4 text-primary hover:underline text-sm inline-block">
            Manage Plans →
        </a>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
    <!-- Amount Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Subscription Amount per Employee</h3>
            <div class="flex gap-2">
                <select id="yearFilter" onchange="updateCharts()" class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm">
                    <option value="all">All Years</option>
                    @foreach(range(now()->year, now()->year - 5) as $year)
                        <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <select id="monthFilter" onchange="updateCharts()" class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm">
                    <option value="all">Overall</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="relative h-80 w-full">
            <canvas id="employeeSubscriptionsAmountChart"></canvas>
        </div>
    </div>

    <!-- Count Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Subscription Count per Employee</h3>
             <p class="text-xs text-gray-500">Filtered by Start Date</p>
        </div>
        <div class="relative h-80 w-full">
            <canvas id="employeeSubscriptionsCountChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let amountChartInstance = null;
    let countChartInstance = null;

    async function fetchChartData(month, year) {
        const response = await fetch(`{{ route('admin.dashboard.chart-data') }}?month=${month}&year=${year}`);
        return await response.json();
    }

    async function updateCharts() {
        const month = document.getElementById('monthFilter').value;
        const year = document.getElementById('yearFilter').value;
        const data = await fetchChartData(month, year);

        updateAmountChart(data);
        updateCountChart(data);
    }

    function updateAmountChart(data) {
        if (amountChartInstance) {
            amountChartInstance.destroy();
        }

        const ctx = document.getElementById('employeeSubscriptionsAmountChart').getContext('2d');
        amountChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Total Subscription Amount ($)',
                    data: data.amount_data,
                    backgroundColor: 'rgba(234, 179, 8, 0.6)', 
                    borderColor: 'rgba(234, 179, 8, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    function updateCountChart(data) {
        if (countChartInstance) {
            countChartInstance.destroy();
        }

        const ctx = document.getElementById('employeeSubscriptionsCountChart').getContext('2d');
        countChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Total Subscriptions Count',
                    data: data.count_data,
                    backgroundColor: 'rgba(59, 130, 246, 0.6)', // Blue
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', updateCharts);
</script>

<!-- Recent Subscriptions -->
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Recent Subscriptions</h3>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Added By</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse(\App\Models\Subscription::with('addedBy')->latest()->take(5)->get() as $subscription)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $subscription->client_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscription->addedBy->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($subscription->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscription->start_date->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No subscriptions yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
