<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Dashboard') }}
            </span>
        </h1>
    </x-slot>

    <!-- begin: grid -->
    <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
        <div class="lg:col-span-2">
            <div class="grid">
                <div class="card card-grid h-full min-w-full">
                    <div class="card-header">
                        <h3 class="card-title">
                            Block 1
                        </h3>
                    </div>
                    <div class="card-body flex flex-col gap-5">

                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1">
            <div class="card card-grid h-full min-w-full">
                <div class="card-header">
                    <h3 class="card-title">
                        Block 2
                    </h3>
                </div>
                <div class="card-body flex flex-col gap-5">
                </div>
            </div>
        </div>
    </div>
    <!-- end: grid -->
</x-app-layout>
