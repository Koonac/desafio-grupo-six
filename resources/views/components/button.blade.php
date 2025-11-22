@props([
'text',
'href' => null,
'type' => 'button',
'class' => ''
])

@php
$baseClasses = "px-6 py-3 bg-[#0f75bd] hover:bg-[#2877f7] hover:scale-105 text-white rounded-lg transition-all duration-300 font-semibold shadow-lg border border-white/20 backdrop-blur-sm";
$classes = trim($baseClasses . ' ' . $class);
@endphp

@if($href)
<a href="{{ $href }}" class="{{ $classes }}">
    {{ $text }}
</a>
@else
<button type="{{ $type }}" class="{{ $classes }}">
    {{ $text }}
</button>
@endif
