<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login — Fleet Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-100 text-gray-700 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-4xl">

    {{-- Logo above card --}}
    <div class="flex justify-center mb-6">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.jpeg') }}"
                 class="h-20 object-contain"
                 alt="Fleet Management System"
                 onerror="this.style.display='none'">
        </a>
    </div>

    {{-- Two-column card --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden flex flex-col lg:flex-row min-h-[520px]">

        {{-- LEFT — Login form --}}
        <div class="w-full lg:w-1/2 p-8 lg:p-10 flex flex-col justify-center">

            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800">Sign in to your account</h2>
                <p class="text-sm text-gray-500 mt-1">Fleet Management System</p>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-blue-50 border border-blue-200 text-sm text-blue-700">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-start gap-2">
                                <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 shrink-0"></i>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" x-data="{ showPass: false }">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email Address
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="you@example.com"
                               class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                      @error('email') border-red-400 @enderror">
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-2">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input id="password"
                               :type="showPass ? 'text' : 'password'"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-2.5 text-sm border border-gray-200 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                      @error('password') border-red-400 @enderror">
                        <button type="button"
                                @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i :data-lucide="showPass ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- Forgot password --}}
                <div class="flex justify-end mb-5">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-xs text-blue-600 hover:text-blue-800 hover:underline">
                            Forgot your password?
                        </a>
                    @endif
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2 mb-6">
                    <input id="remember_me"
                           type="checkbox"
                           name="remember"
                           class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="remember_me" class="text-sm text-gray-600 select-none">
                        Remember me
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2
                               bg-gray-900 hover:bg-black active:bg-gray-800
                               text-white text-sm font-semibold
                               py-2.5 rounded-lg transition">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    Sign In
                </button>
            </form>

        </div>

        {{-- RIGHT — Illustration panel --}}
        <div class="hidden lg:flex w-1/2 bg-gray-900 items-center justify-center p-10 relative overflow-hidden">

            {{-- Background grid pattern --}}
            <div class="absolute inset-0 opacity-10"
                 style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
                                          linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px);
                        background-size: 32px 32px;">
            </div>

            {{-- Decorative blobs --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600 opacity-10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-400 opacity-10 rounded-full translate-y-1/3 -translate-x-1/3"></div>

            <div class="relative z-10 text-center">

                {{-- Fleet SVG Illustration --}}
                <svg viewBox="0 0 320 220" fill="none" xmlns="http://www.w3.org/2000/svg"
                     class="w-72 mx-auto mb-8 drop-shadow-2xl">

                    {{-- Road --}}
                    <rect x="0" y="170" width="320" height="50" fill="#1e293b" rx="4"/>
                    {{-- Road markings --}}
                    <rect x="30"  y="191" width="40" height="6" fill="#f59e0b" rx="3" opacity="0.8"/>
                    <rect x="100" y="191" width="40" height="6" fill="#f59e0b" rx="3" opacity="0.8"/>
                    <rect x="170" y="191" width="40" height="6" fill="#f59e0b" rx="3" opacity="0.8"/>
                    <rect x="240" y="191" width="40" height="6" fill="#f59e0b" rx="3" opacity="0.8"/>

                    {{-- Lorry body (cab) --}}
                    <rect x="180" y="110" width="100" height="60" fill="#3b82f6" rx="6"/>
                    {{-- Cargo box --}}
                    <rect x="40"  y="118" width="145" height="52" fill="#2563eb" rx="4"/>
                    {{-- Cab window --}}
                    <rect x="193" y="120" width="52" height="28" fill="#bfdbfe" rx="3"/>
                    <line x1="219" y1="120" x2="219" y2="148" stroke="#93c5fd" stroke-width="1.5"/>
                    {{-- Cab details --}}
                    <rect x="268" y="126" width="8" height="14" fill="#f59e0b" rx="2"/>
                    {{-- Cargo stripes --}}
                    <line x1="80"  y1="118" x2="80"  y2="170" stroke="#1d4ed8" stroke-width="1.5" opacity="0.5"/>
                    <line x1="120" y1="118" x2="120" y2="170" stroke="#1d4ed8" stroke-width="1.5" opacity="0.5"/>
                    <line x1="160" y1="118" x2="160" y2="170" stroke="#1d4ed8" stroke-width="1.5" opacity="0.5"/>

                    {{-- Wheels --}}
                    <circle cx="80"  cy="172" r="18" fill="#0f172a"/>
                    <circle cx="80"  cy="172" r="10" fill="#334155"/>
                    <circle cx="80"  cy="172" r="4"  fill="#64748b"/>

                    <circle cx="135" cy="172" r="18" fill="#0f172a"/>
                    <circle cx="135" cy="172" r="10" fill="#334155"/>
                    <circle cx="135" cy="172" r="4"  fill="#64748b"/>

                    <circle cx="210" cy="172" r="18" fill="#0f172a"/>
                    <circle cx="210" cy="172" r="10" fill="#334155"/>
                    <circle cx="210" cy="172" r="4"  fill="#64748b"/>

                    <circle cx="265" cy="172" r="18" fill="#0f172a"/>
                    <circle cx="265" cy="172" r="10" fill="#334155"/>
                    <circle cx="265" cy="172" r="4"  fill="#64748b"/>

                    {{-- Speed lines --}}
                    <line x1="10" y1="135" x2="38" y2="135" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" opacity="0.6"/>
                    <line x1="10" y1="148" x2="32" y2="148" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" opacity="0.4"/>
                    <line x1="10" y1="158" x2="25" y2="158" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" opacity="0.25"/>

                    {{-- Location pin above cab --}}
                    <circle cx="234" cy="75" r="18" fill="#3b82f6" opacity="0.2"/>
                    <path d="M234 58 C226 58 220 64 220 72 C220 82 234 94 234 94 C234 94 248 82 248 72 C248 64 242 58 234 58 Z"
                          fill="#3b82f6"/>
                    <circle cx="234" cy="72" r="5" fill="white"/>
                    {{-- Dashed line from pin to cab --}}
                    <line x1="234" y1="94" x2="234" y2="110" stroke="#3b82f6" stroke-width="1.5"
                          stroke-dasharray="4 3" opacity="0.6"/>

                    {{-- Dashboard / stats panel (top left) --}}
                    <rect x="10" y="10" width="90" height="55" fill="#1e3a5f" rx="8" opacity="0.85"/>
                    <text x="20" y="28" fill="#93c5fd" font-size="7" font-family="system-ui" font-weight="600">FLEET STATUS</text>
                    <rect x="20" y="33" width="70" height="1" fill="#3b82f6" opacity="0.4"/>
                    <text x="20" y="45" fill="#bfdbfe" font-size="6" font-family="system-ui">Active</text>
                    <text x="60" y="45" fill="#34d399" font-size="6" font-family="system-ui" font-weight="700">12</text>
                    <text x="20" y="56" fill="#bfdbfe" font-size="6" font-family="system-ui">In Repair</text>
                    <text x="60" y="56" fill="#fbbf24" font-size="6" font-family="system-ui" font-weight="700">3</text>

                </svg>

                {{-- Text below illustration --}}
                <h3 class="text-white text-lg font-bold mb-2">Fleet Management System</h3>
                <p class="text-gray-400 text-sm leading-relaxed max-w-xs mx-auto">
                    Track lorries, manage drivers, and monitor deliveries — all in one place.
                </p>

                {{-- Feature badges --}}
                <div class="flex justify-center gap-3 mt-6 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 text-gray-300 text-xs rounded-full">
                        <i data-lucide="truck" class="w-3 h-3"></i> Fleet Tracking
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 text-gray-300 text-xs rounded-full">
                        <i data-lucide="wrench" class="w-3 h-3"></i> Maintenance
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 text-gray-300 text-xs rounded-full">
                        <i data-lucide="ticket" class="w-3 h-3"></i> Tickets
                    </span>
                </div>

            </div>
        </div>

    </div>

    {{-- Footer --}}
    <p class="text-center text-xs text-gray-400 mt-5">
        &copy; {{ date('Y') }} AZCode &mdash; Fleet Management System
    </p>

</div>

<script>
    lucide.createIcons();
    document.addEventListener('alpine:initialized', () => lucide.createIcons());
    document.addEventListener('alpine:mutation',    () => lucide.createIcons());
</script>
</body>
</html>