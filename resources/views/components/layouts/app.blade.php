<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Fleet Management System' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs" defer></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Chart.js (available on pages that need it) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-700">

<div x-data="{ sidebar: false }" class="min-h-screen bg-gray-100">

    {{-- MOBILE OVERLAY --}}
    <div x-show="sidebar"
         x-transition
         class="fixed inset-0 bg-black/40 z-40 lg:hidden"
         @click="sidebar = false">
    </div>

    {{-- SIDEBAR --}}
    <x-sidebar />

    {{-- MAIN WRAPPER --}}
    <div class="lg:ml-64 min-h-screen">

        {{-- HEADER --}}
        <x-header :pageTitle="$pageTitle ?? 'Fleet Management System'" />

        {{-- PAGE CONTENT --}}
        <main class="p-6 pt-20 space-y-6">
            {{ $slot }}

            <footer class="mt-10 text-xs text-gray-500 border-t pt-4">
                Copyright &copy; {{ date('Y') }} AZCode
            </footer>
        </main>

    </div>

</div>

<script>
    lucide.createIcons();
</script>

@stack('scripts')
</body>
</html>
