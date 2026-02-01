@extends('layouts.admin')

@section('header', 'Add New Subscription')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Add New Subscription</h3>
        
        <form method="POST" action="{{ route('admin.subscriptions.store') }}">
            @csrf

            <div class="mb-4" x-data="{
                open: false,
                search: '',
                selectedId: '{{ old('added_by') }}',
                selectedName: '',
                options: [
                    @foreach($employees as $emp)
                        { id: '{{ $emp->id }}', name: '{{ addslashes($emp->name) }} ({{ $emp->email }})' },
                    @endforeach
                ],
                init() {
                    if (this.selectedId) {
                        const selected = this.options.find(o => o.id == this.selectedId);
                        if (selected) this.selectedName = selected.name;
                    }
                },
                get filteredOptions() {
                    return this.options.filter(option => 
                        option.name.toLowerCase().includes(this.search.toLowerCase())
                    );
                },
                select(option) {
                    this.selectedId = option.id;
                    this.selectedName = option.name;
                    this.open = false;
                    this.search = '';
                },
                clear() {
                    this.selectedId = '';
                    this.selectedName = '';
                    this.open = false;
                }
            }">
                <label for="added_by" class="block text-sm font-medium text-gray-700 mb-2">Added By (Employee)</label>
                <input type="hidden" name="added_by" :value="selectedId">
                
                <div class="relative" @click.away="open = false">
                    <div @click="open = !open" 
                         class="w-full rounded-md border border-gray-300 shadow-sm p-2 bg-white cursor-pointer flex justify-between items-center h-10 focus:ring-2 focus:ring-primary">
                        <span x-text="selectedName || 'Search Employee (Leave empty for Admin)'" :class="{'text-gray-500': !selectedName, 'text-black': selectedName}"></span>
                        <div class="flex items-center">
                            <span x-show="selectedName" @click.stop="clear()" class="mr-2 text-gray-400 hover:text-gray-600 cursor-pointer">&times;</span>
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
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
                        <div x-show="filteredOptions.length === 0" class="p-2 text-gray-500 text-sm">No results found.</div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Select an employee to add this subscription on their behalf. If left empty, it will be assigned to you.</p>
            </div>

            <div class="mb-4">
                <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">Client Name</label>
                <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('client_name') border-red-500 @enderror">
                @error('client_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-2">Client Phone (Optional)</label>
                <input type="text" name="client_phone" id="client_phone" value="{{ old('client_phone') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('client_phone') border-red-500 @enderror">
                @error('client_phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="plan" class="block text-sm font-medium text-gray-700 mb-2">Plan</label>
                <select name="plan" id="plan" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('plan') border-red-500 @enderror">
                    <option value="">Select Plan</option>
                    <option value="basic" {{ old('plan') == 'basic' ? 'selected' : '' }}>Basic</option>
                    <option value="premium" {{ old('plan') == 'premium' ? 'selected' : '' }}>Premium</option>
                    <option value="vip" {{ old('plan') == 'vip' ? 'selected' : '' }}>VIP</option>
                </select>
                @error('plan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('amount') border-red-500 @enderror">
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (Months)</label>
                <input type="number" name="duration" id="duration" value="{{ old('duration') }}" placeholder="e.g., 1, 3, 12" required min="1"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('duration') border-red-500 @enderror">
                @error('duration')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date (Optional - Defaults to Today)</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('start_date') border-red-500 @enderror">
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date (Optional - Auto-calculated)</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('end_date') border-red-500 @enderror">
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.subscriptions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-dark font-semibold py-2 px-4 rounded">
                    Cancel
                </a>
                <button type="submit" class="bg-primary hover:bg-yellow-500 text-dark font-semibold py-2 px-4 rounded">
                    Add Subscription
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
