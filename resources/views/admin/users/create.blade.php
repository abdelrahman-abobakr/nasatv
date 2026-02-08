@extends('layouts.admin')

@section('header', 'Create New User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">Add New System User</h3>
            <p class="text-xs text-gray-500">Create a new account with specific access privileges</p>
        </div>
        <div class="p-8">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf

                <!-- Name & Email Group -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('name') border-red-500 @enderror"
                               placeholder="e.g. John Doe">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('email') border-red-500 @enderror"
                               placeholder="john@example.com">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Role & Status Group -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="role" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Access Level</label>
                        <div class="relative">
                            <select name="role" id="role" required
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 appearance-none bg-white pr-10 @error('role') border-red-500 @enderror">
                                <option value="">Choose a role...</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                        @error('role')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Account Status</label>
                        <div class="relative">
                            <select name="status" id="status" required
                                    class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 appearance-none bg-white pr-10 @error('status') border-red-500 @enderror">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- Password Group -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                               placeholder="••••••••">
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600 font-bold text-sm transition-colors">
                        Cancel & Return
                    </a>
                    <button type="submit" class="bg-dark hover:bg-gray-800 text-light font-bold py-3 px-8 rounded-xl transition-all shadow-sm hover:shadow-md">
                        Generate Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
