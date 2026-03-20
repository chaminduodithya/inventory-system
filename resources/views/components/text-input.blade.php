@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge(['class' => 'bg-white dark:bg-zinc-900 border-zinc-200 dark:border-zinc-800 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm text-zinc-900 dark:text-zinc-100 placeholder:text-zinc-400 dark:placeholder:text-zinc-600 text-sm py-2.5 px-4 transition-all duration-200']) }}>
