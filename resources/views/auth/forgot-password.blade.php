@extends('layouts.master-without-nav')
@section('title') Login @endsection
@section('content')
<body class="bg-gray-100 text-4xl min-h-screen flex items-center justify-center p-8"> 
    <div class="w-full max-w-[150rem] px-24"> 
        <div class="text-center mb-24 mt-48"> 
            <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="AERO Logo" class="mx-auto h-36 w-auto" />
            <p class="text-gray-600 mt-6 text-4xl">Aero Aviation Tech Staff Portal Dashboard</p>
        </div>

        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2">
            <div class="p-16 lg:p-24 mt-8">
                <h2 class="text-5xl font-semibold">Reset Password</h2>

                <form class="mt-32 space-y-12" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="input_group mt-8">
                        <label for="email" class="block">Email</label>
                        <input class="input" type="email" name="email" value="{{ old('email') }}" required autofocus>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end mt-16">
                        <x-primary-button>
                            {{ __('Email Password Reset Link') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Right Image Side -->
            <div class="hidden lg:block relative">
                <img src="{{ Vite::asset('resources/img/airplane-image.jpg') }}" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-white/10"></div>
            </div>
        </div>

        <p class="text-center text-gray-600 mt-32 text-3xl">© 2024 <b>Aero Aviation Tech</b></p>
    </div>
</body>
@endsection
