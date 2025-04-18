<x-guest-layout>
    <div class="card max-w-[370px] w-full">
        <form method="POST" class="card-body flex flex-col gap-5 p-10" action="{{ route('register') }}">
            @csrf

            <div class="text-center mb-2.5">
                <h3 class="text-lg font-medium text-gray-900 leading-none mb-2.5">Sign up</h3>
                <div class="flex items-center justify-center">
                    <span class="text-2sm text-gray-700 me-1.5">Already have an Account ?</span>
                    <a class="text-2sm link" href="{{ route('login') }}">
                        Sign In
                    </a>
                </div>
            </div>

            <!-- Last Name -->
            <x-forms.input label="{{ __('Last Name') }}" name="last_name"
                           :value="old('last_name')" type="text" placeholder="Doe"
                           :messages="$errors->get('last_name')"/>

            <!-- First Name -->
            <x-forms.input label="{{ __('First Name') }}" name="first_name"
                           :value="old('first_name')" type="text" placeholder="John"
                           :messages="$errors->get('first_name')"/>

            <!-- Email Address -->
            <x-forms.input label="{{ __('Email') }}" name="email"
                           :value="old('email')" type="email" :placeholder="__('email@email.com')"
                           :messages="$errors->get('email')"/>

            <!-- Password -->
            <x-forms.input label="{{ __('Password') }}" name="password"
                           :value="old('email')" type="password" :placeholder="__('Enter Password')"
                           :messages="$errors->get('password')"/>

            <!-- Confirm Password -->
            <x-forms.input label="{{ __('Password') }}" name="password_confirmation"
                           :value="old('email')" type="password" :placeholder="__('Re-enter Password')"
                           :messages="$errors->get('password')"/>


            <x-forms.primary-button class="ms-4">
                {{ __('Register') }}
            </x-forms.primary-button>
        </form>
    </div>
</x-guest-layout>
