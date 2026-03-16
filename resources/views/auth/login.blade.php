<x-guest-layout>
    <div class="w-full max-w-[440px] relative z-10 w-full">
        <!-- Logo & Title -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-xl shadow-indigo-500/30 mx-auto mb-6 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                <i data-lucide="package" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-indigo-900 dark:from-white dark:to-indigo-200 tracking-tight">
                Welcome back
            </h1>
            <p class="mt-3 text-slate-600 dark:text-slate-400 font-medium">
                Enter your details to access your account.
            </p>
        </div>

        <!-- Login Card -->
        <div class="relative group">
            <!-- Subtle border pulse effect -->
            <div class="absolute -inset-[1px] bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-[1.5rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
            
            <div class="relative backdrop-blur-xl bg-white/90 dark:bg-slate-900/90 shadow-2xl rounded-3xl overflow-hidden ring-1 ring-white/50 dark:ring-white/10">
                <div class="p-8 sm:p-10 relative">
                    <!-- Decorative inner glow -->
                    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-indigo-500/10 blur-2xl pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 rounded-full bg-purple-500/10 blur-2xl pointer-events-none"></div>

                    <x-auth-session-status class="mb-6 text-center" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="relative z-10">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 mt-0">Email Address</label>
                            <div class="relative group/input">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                </div>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autofocus 
                                    autocomplete="username" 
                                    class="pl-11 block w-full rounded-xl border-slate-200 dark:border-slate-700/60 bg-white/50 dark:bg-slate-950/50 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all sm:text-sm placeholder-slate-400 py-3 appearance-none outline-none"
                                    placeholder="you@example.com"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500 text-sm" />
                        </div>

                        <!-- Password -->
                        <div class="mt-6">
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>
                            <div class="relative group/input" x-data="{ show: false }">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                    <i data-lucide="lock" class="w-5 h-5"></i>
                                </div>
                                <input 
                                    id="password" 
                                    x-bind:type="show ? 'text' : 'password'" 
                                    name="password" 
                                    required 
                                    autocomplete="current-password" 
                                    class="pl-11 pr-11 block w-full rounded-xl border-slate-200 dark:border-slate-700/60 bg-white/50 dark:bg-slate-950/50 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all sm:text-sm placeholder-slate-400 py-3 appearance-none outline-none"
                                    placeholder="••••••••"
                                />
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors focus:outline-none">
                                    <i data-lucide="eye" x-show="!show" class="w-5 h-5"></i>
                                    <i data-lucide="eye-off" x-show="show" x-cloak class="w-5 h-5"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500 text-sm" />
                        </div>

                        <!-- Remember Me -->
                        <div class="mt-5 flex items-center">
                            <label for="remember_me" class="flex items-center cursor-pointer group/check">
                                <div class="relative flex items-center justify-center">
                                    <input id="remember_me" type="checkbox" class="peer appearance-none w-5 h-5 border-2 border-slate-300 dark:border-slate-600 rounded-md bg-transparent checked:bg-indigo-600 border-solid focus:ring-indigo-500 focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all shadow-sm cursor-pointer" name="remember">
                                    <i data-lucide="check" class="absolute w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></i>
                                </div>
                                <span class="ml-2.5 text-sm font-medium text-slate-600 dark:text-slate-400 group-hover/check:text-slate-900 dark:group-hover/check:text-slate-200 transition-colors">
                                    Keep me logged in
                                </span>
                            </label>
                        </div>

                        <!-- Submit -->
                        <div class="mt-8">
                            <button type="submit" class="w-full relative group/btn overflow-hidden rounded-xl bg-slate-900 dark:bg-indigo-600 px-4 py-3.5 text-sm font-bold text-white shadow-[0_0_40px_-10px_rgba(0,0,0,0.4)] dark:shadow-[0_0_40px_-10px_rgba(79,70,229,0.5)] transition-all hover:scale-[1.02] active:scale-[0.98] outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                                <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-indigo-500/50 to-purple-500/50 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                                <span class="relative flex items-center justify-center gap-2 tracking-wide">
                                    Sign In
                                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform"></i>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Register Link -->
            @if (Route::has('register'))
                <p class="mt-8 text-center text-sm text-slate-600 dark:text-slate-400 font-medium">
                    New to StockPro? 
                    <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 hover:underline underline-offset-4 transition-all">
                        Create an account
                    </a>
                </p>
            @endif
        </div>
    </div>
</x-guest-layout>