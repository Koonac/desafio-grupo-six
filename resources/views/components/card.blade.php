@props([
'title',
'value' => null,
'icon' => null,
'class' => ''
])

@php
$baseClasses = "bg-white/10 backdrop-blur-md rounded-xl shadow-2xl border border-white/25 p-6 transition-all duration-300 hover:bg-white/15 hover:shadow-3xl";
$classes = trim($baseClasses . ' ' . $class);
@endphp

<div class="{{ $classes }}">
    @if($icon)
    <div class="flex items-center justify-between mb-2">
        <h3 class="text-sm font-medium text-white/80 uppercase tracking-wider">{{ $title }}</h3>
        <div class="text-white/60">
            {!! $icon !!}
        </div>
    </div>
    @else
    <h3 class="text-sm font-medium text-white/80 uppercase tracking-wider mb-2">{{ $title }}</h3>
    @endif
    @if($value)
    <p class="text-3xl sm:text-4xl font-bold text-white">{{ $value }}</p>
    @endif
    {{ $slot }}
</div>
