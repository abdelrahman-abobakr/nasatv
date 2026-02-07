@extends('layouts.admin')

@section('header', 'Add New Subscription')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">New Subscription Enrollment</h3>
            <p class="text-xs text-gray-500">Register a new client and assign a revenue manager</p>
        </div>
        <div class="p-8">
            <form method="POST" action="{{ route('admin.subscriptions.store') }}" class="space-y-6">
                @csrf

                <!-- Revenue Manager Selection -->
                <div x-data="{
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
                        return this.search === '' ? this.options : this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
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
                }" class="space-y-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Select Employee</label>
                    <input type="hidden" name="added_by" :value="selectedId">
                    
                    <div class="relative" @click.away="open = false">
                        <div @click="open = !open" 
                             class="w-full rounded-lg border border-gray-200 shadow-sm p-3 bg-white cursor-pointer flex justify-between items-center min-h-[42px] transition-all focus-within:ring-2 focus-within:ring-primary/20">
                            <span x-text="selectedName || 'Choose Personnel (Defaults to you)'" :class="{'text-gray-400 font-medium': !selectedName, 'text-gray-900 font-bold': selectedName}" class="text-sm truncate"></span>
                            <div class="flex items-center">
                                <span x-show="selectedName" @click.stop="clear()" class="mr-2 text-gray-400 hover:text-gray-600 cursor-pointer p-1">&times;</span>
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>

                        <div x-show="open" x-cloak
                             class="absolute z-50 mt-1 w-full bg-white shadow-xl rounded-lg border border-gray-100 max-h-60 overflow-y-auto">
                            <div class="sticky top-0 bg-white p-2 border-b border-gray-50">
                                 <input x-model="search" type="text" placeholder="Filter employees..." 
                                        class="w-full border-gray-200 rounded-md text-xs p-2 focus:ring-primary focus:border-primary">
                            </div>
                            <template x-for="option in filteredOptions" :key="option.id">
                                <div @click="select(option)" 
                                     class="p-2.5 hover:bg-gray-50 cursor-pointer text-xs font-medium border-b border-gray-50 last:border-0"
                                     :class="{'bg-primary/10 text-primary font-bold': selectedId == option.id}"
                                     x-text="option.name">
                                </div>
                            </template>
                            <div x-show="filteredOptions.length === 0" class="p-4 text-center text-gray-400 text-xs italic">No results found</div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- Client Info Group -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Client Full Name</label>
                        <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}" required
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('client_name') border-red-500 @enderror"
                               placeholder="e.g. Acme Corp">
                        @error('client_name')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="client_phone" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Contact Phone</label>
                        <input type="text" name="client_phone" id="client_phone" value="{{ old('client_phone') }}"
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('client_phone') border-red-500 @enderror"
                               placeholder="+1 234 567 890">
                        @error('client_phone')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Plan & Financials -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="plan" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Service Plan</label>
                        <select name="plan" id="plan" required
                                class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('plan') border-red-500 @enderror">
                            <option value="">Select Plan</option>
                            <option value="basic" {{ old('plan') == 'basic' ? 'selected' : '' }}>Basic Tier</option>
                            <option value="premium" {{ old('plan') == 'premium' ? 'selected' : '' }}>Premium Tier</option>
                            <option value="vip" {{ old('plan') == 'vip' ? 'selected' : '' }}>VIP / Custom</option>
                        </select>
                        @error('plan')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Total Amount</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 font-bold">$</span>
                            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" required
                                   class="w-full pl-7 rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('amount') border-red-500 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('amount')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Term (Months)</label>
                        <input type="number" name="duration" id="duration" value="{{ old('duration') }}" placeholder="1-60" required min="1"
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('duration') border-red-500 @enderror">
                        @error('duration')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Dates Group -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <div>
                        <label for="start_date" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Commencement Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                               class="w-full border-0 bg-transparent focus:ring-0 text-sm font-bold text-gray-900 p-0 @error('start_date') text-red-600 @enderror">
                        @error('start_date')
                            <p class="mt-1 text-[10px] text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Termination Date (optional)</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                               class="w-full border-0 bg-transparent focus:ring-0 text-sm font-bold text-gray-900 p-0 @error('end_date') text-red-600 @enderror">
                        @error('end_date')
                            <p class="mt-1 text-[10px] text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="{{ route('admin.subscriptions.index') }}" class="text-gray-400 hover:text-gray-600 font-bold text-sm transition-colors">
                        Cancel & Return
                    </a>
                    <button type="submit" class="bg-dark hover:bg-gray-800 text-light font-bold py-3 px-8 rounded-xl transition-all shadow-sm hover:shadow-md">
                        Activate Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    </div>
</div>
@endsection
