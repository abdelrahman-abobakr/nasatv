@extends('layouts.admin')

@section('header', 'Plans Management')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 bg-gray-50/50">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Subscription Packages</h3>
            <p class="text-xs text-gray-500">Configure and manage available plans for clients</p>
        </div>
        <a href="{{ route('admin.plans.create') }}" class="w-full sm:w-auto bg-primary hover:bg-yellow-500 text-dark font-bold py-2.5 px-6 rounded-lg transition-all shadow-sm hover:shadow-md flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create New Plan
        </a>
    </div>

    <!-- Plans Table (Desktop) -->
    <div class="hidden md:block">
        <table class="min-w-full divide-y divide-gray-100">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Plan Name</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Monthly Cost</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Duration</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Visibility</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($plans as $plan)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900 capitalize">{{ $plan->name }}</div>
                            <div class="text-[10px] text-gray-400 uppercase tracking-tighter">ID: #{{ $plan->id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-black text-emerald-600">${{ number_format($plan->price, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                            {{ $plan->duration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $plan->status == 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($plan->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.plans.edit', $plan) }}" class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-all" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Delete this plan entirely? This may affect existing subscriptions.');">
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
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">No packages configured yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Plans Cards (Mobile) -->
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($plans as $plan)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-sm font-black text-gray-900 uppercase tracking-wide">{{ $plan->name }}</h4>
                        <div class="flex items-baseline mt-1 text-xs text-gray-500 font-medium">
                            {{ $plan->duration }}
                        </div>
                    </div>
                    <span class="px-2 py-0.5 inline-flex text-[10px] font-black uppercase rounded-full {{ $plan->status == 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $plan->status }}
                    </span>
                </div>
                <div class="flex justify-between items-center mb-6">
                    <span class="text-xl font-black text-emerald-600">${{ number_format($plan->price, 2) }}</span>
                    <div class="flex space-x-6">
                        <a href="{{ route('admin.plans.edit', $plan) }}" class="text-primary font-black text-[10px] uppercase tracking-widest">Manage</a>
                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Delete plan?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 font-black text-[10px] uppercase tracking-widest">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-gray-400 italic font-medium">No plans found</div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t border-gray-200">
        {{ $plans->links() }}
    </div>
</div>
@endsection
