<x-guest-layout>
    <div class="my-10 pt-10">
        <div class="container mx-auto">
            <div class="flex justify-center">
                <div class="w-full max-w-7xl">
                    <div class="p-8 lg:w-full">
                        <div>
                            <h5 class="text-4xl font-semibold">Two-factor authentication (2FA)</h5>
                        </div>
                        <div class="mt-6">
                            <form method="POST" action="{{ route('user.2fa.verify') }}">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">
                                <div class="mb-6">
                                    <label for="token" class="block font-semibold text-gray-700">Enter Passcode</label>
                                    <input id="token" type="text" class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300" name="token">
                                </div>
                                <div class="text-right">
                                    <button class="btn-primary px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition" type="submit">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-10 text-center">
                        <p class="text-gray-500">© 2024 <b>Aero Aviation Tech</b></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>