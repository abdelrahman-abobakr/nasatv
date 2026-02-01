@extends('layouts.employee')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Welcome, {{ auth()->user()->name }}!</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- My Subscriptions Card -->
        <div class="bg-gradient-to-br from-primary to-yellow-500 rounded-lg shadow p-6 text-dark">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-80">My Subscriptions</p>
                    <p class="text-4xl font-bold mt-2">{{ auth()->user()->subscriptions()->count() }}</p>
                </div>
                <div class="bg-dark bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('employee.subscriptions.index') }}" class="mt-4 inline-block text-dark hover:underline font-medium">
                View All →
            </a>
        </div>

        <!-- Add New Subscription Card -->
        <div class="bg-dark rounded-lg shadow p-6 text-light">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-300">Quick Action</p>
                    <p class="text-xl font-bold mt-2">Add New Subscription</p>
                </div>
                <div class="bg-primary bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('employee.subscriptions.create') }}" class="mt-4 inline-block bg-primary hover:bg-yellow-500 text-dark font-semibold py-2 px-4 rounded">
                Create Subscription
            </a>
        </div>
    </div>

    <!-- Recent Subscriptions -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Subscriptions</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse(auth()->user()->subscriptions()->latest()->take(5)->get() as $subscription)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $subscription->client_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($subscription->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscription->duration }}</td>
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
