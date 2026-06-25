<x-guest-layout>
    <h2 class="mb-4 text-gray text-md">
        {{ __('To continue with your login process, please provide your new password. Once your password has been successfully updated, you may proceed to log in as usual.') }}
    </h2>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
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
        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
