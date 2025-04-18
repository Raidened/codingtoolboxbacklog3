<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de la note') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations sur l'évaluation</h3>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Titre</p>
                                <p class="mt-1">{{ $grade->title }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Note</p>
                                <p class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $grade->value >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $grade->value }}/20
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Date d'évaluation</p>
                                <p class="mt-1">{{ $grade->evaluation_date->format('d/m/Y') }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Description</p>
                                <p class="mt-1">{{ $grade->description ?? 'Aucune description' }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations sur l'étudiant</h3>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Nom</p>
                                <p class="mt-1">{{ $grade->user->last_name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Prénom</p>
                                <p class="mt-1">{{ $grade->user->first_name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="mt-1">{{ $grade->user->email }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Promotion</p>
                                <p class="mt-1">{{ $grade->cohort->name }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-between">
                        <a href="{{ route('grades.index') }}" class="text-indigo-600 hover:text-indigo-900">Retour à la liste</a>
                        
                        <div>
                            <a href="{{ route('grades.edit', $grade->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                Modifier
                            </a>
                            
                            <form action="{{ route('grades.destroy', $grade->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette note ?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>