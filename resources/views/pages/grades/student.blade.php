<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes notes et bilans de compétences') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($grades->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <p>Vous n'avez pas encore de notes enregistrées.</p>
                        </div>
                    @else
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Moyenne générale</h3>
                            <div class="mt-3 flex items-center">
                                <div class="relative w-full h-4 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="absolute top-0 left-0 h-full {{ $averageScore >= 10 ? 'bg-green-500' : 'bg-red-500' }}" style="width: {{ min($averageScore * 5, 100) }}%"></div>
                                </div>
                                <span class="ml-4 text-lg font-semibold {{ $averageScore >= 10 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($averageScore, 2) }}/20
                                </span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Promotion
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Intitulé
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Note
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date d'évaluation
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Enseignant
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($grades as $grade)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $grade->cohort->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $grade->title }}</div>
                                                @if($grade->description)
                                                    <div class="text-sm text-gray-500">{{ Str::limit($grade->description, 50) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $grade->value >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $grade->value }}/20
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $grade->evaluation_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($grade->teacher)
                                                    {{ $grade->teacher->first_name }} {{ $grade->teacher->last_name }}
                                                @else
                                                    Non spécifié
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>