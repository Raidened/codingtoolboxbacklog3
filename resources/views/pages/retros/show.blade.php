<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold">{{ $retro->name }} - {{ $retro->cohort->name }}</h1>
            <a href="{{ route('retro.index') }}" class="btn btn-secondary">{{ __('Retour') }}</a>
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-bold mb-6">Tableau de rétrospective</h2>
            
            <!-- Nouveau Kanban implémenté avec Tailwind directement -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($retro->columns as $column)
                    <div class="bg-gray-100 rounded-lg p-4 shadow-sm">
                        <h3 class="font-bold text-lg mb-3 bg-blue-600 text-white p-2 rounded-t-lg">{{ $column->name }}</h3>
                        
                        <div class="space-y-3">
                            @foreach($column->data as $item)
                                <div class="bg-white p-3 rounded shadow relative group">
                                    <div class="flex justify-between items-start">
                                        <h4 class="font-semibold text-break mb-2 pr-6">{{ $item->name }}</h4>
                                        <form action="{{ route('retro.item.remove', $item->id) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 bg-white rounded-full p-1 shadow-sm" onclick="return confirm('{{ __('Voulez-vous vraiment supprimer ce retour ?') }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-sm text-gray-600 break-words overflow-hidden" style="word-wrap: break-word; max-height: 150px; overflow-y: auto;">{{ $item->description }}</p>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Formulaire pour ajouter un retour -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <form action="{{ route('retro.column.addItem', $column->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <input type="text" name="name" placeholder="Titre du retour" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                </div>
                                <div>
                                    <textarea name="description" placeholder="Description (optionnelle)" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2"></textarea>
                                </div>
                                <div>
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-300">
                                        {{ __('Ajouter un retour') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
