@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'required' => false,
])

<div {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="block text-xs tracking-[0.12em] uppercase text-[var(--text-dim)] mb-2">
        {{ $label }}@if ($required)<span class="text-[var(--color-gold)]"> *</span>@endif
    </label>
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        @required($required)
        {{ $attributes->except('class') }}
        @class([
            'w-full bg-transparent border px-4 py-3 text-sm focus:outline-none transition-colors',
            'border-[var(--line)] focus:border-[var(--color-gold)]' => !$errors->has($name),
            'border-red-500/60 focus:border-red-500' => $errors->has($name),
        ])
    >
    @error($name)
        <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
    @enderror
</div>
