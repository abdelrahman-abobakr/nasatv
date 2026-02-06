@extends('layouts.admin')

@section('header', 'Dashboard')

@section('content')
<!-- Welcome Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h1>
    <p class="text-gray-600">Here's a quick overview of your system's performance.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Users Card -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 transform transition-all hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium uppercase tracking-wider">Total Users</p>
                <p class="text-3xl font-bold text-white mt-1">{{ \App\Models\User::count() }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="mt-4 text-blue-100 hover:text-white text-sm font-medium flex items-center">
            Manage Users
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <!-- Subscriptions Card -->
    <div class="bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl shadow-lg p-6 transform transition-all hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-amber-50 pr-4 text-sm font-medium uppercase tracking-wider">Total Subscriptions</p>
                <p class="text-3xl font-bold text-white mt-1">{{ \App\Models\Subscription::count() }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.subscriptions.index') }}" class="mt-4 text-amber-50 hover:text-white text-sm font-medium flex items-center">
            View Details
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <!-- Plans Card -->
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 transform transition-all hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-emerald-50 text-sm font-medium uppercase tracking-wider">Active Plans</p>
                <p class="text-3xl font-bold text-white mt-1">{{ \App\Models\Plan::where('status', 'active')->count() }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.plans.index') }}" class="mt-4 text-emerald-50 hover:text-white text-sm font-medium flex items-center">
            Manage Plans
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
    <!-- Amount Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Revenue Performance</h3>
                <p class="text-xs text-gray-500">Subscription Amount per Employee</p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <select id="yearFilter" onchange="updateCharts()" class="flex-1 sm:flex-none rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm">
                    <option value="all">All Years</option>
                    @foreach(range(now()->year, now()->year - 5) as $year)
                        <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <select id="monthFilter" onchange="updateCharts()" class="flex-1 sm:flex-none rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm">
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
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800">Sales Volume</h3>
            <p class="text-xs text-gray-500">Subscription Count per Employee (Filtered by Start Date)</p>
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
<div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
        <h3 class="text-lg font-bold text-gray-800">Recent Transactions</h3>
        <p class="text-xs text-gray-500">Latest 5 subscriptions added to the system</p>
    </div>
    <div class="p-0">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Client Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Revenue Manager</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse(\App\Models\Subscription::with('addedBy')->latest()->take(5)->get() as $subscription)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-semibold text-gray-900">{{ $subscription->client_name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                {{ $subscription->addedBy->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold">
                                    ${{ number_format($subscription->amount, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-mono text-xs">
                                {{ $subscription->start_date->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">No transactions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden divide-y divide-gray-100">
            @forelse(\App\Models\Subscription::with('addedBy')->latest()->take(5)->get() as $subscription)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-bold text-gray-900">{{ $subscription->client_name }}</p>
                            <p class="text-xs text-gray-500 font-medium">By {{ $subscription->addedBy->name }}</p>
                        </div>
                        <span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-sm">
                            ${{ number_format($subscription->amount, 2) }}
                        </span>
                    </div>
                    <div class="flex items-center text-xs text-gray-400 font-mono">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $subscription->start_date->format('M d, Y') }}
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400 italic">No transactions found</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
