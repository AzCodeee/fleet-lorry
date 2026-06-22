{{--
    Button Component
    Props:
      $type    — primary (green) | secondary (white/border) | danger (red) | dark (gray-800)
      $icon    — lucide icon name (optional)
      $href    — renders as <a> when set
      $size    — sm | md (default) | lg
      $submit  — renders type="submit" instead of button

    Usage:
      <x-btn type="primary" icon="plus">Add Driver</x-btn>
      <x-btn type="secondary" @click="open=false">Cancel</x-btn>
      <x-btn type="danger" icon="trash-2">Delete</x-btn>
      <x-btn href="{{ route('drivers.index') }}">Back</x-btn>
--}}

@props([
    'type'   => 'secondary',
    'icon'   => null,
    'href'   => null,
    'size'   => 'md',
    'submit' => false,
])

@php
    $styles = [
        'primary'   => 'bg-green-600 text-white hover:bg-green-700',
        'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50',
        'danger'    => 'bg-red-600 text-white hover:bg-red-700',
        'dark'      => 'bg-gray-800 text-white hover:bg-gray-900',
        'outline'   => 'border border-gray-300 text-gray-700 hover:bg-white hover:shadow',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
    ];

    $base    = 'inline-flex items-center gap-2 rounded-lg font-medium transition focus:outline-none';
    $variant = $styles[$type] ?? $styles['secondary'];
    $sz      = $sizes[$size] ?? $sizes['md'];
    $class   = "$base $variant $sz";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>
        @if($icon)<i data-lucide="{{ $icon }}" class="w-4 h-4"></i>@endif
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $submit ? 'submit' : 'button' }}"
        {{ $attributes->merge(['class' => $class]) }}>
        @if($icon)<i data-lucide="{{ $icon }}" class="w-4 h-4"></i>@endif
        {{ $slot }}
    </button>
@endif
