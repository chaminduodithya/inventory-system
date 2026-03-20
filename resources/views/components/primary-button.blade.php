<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-brand-600 border border-brand-700/50 rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-brand-700 focus:bg-brand-700 active:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition-all duration-200 shadow-[0_4px_12px_rgba(139,92,246,0.3)] active:scale-95']) }}>
    {{ $slot }}
</button>
