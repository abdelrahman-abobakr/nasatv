@extends('layouts.admin')

@section('header', 'Create New Plan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">Create New Subscription Tier</h3>
            <p class="text-xs text-gray-500">Define a new service package with pricing and duration</p>
        </div>
        <div class="p-8">
            <form method="POST" action="{{ route('admin.plans.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Plan Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Package Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('name') border-red-500 @enderror"
                               placeholder="e.g. Platinum Plus">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Monthly Rate</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 font-bold">$</span>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" required
                                   class="w-full pl-7 rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('price') border-red-500 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Service Duration</label>
                    <input type="text" name="duration" id="duration" value="{{ old('duration') }}" placeholder="e.g. 1 Month, 12 Months, Lifetime" required
                           class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('duration') border-red-500 @enderror">
                    <p class="mt-1 text-[10px] text-gray-400 italic">This is a display label for the plan duration</p>
                    @error('duration')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Package Description (Optional)</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('description') border-red-500 @enderror"
                              placeholder="Briefly describe the benefits of this tier...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Package Visibility</label>
                    <div class="relative">
                        <select name="status" id="status" required
                                class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 appearance-none bg-white pr-10 @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Publicly Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Hidden / Internal</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="{{ route('admin.plans.index') }}" class="text-gray-400 hover:text-gray-600 font-bold text-sm transition-colors">
                        Cancel & Return
                    </a>
                    <button type="submit" class="bg-dark hover:bg-gray-800 text-light font-bold py-3 px-8 rounded-xl transition-all shadow-sm hover:shadow-md">
                        Publish Package
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
