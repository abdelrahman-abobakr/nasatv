@extends('layouts.admin')

@section('header', 'Users Management')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 bg-gray-50/50">
        <div>
            <h3 class="text-lg font-bold text-gray-800">User Directory</h3>
            <p class="text-xs text-gray-500">Manage all registered users and their roles</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="w-full sm:w-auto bg-primary hover:bg-yellow-500 text-dark font-bold py-2.5 px-6 rounded-lg transition-all shadow-sm hover:shadow-md flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New User
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="p-6 border-b border-gray-100 bg-white">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email..." 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-primary focus:border-primary sm:text-sm">
            </div>
            
            <div class="flex gap-4">
                <select name="role" class="flex-1 md:w-48 border border-gray-200 rounded-lg focus:ring-primary focus:border-primary px-3 py-2 sm:text-sm">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                </select>

                <select name="status" class="flex-1 md:w-48 border border-gray-200 rounded-lg focus:ring-primary focus:border-primary px-3 py-2 sm:text-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                
                <button type="submit" class="bg-dark hover:bg-gray-800 text-light font-bold py-2 px-6 rounded-lg transition-all">
                    Search
                </button>
            </div>
            
            @if(request('search') || request('role') || request('status'))
                <a href="{{ route('admin.users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2 px-6 rounded-lg text-center transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Users Table (Desktop) -->
    <div class="hidden lg:block">
        <table class="min-w-full divide-y divide-gray-100">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">User Details</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Access Level</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Joined On</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $user->role == 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $user->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 {{ $user->status == 'active' ? 'text-orange-500 hover:bg-orange-50' : 'text-green-500 hover:bg-green-50' }} rounded-lg transition-all" title="{{ $user->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                            @if($user->status == 'active')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-all" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">No users found match your search</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Users Cards (Mobile) -->
    <div class="lg:hidden divide-y divide-gray-100">
        @forelse($users as $user)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center">
                        <div class="h-10 w-10 flex-shrink-0 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-bold text-gray-900">{{ $user->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end space-y-2">
                        <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full {{ $user->role == 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full {{ $user->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-50 mt-4">
                    <span class="text-xs text-gray-400 font-medium">Added {{ $user->created_at->format('M d, Y') }}</span>
                    <div class="flex space-x-3 items-center">
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="font-bold text-xs uppercase tracking-wider {{ $user->status == 'active' ? 'text-orange-500' : 'text-green-500' }}">
                                    {{ $user->status == 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-primary font-bold text-xs uppercase tracking-wider">Edit</a>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 font-bold text-xs uppercase tracking-wider">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-gray-400 italic">No users found</div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t border-gray-100 bg-gray-50/30">
        {{ $users->links() }}
    </div>
</div>
@endsection
