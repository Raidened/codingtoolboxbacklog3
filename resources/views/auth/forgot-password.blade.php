<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="card max-w-[370px] w-full">
        <form method="POST" class="card-body flex flex-col gap-5 p-10" action="{{ route('password.email') }}">
            @csrf

            <div class="text-center mb-2.5">
                <h3 class="text-lg font-medium text-gray-900 leading-none mb-2.5">
                    {{ __('Forgot Password') }}
                </h3>
                <p class="text-2sm text-gray-700">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>
            </div>

            <!-- Email Address -->
            <x-forms.input
                label="{{ __('Email') }}"
                name="email"
                type="email"
                :value="old('email')"
                :placeholder="__('email@email.com')"
                :messages="$errors->get('email')"
            />

            <x-forms.primary-button>
                {{ __('Email Password Reset Link') }}
            </x-forms.primary-button>
        </form>
    </div>
</x-guest-layout>
