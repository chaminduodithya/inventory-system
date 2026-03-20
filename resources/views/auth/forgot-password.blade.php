<x-guest-layout>
    <div class="space-y-8">
        <!-- Header: Credential Recovery -->
        <div class="space-y-1.5">
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white tracking-tight">Access Recovery</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Initialize credential reset protocol via communication
                node.</p>
        </div>

        <div class="p-4 bg-zinc-50 dark:bg-zinc-900/50 border border-zinc-100 dark:border-zinc-800 rounded-lg">
            <p class="text-[11px] leading-relaxed text-zinc-600 dark:text-zinc-400 font-medium">
                {{ __('Forgot your password? No problem. Provide your registered identity identifier and we will dispatch a verification link to reset your access hash.') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status
            class="text-center bg-emerald-500/5 border border-emerald-500/10 p-3 rounded text-[11px] font-bold text-emerald-600"
            :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Identity
                    Identifier</label>
                <div class="relative group">
                    <div
                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 group-focus-within:text-brand-500 transition-colors">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="saas-input pl-10 block w-full" placeholder="nexus-id@enterprise.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="text-rose-500 text-[10px] font-bold" />
            </div>

            <div class="pt-2 flex flex-col gap-3">
                <button type="submit"
                    class="saas-btn-primary w-full py-4 text-[11px] uppercase tracking-[0.2em] font-black group">
                    Dispatch Reset Protocol
                    <i data-lucide="send"
                        class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                </button>

                <div class="text-center">
                    <a class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 hover:text-brand-600 transition-colors"
                        href="{{ route('login') }}">
                        Return to Authentication Gateway
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>
