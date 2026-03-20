@props(['value'])

<label
    {{ $attributes->merge(['class' => 'block text-[11px] font-bold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
