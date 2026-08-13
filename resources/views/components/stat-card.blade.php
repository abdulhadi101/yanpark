@props(['label', 'value', 'href' => null])

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'block bg-white rounded-xl border border-slate-200 p-4 hover:border-slate-300 hover:shadow-sm transition']) }}>
        <p class="text-xs uppercase tracking-wide text-slate-400">{{ $label }}</p>
        <p class="text-xl font-semibold text-slate-800 mt-1">{{ $value }}</p>
    </a>
@else
    <div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-slate-200 p-4']) }}>
        <p class="text-xs uppercase tracking-wide text-slate-400">{{ $label }}</p>
        <p class="text-xl font-semibold text-slate-800 mt-1">{{ $value }}</p>
    </div>
@endif