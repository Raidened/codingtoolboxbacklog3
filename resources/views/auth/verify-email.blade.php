<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="card max-w-[370px] w-full">
        <form method="POST" class="card-body flex flex-col gap-5 p-10" action="{{ route('verification.send') }}">
            @csrf

            <div class="text-center mb-2.5">
                <h3 class="text-lg font-medium text-gray-900 leading-none mb-2.5">
                    {{ __('Verify Your Email Address') }}
                </h3>
                <p class="text-2sm text-gray-700">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="flex items-center justify-between mt-4">
                <x-forms.primary-button>
                    {{ __('Resend Verification Email') }}
                </x-forms.primary-button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </form>
    </div>
</x-guest-layout>
