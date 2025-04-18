<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="card max-w-[370px] w-full">
        <form method="POST" class="card-body flex flex-col gap-5 p-10" action="{{ route('password.confirm') }}">
            @csrf

            <div class="text-center mb-2.5">
                <h3 class="text-lg font-medium text-gray-900 leading-none mb-2.5">
                    {{ __('Confirm Your Password') }}
                </h3>
                <p class="text-2sm text-gray-700">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </p>
            </div>

            <!-- Password -->
            <x-forms.input
                label="{{ __('Password') }}"
                name="password"
                type="password"
                :placeholder="__('Enter your password')"
                :messages="$errors->get('password')"
            />

            <x-forms.primary-button>
                {{ __('Confirm') }}
            </x-forms.primary-button>
        </form>
    </div>
</x-guest-layout>
