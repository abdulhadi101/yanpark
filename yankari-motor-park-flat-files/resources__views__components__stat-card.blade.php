{{-- File path: resources/views/components/stat-card.blade.php --}}
@props(['label', 'value'])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-slate-200 p-4']) }}>
    <p class="text-xs uppercase tracking-wide text-slate-400">{{ $label }}</p>
    <p class="text-xl font-semibold text-slate-800 mt-1">{{ $value }}</p>
</div>
