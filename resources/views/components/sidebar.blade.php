{{--
    Sidebar Component
    Auto-detects active route using request()->routeIs()
--}}

@php
    $isSettings = request()->routeIs('drivers.*')
        || request()->routeIs('lorries.*')
        || request()->routeIs('regions.*')
        || request()->routeIs('sites.*');

    $settingsOpen = $isSettings ? 'true' : 'false';
@endphp

<aside :class="sidebar ? 'translate-x-0' : '-translate-x-full'"
       class="fixed lg:translate-x-0 transition-transform duration-300 z-50
              w-64 h-screen bg-gray-100 border-r border-gray-200">

    {{-- LOGO --}}
    <div class="p-4 border-b flex items-center justify-between">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.jpeg') }}" class="h-24 object-contain" alt="Logo">
        </a>
        <button class="lg:hidden text-gray-500 hover:text-black" @click="sidebar = false">✕</button>
    </div>

    {{-- NAV --}}
    <nav class="p-2 space-y-1 overflow-y-auto h-[calc(100vh-100px)]">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                  {{ request()->routeIs('dashboard') ? 'bg-white shadow text-black font-medium' : 'hover:bg-white hover:shadow' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            Dashboard
        </a>

        {{-- Project --}}
        <a href="{{ route('projects.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                  {{ request()->routeIs('projects.*') ? 'bg-white shadow text-black font-medium' : 'hover:bg-white hover:shadow' }}">
            <i data-lucide="folder" class="w-5 h-5"></i>
            Project
        </a>

        {{-- Ticket --}}
        <a href="{{ route('tickets.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                  {{ request()->routeIs('tickets.*') ? 'bg-white shadow text-black font-medium' : 'hover:bg-white hover:shadow' }}">
            <i data-lucide="ticket" class="w-5 h-5"></i>
            Ticket
        </a>

        {{-- Maintenance --}}
        <a href="{{ route('maintenance.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                  {{ request()->routeIs('maintenance.*') ? 'bg-white shadow text-black font-medium' : 'hover:bg-white hover:shadow' }}">
            <i data-lucide="wrench" class="w-5 h-5"></i>
            Maintenance
        </a>

        {{-- Settings (collapsible) --}}
        <div x-data="{ open: {{ $settingsOpen }} }" class="space-y-1">

            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white hover:shadow transition
                           {{ $isSettings ? 'bg-white shadow text-black font-medium' : '' }}">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Settings
                <i data-lucide="chevron-down"
                   class="w-4 h-4 ml-auto transition-transform"
                   :class="{ 'rotate-180': open }">
                </i>
            </button>

            <div x-show="open" x-transition class="pl-8 space-y-1">

                <a href="{{ route('drivers.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg transition
                          {{ request()->routeIs('drivers.*') ? 'bg-white shadow text-black font-medium' : 'hover:bg-white hover:shadow' }}">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    Driver
                </a>

                <a href="{{ route('regions.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg transition
                          {{ request()->routeIs('regions.*') ? 'bg-white shadow text-black font-medium' : 'hover:bg-white hover:shadow' }}">
                    <i data-lucide="map" class="w-4 h-4"></i>
                    Region
                </a>

                <a href="{{ route('sites.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg transition
                          {{ request()->routeIs('sites.*') ? 'bg-white shadow text-black font-medium' : 'hover:bg-white hover:shadow' }}">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    Sites
                </a>

                <a href="{{ route('lorries.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg transition
                          {{ request()->routeIs('lorries.*') ? 'bg-white shadow text-black font-medium' : 'hover:bg-white hover:shadow' }}">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                    Lorry
                </a>

            </div>
        </div>

    </nav>
</aside>
