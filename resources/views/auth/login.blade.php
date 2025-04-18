<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="card max-w-[370px] w-full">
        <form method="POST" class="card-body flex flex-col gap-5 p-10" action="{{ route('login') }}">
            @csrf

            <div class="text-center mb-2.5">
                <h3 class="text-lg font-medium text-gray-900 leading-none mb-2.5">Sign in</h3>
                <div class="flex items-center justify-center font-medium">
                    <span class="text-2sm text-gray-700 me-1.5">Need an account?</span>
                    <a class="text-2sm link" href="{{ route('register') }}">Sign up</a>
                </div>
            </div>

            <!-- Email Address -->
            <x-forms.input label="{{ __('Email') }}" name="email"
                           :value="old('email')" type="email" :placeholder="__('email@email.com')"
                           :messages="$errors->get('email')"/>

            <!-- Password -->
            <x-forms.input label="{{ __('Password') }}" name="password" :placeholder="__('Enter Password')"
                           :value="old('email')" type="password" :resetLink="true"
                           :messages="$errors->get('password')"/>

            <!-- Remember Me -->
            <x-forms.checkbox :label="__('Remember Me')" name="remember" />


            <x-forms.primary-button>
                {{ __('Log in') }}
            </x-forms.primary-button>

        </form>
    </div>
</x-guest-layout>
