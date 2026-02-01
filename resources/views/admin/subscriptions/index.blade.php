@extends('layouts.admin')

@section('header', 'Subscriptions Management')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">All Subscriptions</h3>
        <a href="{{ route('admin.subscriptions.create') }}" class="bg-primary hover:bg-yellow-500 text-dark font-semibold py-2 px-4 rounded">
            Add New Subscription
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client name or phone..." 
                   class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            
            <!-- Searchable Employee Dropdown -->
            <div x-data="{
                open: false,
                search: '',
                selectedId: '{{ request('employee') }}',
                selectedName: '',
                options: [
                    { id: '', name: 'All Employees' },
                    @foreach($employees as $emp)
                        { id: '{{ $emp->id }}', name: '{{ addslashes($emp->name) }} ({{ $emp->email }})' },
                    @endforeach
                ],
                init() {
                    const selected = this.options.find(o => o.id == this.selectedId);
                    if (selected) {
                        this.selectedName = selected.name;
                    } else {
                        this.selectedName = 'All Employees';
                    }
                },
                get filteredOptions() {
                    if (this.search === '') {
                        return this.options;
                    }
                    return this.options.filter(option => 
                        option.name.toLowerCase().includes(this.search.toLowerCase())
                    );
                },
                select(option) {
                    this.selectedId = option.id;
                    this.selectedName = option.name;
                    this.open = false;
                    this.search = '';
                }
            }" class="relative" @click.away="open = false">
                <input type="hidden" name="employee" :value="selectedId">
                
                <div @click="open = !open" 
                     class="rounded-md border-gray-300 shadow-sm border p-2 bg-white cursor-pointer flex justify-between items-center h-10">
                    <span x-text="selectedName" class="truncate block"></span>
                    <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <div x-show="open" x-cloak
                     class="absolute z-50 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-200 max-h-60 overflow-y-auto">
                    
                    <div class="sticky top-0 bg-white p-2 border-b">
                         <input x-model="search" type="text" placeholder="Search..." 
                                class="w-full border-gray-300 rounded-md text-sm p-1 focus:ring-primary focus:border-primary">
                    </div>

                    <template x-for="option in filteredOptions" :key="option.id">
                        <div @click="select(option)" 
                             class="p-2 hover:bg-gray-100 cursor-pointer text-sm"
                             :class="{'bg-primary text-white': selectedId == option.id}"
                             x-text="option.name">
                        </div>
                    </template>
                    
                    <div x-show="filteredOptions.length === 0" class="p-2 text-gray-500 text-sm">
                        No results found.
                    </div>
                </div>
            </div>

            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="expire_soon" {{ request('status') == 'expire_soon' ? 'selected' : '' }}>Expire Soon</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            
            <input type="date" name="start_date" value="{{ request('start_date') }}" placeholder="Start Date"
                   class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            
            <input type="date" name="end_date" value="{{ request('end_date') }}" placeholder="End Date"
                   class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="bg-dark hover:bg-gray-800 text-light font-semibold py-2 px-6 rounded">
                    Filter
                </button>
                <button type="submit" formaction="{{ route('admin.subscriptions.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded">
                    Export Excel
                </button>
                
                @if(request('search') || request('employee') || request('start_date') || request('end_date'))
                    <a href="{{ route('admin.subscriptions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-dark font-semibold py-2 px-4 rounded">
                        Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Subscriptions Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Added By</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($subscriptions as $subscription)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $subscription->client_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscription->client_phone ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $subscription->plan === 'vip' ? 'bg-purple-100 text-purple-800' : 
                                   ($subscription->plan === 'premium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($subscription->plan) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscription->addedBy->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($subscription->amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscription->duration }} Months</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscription->start_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $subscription->end_date->format('M d, Y') }}
                            @if($subscription->end_date < now())
                                <span class="text-red-500 text-xs font-bold">(Expired)</span>
                            @elseif($subscription->end_date <= now()->addMonths(3))
                                <span class="text-yellow-500 text-xs font-bold">(Expires Soon)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="text-primary hover:text-yellow-600 mr-3">Edit</a>
                            <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">No subscriptions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t border-gray-200">
        {{ $subscriptions->links() }}
    </div>
</div>
@endsection
