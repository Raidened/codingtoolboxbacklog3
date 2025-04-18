<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold">{{ __('Rétrospectives') }}</h1>
            @if(!$isStudent)
                <a href="{{ route('retro.create') }}" class="btn btn-primary">{{ __('Créer une rétro') }}</a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        @isset($retros)
            @foreach($retros as $cohortId => $cohortRetros)
                @php $cohortName = $cohorts->firstWhere('id', $cohortId)->name ?? __('Promotion inconnue'); @endphp
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-2">{{ $cohortName }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($cohortRetros as $retro)
                            <div class="card p-4">
                                <h3 class="font-bold">{{ $retro->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $retro->created_at->format('d/m/Y') }}</p>
                                <div class="mt-2 space-x-2">
                                    <a href="{{ route('retro.show', $retro) }}" class="text-indigo-600 hover:text-indigo-900 inline-block">{{ __('Voir le Kanban') }}</a>
                                    @if(!$isStudent)
                                    <form action="{{ route('retro.destroy', $retro) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Voulez-vous vraiment supprimer cette rétrospective ?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 inline-block">{{ __('Supprimer') }}</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center text-gray-500">{{ __('Aucune rétrospective trouvée.') }}</p>
        @endisset
    </div>
</x-app-layout>
