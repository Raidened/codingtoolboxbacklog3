<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes Groupes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($userGroups->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500">Vous n'êtes actuellement membre d'aucun groupe.</p>
                        </div>
                    @else
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Vos groupes</h3>
                        
                        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
                            @foreach($userGroups as $group)
                                <div class="bg-gray-50 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $group->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">Promotion: {{ $group->cohort->name }}</p>
                                    
                                    @if($group->description)
                                        <p class="text-sm text-gray-600 mt-2">{{ $group->description }}</p>
                                    @endif
                                    
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-500">Membres du groupe ({{ $group->users->count() }})</p>
                                        <ul class="mt-2 space-y-1">
                                            @foreach($group->users as $member)
                                                <li class="text-sm {{ $member->id === Auth::id() ? 'font-semibold text-indigo-600' : 'text-gray-700' }}">
                                                    {{ $member->first_name }} {{ $member->last_name }}
                                                    @if($member->id === Auth::id())
                                                        <span class="text-xs text-indigo-600">(vous)</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>