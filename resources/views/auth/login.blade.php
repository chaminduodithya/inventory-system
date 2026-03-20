<x-guest-layout>
    <div class="space-y-8">
        <!-- Header: System Authentication -->
        <div class="space-y-1.5">
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Access Control Center</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Initialize secure session to manage logistical registry.
            </p>
        </div>

        <!-- Status Messages -->
        <x-auth-session-status
            class="text-center bg-brand-500/5 border border-brand-500/10 p-3 rounded text-[11px] font-bold text-brand-600"
            :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email: Identity Key -->
            <div class="space-y-2">
                <label for="email" class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Identity
                    Identifier</label>
                <div class="relative group">
                    <div
                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 group-focus-within:text-brand-500 transition-colors">
                        <i data-lucide="shield-user" class="w-4 h-4"></i>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="saas-input pl-10 block w-full bg-zinc-50/50 dark:bg-zinc-900/50 border-zinc-200 dark:border-zinc-800 focus:ring-brand-500 placeholder-zinc-400"
                        placeholder="nexus-id@enterprise.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="text-rose-500 text-[10px] font-bold" />
            </div>

            <!-- Password: Verification Hash -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label for="password"
                        class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Verification
                        Hash</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-[10px] font-bold text-brand-600 hover:text-brand-500 uppercase tracking-widest transition-colors">
                            Recovery?
                        </a>
                    @endif
                </div>
                <div class="relative group" x-data="{ show: false }">
                    <div
                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 group-focus-within:text-brand-500 transition-colors">
                        <i data-lucide="key-round" class="w-4 h-4"></i>
                    </div>
                    <input id="password" :type="show ? 'text' : 'password'" name="password" required
                        class="saas-input pl-10 pr-10 block w-full bg-zinc-50/50 dark:bg-zinc-900/50 border-zinc-200 dark:border-zinc-800 focus:ring-brand-500 placeholder-zinc-400"
                        placeholder="••••••••" />
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors">
                        <i data-lucide="eye" x-show="!show" class="w-4 h-4"></i>
                        <i data-lucide="eye-off" x-show="show" x-cloak class="w-4 h-4"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="text-rose-500 text-[10px] font-bold" />
            </div>

            <!-- Temporal Persistence -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                    <div class="relative flex items-center justify-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="peer appearance-none w-4 h-4 border-2 border-zinc-300 dark:border-zinc-700 rounded bg-transparent checked:bg-brand-600 checked:border-brand-600 transition-all cursor-pointer">
                        <i data-lucide="check"
                            class="absolute w-2.5 h-2.5 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 group-hover:text-zinc-700 dark:group-hover:text-zinc-300 transition-colors">
                        Maintain Session
                    </span>
                </label>
            </div>

            <!-- Authorization Command -->
            <div class="pt-2">
                <button type="submit"
                    class="saas-btn-primary w-full py-4 text-[11px] uppercase tracking-[0.2em] font-black group">
                    Establish Connection
                    <i data-lucide="chevron-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </form>

        <!-- External Directory -->
        @if (Route::has('register'))
            <div class="pt-6 border-t border-zinc-100 dark:border-zinc-800 text-center">
                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest line-clamp-1">
                    Awaiting clearance?
                    <a href="{{ route('register') }}" class="text-brand-600 hover:text-brand-500 ml-1">
                        Apply for registry access
                    </a>
                </p>
            </div>
        @endif
    </div>
</x-guest-layout>
