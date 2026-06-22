{{--
    Stat Card Component
    Props:
      $title      — label (e.g. "Total Revenue")
      $value      — main number / text
      $icon       — lucide icon name (e.g. "dollar-sign")
      $color      — tailwind color key: green | red | blue | yellow | purple | cyan | orange
      $href       — optional link to make the card clickable
      $clickable  — boolean, adds hover scale effect (used with @click)
--}}

@props([
    'title'     => '',
    'value'     => '',
    'icon'      => 'activity',
    'color'     => 'blue',
    'href'      => null,
    'clickable' => false,
])

@php
    $colorMap = [
        'green'  => ['bg' => 'bg-green-100',  'text' => 'text-green-600',  'card' => ''],
        'red'    => ['bg' => 'bg-red-100',    'text' => 'text-red-600',    'card' => ''],
        'blue'   => ['bg' => 'bg-blue-100',   'text' => 'text-blue-600',   'card' => ''],
        'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'card' => ''],
        'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'card' => ''],
        'cyan'   => ['bg' => 'bg-cyan-100',   'text' => 'text-cyan-600',   'card' => ''],
        'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'card' => ''],

        // Solid variants (used in maintenance lorry cards)
        'green-solid'  => ['bg' => 'bg-green-500',  'text' => 'text-white', 'card' => 'bg-green-500 text-white'],
        'yellow-solid' => ['bg' => 'bg-yellow-500', 'text' => 'text-white', 'card' => 'bg-yellow-500 text-white'],
        'red-solid'    => ['bg' => 'bg-red-500',    'text' => 'text-white', 'card' => 'bg-red-500 text-white'],
    ];

    $c         = $colorMap[$color] ?? $colorMap['blue'];
    $isSolid   = str_ends_with($color, '-solid');
    $baseClass = $isSolid
        ? "rounded-xl p-4 shadow-lg {$c['card']} " . ($clickable ? 'cursor-pointer hover:scale-[1.02] transition' : '')
        : "bg-white rounded-xl shadow p-4 " . ($clickable ? 'cursor-pointer hover:shadow-md transition' : '');
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $baseClass }}">
@elseif($clickable)
    <div {{ $attributes }} class="{{ $baseClass }}">
@else
    <div class="{{ $baseClass }}">
@endif

    <div class="flex items-center justify-between">
        <div>
            <p class="{{ $isSolid ? 'text-sm opacity-90' : 'text-sm text-gray-500' }}">{{ $title }}</p>
            <h2 class="{{ $isSolid ? 'text-2xl font-bold' : 'text-2xl font-bold' }}">{{ $value }}</h2>
        </div>
        <div class="{{ $isSolid ? 'opacity-80' : "p-3 {$c['bg']} {$c['text']} rounded-lg" }}">
            <i data-lucide="{{ $icon }}" class="{{ $isSolid ? 'w-10 h-10' : 'w-6 h-6' }}"></i>
        </div>
    </div>

    {{ $slot ?? '' }}

@if($href)
    </a>
@else
    </div>
@endif
