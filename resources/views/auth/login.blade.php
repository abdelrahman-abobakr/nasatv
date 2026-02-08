<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-white tracking-tight">Welcome Back</h2>
        <p class="text-gray-400 mt-2 text-sm">Please enter your credentials to access your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6 px-4 py-3 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400 text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1">
            <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Email Address</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-500 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com"
                       class="block w-full pl-10 pr-3 py-3 bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1 ml-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <div class="flex justify-between items-center ml-1">
                <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-bold text-primary hover:text-yellow-500 uppercase tracking-wider transition-colors" href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-500 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                       class="block w-full pl-10 pr-3 py-3 bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 ml-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center ml-1">
            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-700 bg-gray-800 text-primary focus:ring-primary focus:ring-offset-gray-900 transition-colors">
            <label for="remember_me" class="ms-2 text-sm text-gray-400 font-medium cursor-pointer select-none">Stay signed in</label>
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-[0_10px_20px_-5px_rgba(255,188,14,0.3)] text-sm font-bold text-dark bg-primary hover:bg-yellow-500 hover:shadow-[0_15px_30px_-5px_rgba(255,188,14,0.4)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0">
                Log In to NasaTV
            </button>
        </div>
    </form>
</x-guest-layout>
