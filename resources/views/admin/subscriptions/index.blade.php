@extends('layouts.admin')

@section('header', 'Subscriptions Management')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 bg-gray-50/50">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Subscription History</h3>
            <p class="text-xs text-gray-500">Track and manage all client subscription plans</p>
        </div>
        <a href="{{ route('admin.subscriptions.create') }}" class="w-full sm:w-auto bg-primary hover:bg-yellow-500 text-dark font-bold py-2.5 px-6 rounded-lg transition-all shadow-sm hover:shadow-md flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New Subscription
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="p-6 border-b border-gray-100 bg-white">
        <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client or phone..." 
                       class="w-full border border-gray-200 rounded-lg focus:ring-primary focus:border-primary px-3 py-2 text-sm">
            </div>
            
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
                    this.selectedName = selected ? selected.name : 'All Employees';
                },
                get filteredOptions() {
                    return this.search === '' ? this.options : this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
                },
                select(option) {
                    this.selectedId = option.id;
                    this.selectedName = option.name;
                    this.open = false;
                    this.search = '';
                }
            }" class="relative" @click.away="open = false">
                <input type="hidden" name="employee" :value="selectedId">
                <div @click="open = !open" class="border border-gray-200 rounded-lg p-2 bg-white cursor-pointer flex justify-between items-center h-10 text-sm">
                    <span x-text="selectedName" class="truncate"></span>
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7" /></svg>
                </div>
                <div x-show="open" x-cloak class="absolute z-50 mt-1 w-full bg-white shadow-xl rounded-lg border border-gray-100 max-h-60 overflow-y-auto">
                    <div class="sticky top-0 bg-white p-2 border-b">
                         <input x-model="search" type="text" placeholder="Filter..." class="w-full border-gray-200 rounded-md text-xs p-2">
                    </div>
                    <template x-for="option in filteredOptions" :key="option.id">
                        <div @click="select(option)" class="p-2.5 hover:bg-gray-50 cursor-pointer text-xs font-medium border-b border-gray-50 last:border-0" :class="{'bg-primary/10 text-primary font-bold': selectedId == option.id}" x-text="option.name"></div>
                    </template>
                </div>
            </div>

            <select name="status" class="border border-gray-200 rounded-lg focus:ring-primary focus:border-primary px-3 py-2 text-sm">
                <option value="">Status: All</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="expire_soon" {{ request('status') == 'expire_soon' ? 'selected' : '' }}>Expire Soon</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            
            <div class="flex gap-2">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="flex-1 border border-gray-200 rounded-lg text-sm px-3">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="flex-1 border border-gray-200 rounded-lg text-sm px-3">
            </div>
            
            <div class="md:col-span-4 flex flex-wrap gap-2 pt-2">
                <button type="submit" class="flex-1 md:flex-initial bg-dark hover:bg-gray-800 text-light font-bold py-2 px-8 rounded-lg transition-all">
                    Filter Results
                </button>
                <button type="submit" formaction="{{ route('admin.subscriptions.export') }}" class="flex-1 md:flex-initial bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-8 rounded-lg transition-all flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Data
                </button>
                @if(request()->anyFilled(['search', 'employee', 'status', 'start_date', 'end_date']))
                    <a href="{{ route('admin.subscriptions.index') }}" class="w-full md:w-auto bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2 px-6 rounded-lg text-center transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Subscriptions Table (Desktop) -->
    <div class="hidden xl:block">
        <table class="min-w-full divide-y divide-gray-100">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Client</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Plan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Revenue Details</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Period</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($subscriptions as $subscription)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $subscription->client_name }}</div>
                            <div class="text-xs text-gray-500">{{ $subscription->client_phone ?? 'No Phone' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full 
                                {{ $subscription->plan === 'vip' ? 'bg-indigo-100 text-indigo-700' : 
                                   ($subscription->plan === 'premium' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ strtoupper($subscription->plan) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-emerald-600">${{ number_format($subscription->amount, 2) }}</div>
                            <div class="text-xs text-gray-400">By {{ $subscription->addedBy->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="text-gray-900 font-medium">{{ $subscription->start_date->format('M d, Y') }}</div>
                            <div class="flex items-center text-xs mt-1">
                                <span class="{{ $subscription->end_date < now() ? 'text-red-500' : 'text-gray-400' }}">
                                    Expires: {{ $subscription->end_date->format('M d, Y') }}
                                </span>
                                @if($subscription->end_date < now())
                                    <span class="ml-2 px-1.5 py-0.5 rounded bg-red-100 text-red-700 text-[10px] font-black uppercase">Expired</span>
                                @elseif($subscription->end_date <= now()->addMonths(3))
                                    <span class="ml-2 px-1.5 py-0.5 rounded bg-amber-100 text-amber-700 text-[10px] font-black uppercase">Soon</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex justify-end space-x-1">
                                <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-all" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" class="inline" onsubmit="return confirm('Archive this subscription record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic font-medium">No subscription records found match your criteria</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Subscriptions Cards (Mobile/Tablet) -->
    <div class="xl:hidden divide-y divide-gray-100">
        @forelse($subscriptions as $subscription)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-base font-bold text-gray-900">{{ $subscription->client_name }}</h4>
                        <p class="text-xs text-gray-500 font-medium tracking-wide uppercase">{{ $subscription->client_phone ?? 'No Contact' }}</p>
                    </div>
                    <span class="px-2.5 py-0.5 text-[10px] font-black tracking-widest uppercase rounded bg-gray-100 text-gray-600 border border-gray-200">
                        {{ $subscription->plan }}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-y-4 gap-x-2 pb-5 border-b border-gray-50 mb-4">
                    <div class="p-2 bg-gray-50 rounded-lg">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Amount Paid</p>
                        <p class="text-sm font-black text-emerald-600">${{ number_format($subscription->amount, 2) }}</p>
                    </div>
                    <div class="p-2 bg-gray-50 rounded-lg">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Term Length</p>
                        <p class="text-sm font-bold text-gray-700">{{ $subscription->duration }} Mo.</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Starts</p>
                        <p class="text-xs font-medium text-gray-600 uppercase">{{ $subscription->start_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Expires</p>
                        <p class="text-xs font-bold {{ $subscription->end_date < now() ? 'text-red-500' : 'text-gray-800' }} uppercase">
                            {{ $subscription->end_date->format('M d, Y') }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400 font-medium">By {{ $subscription->addedBy->name }}</span>
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="text-primary font-bold uppercase tracking-widest text-[10px]">Edit</a>
                        <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" class="inline" onsubmit="return confirm('Archive record?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 font-bold uppercase tracking-widest text-[10px]">Archive</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-gray-400 italic font-medium">No records found</div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t border-gray-100 bg-gray-50/30">
        {{ $subscriptions->links() }}
    </div>
</div>
@endsection
