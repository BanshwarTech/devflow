@props([
    'label',
    'value',
    'icon' => null,
    'color' => 'indigo',
    'color-fallback' => null,
])

@php
$color = $colorFallback ?? $color;
$bgClass = match ($color) {
    'blue' => 'bg-blue-100',
    'amber' => 'bg-amber-100',
    'rose' => 'bg-rose-100',
    'emerald' => 'bg-emerald-100',
    'green' => 'bg-green-100',
    'purple' => 'bg-purple-100',
    default => 'bg-indigo-100',
};
$textClass = match ($color) {
    'blue' => 'text-blue-600',
    'amber' => 'text-amber-600',
    'rose' => 'text-rose-600',
    'emerald' => 'text-emerald-600',
    'green' => 'text-green-600',
    'purple' => 'text-purple-600',
    default => 'text-indigo-600',
};
@endphp

<div class="bg-white rounded-xl shadow p-6 flex items-center justify-between">
    <div>
        <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
        <p class="text-2xl font-semibold text-gray-800">{{ $value }}</p>
    </div>
    <div class="{{ $bgClass }} {{ $textClass }} rounded-lg p-3 text-2xl">
        {{ $icon }}
    </div>
</div>
