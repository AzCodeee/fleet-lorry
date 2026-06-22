{{--
    Badge Component
    Props:
      $status — the status string; automatically maps to a colour

    Supported statuses (case-insensitive):
      active / completed / success / valid
      pending / in repair / in_repair
      failed / expired / inactive / delayed / cancelled
      ongoing / in progress / in_progress
      new
      scheduled / scheduled
      draft
      info / default
--}}

@props(['status' => ''])

@php
    $map = [
        // green
        'active'      => 'bg-green-100 text-green-800',
        'completed'   => 'bg-green-100 text-green-800',
        'success'     => 'bg-green-100 text-green-800',
        'valid'       => 'bg-green-100 text-green-800',
        'approved'    => 'bg-green-100 text-green-800',

        // yellow
        'pending'     => 'bg-yellow-100 text-yellow-800',
        'in repair'   => 'bg-yellow-100 text-yellow-800',
        'in_repair'   => 'bg-yellow-100 text-yellow-800',
        'scheduled'   => 'bg-yellow-100 text-yellow-800',

        // red
        'failed'      => 'bg-red-100 text-red-800',
        'expired'     => 'bg-red-100 text-red-800',
        'inactive'    => 'bg-red-100 text-red-800',
        'delayed'     => 'bg-red-100 text-red-800',
        'cancelled'   => 'bg-red-100 text-red-800',
        'rejected'    => 'bg-red-100 text-red-800',

        // blue
        'new'         => 'bg-blue-100 text-blue-800',
        'open'        => 'bg-blue-100 text-blue-800',

        // cyan
        'ongoing'     => 'bg-cyan-100 text-cyan-800',
        'in progress' => 'bg-cyan-100 text-cyan-800',
        'in_progress' => 'bg-cyan-100 text-cyan-800',

        // purple
        'schedule'    => 'bg-purple-100 text-purple-800',

        // orange
        'draft'       => 'bg-orange-100 text-orange-800',

        // priority
        'high'        => 'bg-red-100 text-red-800',
        'medium'      => 'bg-yellow-100 text-yellow-800',
        'low'         => 'bg-gray-100 text-gray-700',
    ];

    $key   = strtolower(trim($status));
    $class = $map[$key] ?? 'bg-gray-100 text-gray-700';
@endphp

<span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $class }}">
    {{ $status }}
</span>
