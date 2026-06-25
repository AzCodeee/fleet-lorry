@section('title') Forget Password @endsection

<x-guest-layout>
    <h2 class="mb-4 text-gray text-md">
        {{ __('For security purposes, your password expires every three months. To maintain the safety of your account, please provide a new password.') }}
    </h2>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
    <form method="POST" action="{{ route('password.expired.store') }}">
        @csrf
        <div class="input_group mt-8">
            <label for="email" class="block">Email</label>
            <input class="input" type="email" name="email" value="{{ old('email') }}" required autofocus>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="input_group mt-8">
            <label for="current_password" class="block">Current Password</label>
            <input id="current_password" class="input" type="password" name="current_password" required autofocus autocomplete="current-password"/>
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>
        <div class="input_group mt-8">
            <label for="password" class="block">New Password</label>
            <input id="password" class="input" type="password" name="password" required autofocus autocomplete="new-password"/>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="input_group mt-8">
            <label for="password_confirmation" class="block">Confirm Password</label>
            <input id="password_confirmation" class="input" type="password" name="password_confirmation" required autofocus autocomplete="new-password"/>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-5">
            <x-primary-button>
                {{ __('Password Reset') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
