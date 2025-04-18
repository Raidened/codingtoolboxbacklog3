<div class="fixed top-0 bottom-0 z-20 hidden lg:flex flex-col shrink-0 w-[--tw-sidebar-width] bg-[--tw-page-bg] dark:bg-[--tw-page-bg-dark]" data-drawer="true" data-drawer-class="drawer drawer-start flex top-0 bottom-0" data-drawer-enable="true|lg:false" id="sidebar">
    <!-- Sidebar Header -->
    <div id="sidebar_header">
        <div class="flex items-center gap-2.5 px-3.5 h-[70px]">
            <a href="/">
                <img class="dark:hidden h-[42px]" src="{{ asset('metronic/media/app/mini-logo-circle.svg') }}"/>
                <img class="hidden dark:inline-block h-[42px]" src="{{ asset('metronic/media/app/mini-logo-circle-dark.svg') }}"/>
            </a>
            <span class="text-base font-medium text-gray-900 grow justify-start">
                Coding ToolBox
            </span>
        </div>
        <div class="pt-2.5 px-3.5 mb-1">
            <div class="input">
                <i class="ki-filled ki-magnifier"></i>
                <input class="min-w-0" placeholder="Search" type="text" value=""/>
            </div>
        </div>
    </div>
    <!-- End of Sidebar Header -->
    <!-- Sidebar menu -->
    <div class="flex items-stretch grow shrink-0 justify-center my-5" id="sidebar_menu">
        <div class="scrollable-y-auto light:[--tw-scrollbar-thumb-color:var(--tw-content-scrollbar-color)] grow" data-scrollable="true" data-scrollable-dependencies="#sidebar_header, #sidebar_footer" data-scrollable-height="auto" data-scrollable-offset="0px" data-scrollable-wrappers="#sidebar_menu">
            <!-- Primary Menu -->
            <div class="menu flex flex-col w-full gap-1.5 px-3.5" data-menu="true"
                 data-menu-accordion-expand-all="false" id="sidebar_primary_menu">
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200"
                       href="{{ route('dashboard') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="ki-filled ki-home-3"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            Overview
                        </span>
                    </a>
                </div>
                @can('viewAny', \App\Models\Cohort::class)
                <div class="menu-item" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
                    <div class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent">
                        <span class="menu-icon items-start text-gray-600 text-lg menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="ki-filled ki-users"></i>
                        </span>
                        <span class="menu-title font-medium text-sm text-gray-800 menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            Promotions
                        </span>
                        <span class="menu-arrow text-gray-600 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800">
                            <i class="ki-filled ki-down text-xs menu-item-show:hidden"></i>
                            <i class="ki-filled ki-up text-xs hidden menu-item-show:inline-flex"></i>
                        </span>
                    </div>
                    <div class="menu-accordion gap-px ps-7">
                        <div class="menu-item">
                            <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200"
                               href="{{ route('cohort.index') }}">
                                <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                    Toutes les promotions
                                </span>
                            </a>
                        </div>
                        <!-- @todo CETTE PARTIE EST A GENERER DEPUIS LA BDD -->
                        <div class="menu-item">
                            <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200"
                               href="#">
                               <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                B1 - Cergy - 24-25
                               </span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200"
                               href="#">
                               <span class="menu-title text-2sm text-gray-800 menu-item-active:text-gray-900 menu-link-hover:text-gray-900">
                                B2 - Cergy - 24-25
                               </span>
                            </a>
                        </div>
                        <!-- FIN -->
                   </div>
                </div>

                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200"
                       href="{{ route('student.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="ki-filled ki-user-edit"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            Etudiants
                        </span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200"
                       href="{{ route('teacher.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="ki-filled ki-security-user"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            Enseignants
                        </span>
                    </a>
                </div>
                @endcan
           </div>
            <!-- End of Primary Menu -->
            <div class="border-b border-gray-300 mt-4 mb-4 mx-3.5"></div>

            <div class="menu flex flex-col w-full gap-1.5 px-3.5" data-menu="true"
                 data-menu-accordion-expand-all="false" id="sidebar_secondary_menu">
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200"
                       href="{{ route('common-life.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="ki-filled ki-abstract-44"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            Vie commune
                        </span>
                    </a>
                </div>
                
                @php
                    $userRole = auth()->user()->school()->pivot->role ?? null;
                @endphp
                
                @if($userRole === 'admin' || $userRole === 'teacher')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200"
                       href="{{ route('grades.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="ki-filled ki-notepad"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            Gestion des notes
                        </span>
                    </a>
                </div>
                @endif
                
                @if($userRole === 'student')
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200"
                       href="{{ route('grades.student') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="ki-filled ki-notepad-edit"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            Bilan de compétences
                        </span>
                    </a>
                </div>
                @endif
                
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200"
                       href="{{ route('groups.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="ki-filled ki-abstract-36"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            Groupes
                        </span>
                    </a>
                </div>
               <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200"
                       href="{{ route('knowledge.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="ki-filled ki-devices-2"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            Bilans de connaissances
                        </span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200"
                       href="{{ route('retro.index') }}">
                        <span class="menu-icon items-start text-lg text-gray-600 menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900">
                            <i class="ki-filled ki-lovely"></i>
                        </span>
                        <span class="menu-title text-sm text-gray-800 font-medium menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900">
                            Retrospectives
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Sidebar menu-->
    <!-- Footer -->
    <div class="flex flex-center justify-between shrink-0 ps-4 pe-3.5 mb-3.5" id="sidebar_footer">
        <div class="menu" data-menu="true">
            <div class="menu-item" data-menu-item-offset="-10px, 15px" data-menu-item-placement="right-end" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:click">
                <div class="menu-toggle btn btn-icon rounded-full">
                    <img alt="" class="size-8 rounded-full justify-center border border-gray-500 shrink-0"
                         src="{{ asset('metronic/media/avatars/300-2.png') }}" />
                </div>
                <div class="menu-dropdown menu-default light:border-gray-300 w-screen max-w-[250px]">
                    <div class="flex items-center justify-between px-5 py-1.5 gap-1.5">
                        <div class="flex items-center gap-2">
                            <img alt="" class="size-9 rounded-full border-2 border-success"
                                 src="{{ asset('metronic/media/avatars/300-2.png') }}">
                                <div class="flex flex-col gap-1.5">
                                    <span class="text-sm text-gray-800 font-semibold leading-none">
                                        {{ auth()->user()->short_name }}
                                    </span>
                                    <a class="text-xs text-gray-600 font-medium leading-none">
                                        {{ auth()->user()->school()?->name }}
                                    </a>
                                </div>
                            </img>
                        </div>
                        <span class="badge badge-xs badge-primary badge-outline">
                            {{ auth()->user()->school()?->pivot?->role }}
                        </span>
                    </div>
                    <div class="menu-separator"></div>
                    <div class="flex flex-col">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('profile.edit') }}">
                                <span class="menu-icon">
                                    <i class="ki-filled ki-profile-circle"></i>
                                </span>
                                <span class="menu-title">My Profile</span>
                            </a>
                        </div>
                    </div>
                    <div class="menu-separator"></div>
                    <div class="flex flex-col">
                        <div class="menu-item mb-0.5">
                            <div class="menu-link">
                                <span class="menu-icon">
                                    <i class="ki-filled ki-moon"></i>
                                </span>
                                <span class="menu-title">
                                    Dark Mode
                                </span>
                                <label class="switch switch-sm">
                                    <input data-theme-state="dark" data-theme-toggle="true" name="check" type="checkbox" value="1"/>
                                </label>
                            </div>
                        </div>
                        <div class="menu-item px-4 py-1.5">
                            <form method="POST" class="flex flex-col" action="{{ route('logout') }}">
                                @csrf
                                <a class="btn btn-sm btn-light justify-center"
                                   href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit();">
                                    {{ _('Log out') }}
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-1.5">
            <div class="dropdown" data-dropdown="true" data-dropdown-offset="10px, 15px" data-dropdown-placement="right-end" data-dropdown-trigger="click|lg:click">
                <button class="dropdown-toggle btn btn-icon btn-icon-lg relative size-8 hover:bg-light hover:text-primary dropdown-open:bg-gray-200 text-gray-600">
                    <span class="menu-icon">
                        <i class="ki-filled ki-notification-status"></i>
                    </span>
                </button>
                <div class="dropdown-content light:border-gray-300 w-full max-w-[460px]">
                    <div class="flex items-center justify-between gap-2.5 text-sm text-gray-900 font-semibold px-5 py-2.5 border-b border-b-gray-200" id="notifications_header">
                        Notifications
                        <button class="btn btn-sm btn-icon btn-light btn-clear shrink-0" data-dropdown-dismiss="true">
                            <i class="ki-filled ki-cross">
                            </i>
                        </button>
                    </div>
                    <div class="grow">
                        <div class="flex flex-col">
                            <x-main.notifications />
                        </div>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="btn btn-icon btn-icon-lg size-8 hover:bg-light hover:text-primary text-gray-600"
                   href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit();">
                    <i class="ki-filled ki-exit-right">
                    </i>
                </a>
            </form>
        </div>
    </div>
    <!-- End of Footer -->
</div>
