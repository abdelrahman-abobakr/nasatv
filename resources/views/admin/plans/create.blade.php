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

                <!-- Status & Capacity Group -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                    <div>
                        <label for="simultaneous_devices" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Multi-Device Access</label>
                        <input type="number" name="simultaneous_devices" id="simultaneous_devices" value="{{ old('simultaneous_devices', 1) }}" min="1"
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('simultaneous_devices') border-red-500 @enderror"
                               placeholder="e.g. 2">
                        @error('simultaneous_devices')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- Content Counts -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="channels" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Live Channels</label>
                        <input type="number" name="channels" id="channels" value="{{ old('channels') }}"
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary px-3 py-2 text-sm" placeholder="1000+">
                    </div>
                    <div>
                        <label for="movies" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">VOD Movies</label>
                        <input type="number" name="movies" id="movies" value="{{ old('movies') }}"
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary px-3 py-2 text-sm" placeholder="5000+">
                    </div>
                    <div>
                        <label for="series" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">VOD Series</label>
                        <input type="number" name="series" id="series" value="{{ old('series') }}"
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary px-3 py-2 text-sm" placeholder="200+">
                    </div>
                </div>

                <!-- Technical Specs -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="max_resolution" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Max Resolution</label>
                        <select name="max_resolution" id="max_resolution"
                                class="w-full rounded-lg border-gray-200 shadow-sm focus:border-primary px-3 py-2 text-sm bg-white cursor-pointer">
                            <option value="">Select Resolution...</option>
                            <option value="SD" {{ old('max_resolution') == 'SD' ? 'selected' : '' }}>SD (480p)</option>
                            <option value="HD" {{ old('max_resolution') == 'HD' ? 'selected' : '' }}>HD (720p)</option>
                            <option value="FHD" {{ old('max_resolution') == 'FHD' ? 'selected' : '' }}>Full HD (1080p)</option>
                            <option value="4K" {{ old('max_resolution') == '4K' ? 'selected' : '' }}>Ultra HD (4K)</option>
                            <option value="8K" {{ old('max_resolution') == '8K' ? 'selected' : '' }}>8K Resolution</option>
                        </select>
                    </div>
                </div>

                <!-- Feature Booleans -->
                <div class="bg-gray-50/50 p-6 rounded-xl border border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                    <div class="flex items-center justify-between group">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-tight">Match Recording (DVR)</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="match_recording" value="1" {{ old('match_recording') ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between group">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-tight">Multi-Audio & Subtitles</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="multi_audio_subtitles" value="1" {{ old('multi_audio_subtitles') ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between group">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-tight">24/7 Premium Support</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="support_24_7" value="1" {{ old('support_24_7') ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between group">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-tight">Instant Activation</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="instant_activation" value="1" {{ old('instant_activation') ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>

                <!-- Dynamic Extras with Alpine.js -->
                <div x-data="{ 
                    extras: {{ json_encode(old('extras', [])) }},
                    addExtra() { this.extras.push('') },
                    removeExtra(index) { this.extras.splice(index, 1) }
                }">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Custom Features & Extras</label>
                    <div class="space-y-3">
                        <template x-for="(item, index) in extras" :key="index">
                            <div class="flex items-center space-x-2">
                                <input type="text" :name="'extras['+index+']'" x-model="extras[index]"
                                       class="flex-1 rounded-lg border-gray-200 shadow-sm focus:border-primary focus:ring-0 text-sm py-2"
                                       placeholder="e.g. Free VPN included">
                                <button type="button" @click="removeExtra(index)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="addExtra()" class="text-xs font-black text-primary uppercase tracking-widest flex items-center hover:text-yellow-600 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" /></svg>
                            Add Benefit
                        </button>
                    </div>
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
