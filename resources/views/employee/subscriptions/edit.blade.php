@extends('layouts.employee')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Edit Subscription</h3>
        
        <form method="POST" action="{{ route('employee.subscriptions.update', $subscription) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">Client Name</label>
                <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $subscription->client_name) }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('client_name') border-red-500 @enderror">
                @error('client_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-2">Client Phone (Optional)</label>
                <input type="text" name="client_phone" id="client_phone" value="{{ old('client_phone', $subscription->client_phone) }}"
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
                    <option value="basic" {{ old('plan', $subscription->plan) == 'basic' ? 'selected' : '' }}>Basic</option>
                    <option value="premium" {{ old('plan', $subscription->plan) == 'premium' ? 'selected' : '' }}>Premium</option>
                    <option value="vip" {{ old('plan', $subscription->plan) == 'vip' ? 'selected' : '' }}>VIP</option>
                </select>
                @error('plan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $subscription->amount) }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('amount') border-red-500 @enderror">
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (Months)</label>
                <input type="number" name="duration" id="duration" value="{{ old('duration', $subscription->duration) }}" placeholder="e.g., 1, 3, 12" required min="1"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('duration') border-red-500 @enderror">
                @error('duration')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $subscription->start_date->format('Y-m-d')) }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('start_date') border-red-500 @enderror">
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $subscription->end_date->format('Y-m-d')) }}" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('end_date') border-red-500 @enderror">
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('employee.subscriptions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-dark font-semibold py-2 px-4 rounded">
                    Cancel
                </a>
                <button type="submit" class="bg-primary hover:bg-yellow-500 text-dark font-semibold py-2 px-4 rounded">
                    Update Subscription
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const durationInput = document.getElementById('duration');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        function calculateEndDate() {
            const duration = parseInt(durationInput.value);
            const startDate = new Date(startDateInput.value);

            if (!isNaN(duration) && !isNaN(startDate.getTime())) {
                const endDate = new Date(startDate);
                endDate.setMonth(endDate.getMonth() + duration);
                
                // Format to YYYY-MM-DD
                const yyyy = endDate.getFullYear();
                const mm = String(endDate.getMonth() + 1).padStart(2, '0');
                const dd = String(endDate.getDate()).padStart(2, '0');
                
                endDateInput.value = `${yyyy}-${mm}-${dd}`;
            }
        }

        durationInput.addEventListener('input', calculateEndDate);
        startDateInput.addEventListener('change', calculateEndDate);
    });
</script>
@endpush
