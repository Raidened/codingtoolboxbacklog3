<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="flex grow gap-5 lg:gap-7.5">
        <div class="hidden lg:block w-[230px] shrink-0">
            <div class="w-[230px]" data-sticky="true" data-sticky-animation="true" data-sticky-class="fixed z-[4] left-auto top-[3rem]" data-sticky-name="scrollspy" data-sticky-offset="200" data-sticky-target="#scrollable_content">
                <div class="flex flex-col grow relative before:absolute before:left-[11px] before:top-0 before:bottom-0 before:border-l before:border-gray-200" data-scrollspy="true" data-scrollspy-offset="80px|lg:110px" data-scrollspy-target="#scrollable_content">
                    <a class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-1.5 active border border-transparent text-2sm text-gray-800 hover:text-primary hover:font-medium scrollspy-active:bg-secondary-active scrollspy-active:text-primary scrollspy-active:font-medium dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100" data-scrollspy-anchor="true" href="#basic_settings">
             <span class="flex w-1.5 relative before:absolute before:top-0 before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary">
             </span>
                        Basic Settings
                    </a>
                    <div class="flex flex-col">
                        <div class="pl-6 pr-2.5 py-2.5 text-2sm font-semibold text-gray-900">
                            Authentication
                        </div>
                        <div class="flex flex-col">
                            <a class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-3.5 border border-transparent text-2sm text-gray-800 hover:text-primary hover:font-medium scrollspy-active:bg-secondary-active scrollspy-active:text-primary scrollspy-active:font-medium dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100" data-scrollspy-anchor="true" href="#auth_email">
               <span class="flex w-1.5 relative before:absolute before:top-0 before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary">
               </span>
                                Email
                            </a>
                            <a class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-3.5 border border-transparent text-2sm text-gray-800 hover:text-primary hover:font-medium scrollspy-active:bg-secondary-active scrollspy-active:text-primary scrollspy-active:font-medium dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100" data-scrollspy-anchor="true" href="#auth_password">
               <span class="flex w-1.5 relative before:absolute before:top-0 before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary">
               </span>
                                Password
                            </a>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div class="pl-6 pr-2.5 py-2.5 text-2sm font-semibold text-gray-900">
                            Advanced Settings
                        </div>
                        <div class="flex flex-col">
                            <a class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-1.5 border border-transparent text-2sm text-gray-800 hover:text-primary hover:font-medium scrollspy-active:bg-secondary-active scrollspy-active:text-primary scrollspy-active:font-medium dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100" data-scrollspy-anchor="true" href="#delete_account">
             <span class="flex w-1.5 relative before:absolute before:top-0 before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary">
             </span>
                                Delete Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col items-stretch grow gap-5 lg:gap-7.5">
            @include('profile.partials.update-profile-information-form')
            @include('profile.partials.update-user-email-form')
            @include('profile.partials.update-user-password-form')
            @include('profile.partials.delete-user-form')
        </div>
</x-app-layout>
