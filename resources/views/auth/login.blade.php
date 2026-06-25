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

<div class="w-full max-w-md">

    {{-- Logo --}}
    <div class="flex justify-center mb-6">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.jpeg') }}"
                 class="h-24 object-contain"
                 alt="Fleet Management System"
                 onerror="this.style.display='none'">
        </a>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow p-8">

        {{-- Heading --}}
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Sign in to your account</h2>
            <p class="text-sm text-gray-500 mt-1">Fleet Management System</p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700">
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
                                  focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
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
                                  focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
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
                       class="text-xs text-green-600 hover:text-green-700 hover:underline">
                        Forgot your password?
                    </a>
                @endif
            </div>

            {{-- Remember me --}}
            <div class="flex items-center gap-2 mb-6">
                <input id="remember_me"
                       type="checkbox"
                       name="remember"
                       class="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                <label for="remember_me" class="text-sm text-gray-600 select-none">
                    Remember me
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2
                           bg-green-600 hover:bg-green-700 active:bg-green-800
                           text-white text-sm font-semibold
                           py-2.5 rounded-lg transition">
                <i data-lucide="log-in" class="w-4 h-4"></i>
                Sign In
            </button>
        </form>

    </div>

    {{-- Footer --}}
    <p class="text-center text-xs text-gray-400 mt-6">
        &copy; {{ date('Y') }} AZCode &mdash; Fleet Management System
    </p>

</div>

<script>
    lucide.createIcons();

    // Re-run after Alpine renders (for the eye icon swap)
    document.addEventListener('alpine:initialized', () => {
        lucide.createIcons();
    });

    // Refresh icons whenever Alpine updates a data-lucide attribute
    document.addEventListener('alpine:mutation', () => {
        lucide.createIcons();
    });
</script>
</body>
</html>