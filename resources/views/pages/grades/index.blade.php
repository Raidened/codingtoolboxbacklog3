<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des notes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('grades.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Ajouter une note
                        </a>
                    </div>

                    @if($grades->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <p>Aucune note n'a été enregistrée pour le moment.</p>
                            <p>Commencez par ajouter une note à un étudiant.</p>
                        </div>
                    @else
                        <form method="GET" class="mb-4 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                            <div class="input input-sm max-w-48" role="search">
                                <i class="ki-filled ki-magnifier"></i>
                                <input
                                    name="search"
                                    type="text"
                                    value="{{ request('search', '') }}"
                                    placeholder="Rechercher un étudiant"
                                    class="w-full" />
                            </div>
                            <div class="flex items-center gap-2">
                                <label for="perpage" class="flex items-center gap-2">
                                    Afficher
                                    <select id="perpage" name="perpage" class="select select-sm w-16" onchange="this.form.submit()">
                                        @foreach([10,25,50,100] as $size)
                                            <option value="{{ $size }}" {{ request('perpage', 10)==$size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                    par page
                                </label>
                            </div>
                        </form>
                        <div class="overflow-x-auto scrollable-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 table table-border">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[135px]">
                                            <span class="sort asc" tabindex="0" role="button" aria-label="Trier par étudiant">
                                                <span class="sort-label">Étudiant</span>
                                                <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[135px]">
                                            <span class="sort" tabindex="0" role="button" aria-label="Trier par promotion">
                                                <span class="sort-label">Promotion</span>
                                                <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[135px]">
                                            <span class="sort" tabindex="0" role="button" aria-label="Trier par titre">
                                                <span class="sort-label">Titre</span>
                                                <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[135px]">
                                            <span class="sort" tabindex="0" role="button" aria-label="Trier par note">
                                                <span class="sort-label">Note</span>
                                                <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[135px]">
                                            <span class="sort" tabindex="0" role="button" aria-label="Trier par date d'évaluation">
                                                <span class="sort-label">Date d'évaluation</span>
                                                <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($grades as $grade)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $grade->user->first_name }} {{ $grade->user->last_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $grade->cohort->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $grade->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $grade->value >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $grade->value }}/20
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $grade->evaluation_date->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('grades.show', $grade->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" aria-label="Voir la note">Voir</a>
                                                <a href="{{ route('grades.edit', $grade->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3" aria-label="Modifier la note">Modifier</a>
                                                <form action="{{ route('grades.destroy', $grade->id) }}" method="POST" class="inline" aria-label="Supprimer la note">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette note ?')">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $grades->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>