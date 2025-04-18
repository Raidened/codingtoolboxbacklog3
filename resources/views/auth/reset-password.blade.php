<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="card max-w-[370px] w-full">
        <form method="POST" class="card-body flex flex-col gap-5 p-10" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="text-center mb-2.5">
                <h3 class="text-lg font-medium text-gray-900 leading-none mb-2.5">
                    {{ __('Reset Your Password') }}
                </h3>
                <p class="text-2sm text-gray-700">
                    {{ __('Please choose a new password for your account.') }}
                </p>
            </div>

            <!-- Email Address -->
            <x-forms.input
                label="{{ __('Email') }}"
                name="email"
                type="email"
                :value="old('email', $request->email)"
                :placeholder="__('email@email.com')"
                :messages="$errors->get('email')"
            />

            <!-- Password -->
            <x-forms.input
                label="{{ __('Password') }}"
                name="password"
                type="password"
                :placeholder="__('Enter your new password')"
                :messages="$errors->get('password')"
            />

            <!-- Confirm Password -->
            <x-forms.input
                label="{{ __('Confirm Password') }}"
                name="password_confirmation"
                type="password"
                :placeholder="__('Confirm your new password')"
                :messages="$errors->get('password_confirmation')"
            />

            <x-forms.primary-button>
                {{ __('Reset Password') }}
            </x-forms.primary-button>
        </form>
    </div>
</x-guest-layout>
