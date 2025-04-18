<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="card-body flex flex-col gap-5 p-10">
        @csrf
        @method('put')

        <div>
            <x-forms.input
                label="{{ __('Current Password') }}"
                name="current_password"
                type="password"
                :placeholder="__('Enter your current password')"
                :messages="$errors->updatePassword->get('current_password')"
            />
        </div>

        <div>
            <x-forms.input
                label="{{ __('New Password') }}"
                name="password"
                type="password"
                :placeholder="__('Enter new password')"
                :messages="$errors->updatePassword->get('password')"
            />
        </div>

        <div>
            <x-forms.input
                label="{{ __('Confirm Password') }}"
                name="password_confirmation"
                type="password"
                :placeholder="__('Confirm new password')"
                :messages="$errors->updatePassword->get('password_confirmation')"
            />
        </div>

        <div class="flex items-center gap-4 mt-6">
            <x-forms.primary-button>
                {{ __('Save') }}
            </x-forms.primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
