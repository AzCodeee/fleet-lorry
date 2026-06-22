{{--
    Header Component
    Props:
      $pageTitle  — page heading shown in the top bar
--}}

@props(['pageTitle' => 'Fleet Management System'])

<header class="fixed top-0 right-0 left-0 lg:left-64
               h-16 bg-white border-b border-gray-200
               flex items-center justify-between px-4 lg:px-6 z-30">

    <div class="flex items-center gap-3">

        {{-- Mobile hamburger --}}
        <button class="lg:hidden" @click="sidebar = true">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>

        <h1 class="text-sm lg:text-lg font-semibold uppercase">
            {{ $pageTitle }}
        </h1>

    </div>

    {{-- Profile dropdown --}}
    <div x-data="{ open: false }" class="relative">

        <button @click="open = !open" class="flex items-center gap-3 focus:outline-none">
            <img src="{{ asset('images/avatar.jpg') }}"
                 class="w-10 h-10 rounded-full border object-cover"
                 alt="Avatar"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=6366f1&color=fff'">
            <div class="text-sm text-left hidden sm:block">
                <div class="font-medium">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="text-gray-500 text-xs">{{ auth()->user()->email ?? 'admin@fleet.com' }}</div>
            </div>
        </button>

        <div x-show="open"
             @click.away="open = false"
             x-transition
             class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">

            <a href="{{ route('profile.show') }}"
               class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                <i data-lucide="user-circle" class="w-4 h-4"></i>
                Profile
            </a>

            <div class="border-t"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    Logout
                </button>
            </form>

        </div>
    </div>

</header>
