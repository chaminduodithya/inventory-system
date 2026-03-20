<x-guest-layout>
    <div class="space-y-8">
        <!-- Header: Entity Registration -->
        <div class="space-y-1.5">
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white tracking-tight">System Registration</h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Initialize new operator credentials for the Nexus
                registry.
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div class="space-y-2">
                <label for="name" class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Operator
                    Identity</label>
                <div class="relative group">
                    <div
                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 group-focus-within:text-brand-500 transition-colors">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="saas-input pl-10 block w-full" placeholder="Full Name" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="text-rose-500 text-[10px] font-bold" />
            </div>

            <!-- Email -->
            <div class="space-y-2">
                <label for="email"
                    class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Communication
                    Node (Email)</label>
                <div class="relative group">
                    <div
                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 group-focus-within:text-brand-500 transition-colors">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="saas-input pl-10 block w-full" placeholder="email@enterprise.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="text-rose-500 text-[10px] font-bold" />
            </div>

            <!-- Password Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Access
                        Key</label>
                    <input id="password" type="password" name="password" required class="saas-input block w-full"
                        placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="text-rose-500 text-[10px] font-bold" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation"
                        class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Retry Key</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="saas-input block w-full" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="text-rose-500 text-[10px] font-bold" />
                </div>
            </div>

            <div class="pt-4 flex flex-col gap-3">
                <button type="submit"
                    class="saas-btn-primary w-full py-4 text-[11px] uppercase tracking-[0.2em] font-black group">
                    Authorize Registry Entry
                    <i data-lucide="shield-plus" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                </button>

                <div class="text-center">
                    <a class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 hover:text-brand-600 transition-colors"
                        href="{{ route('login') }}">
                        Already Authorized? Establish Connection
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>
