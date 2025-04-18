<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-lg font-semibold text-gray-800">
            <span class="text-gray-700">
                {{ __('Groupes') }}
            </span>
        </h1>
    </x-slot>

    <div class="py-6">
        <form method="POST" action="{{ route('groups.generate') }}" class="space-y-6 max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
            @csrf
            <div>
                <label for="cohort_id" class="block text-sm font-medium text-gray-700">Promotion</label>
                <select id="cohort_id" name="cohort_id" class="form-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary focus:border-primary" required>
                    <option value="">Sélectionner une promotion</option>
                    @foreach($cohorts as $cohort)
                        <option value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="group_size" class="block text-sm font-medium text-gray-700">Nombre d'étudiants par groupe</label>
                <input type="number" min="2" max="50" id="group_size" name="group_size" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary focus:border-primary" required>
            </div>
            <div>
                <label for="batch_name" class="block text-sm font-medium text-gray-700">Nom de la fournée</label>
                <input type="text" id="batch_name" name="batch_name" value="{{ old('batch_name') }}" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary focus:border-primary" maxlength="255" placeholder="Entrez un nom pour cette fournée" required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary w-full">Générer les groupes</button>
            </div>
        </form>
    </div>

    @if(session('info'))
        <div class="mt-4 max-w-2xl mx-auto">
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="mt-4 max-w-2xl mx-auto">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Affichage des groupes existants par promotion -->
    @isset($groupsByCohort)
        <div class="mt-12">
            <h2 class="text-xl font-bold mb-6 text-center text-gray-800">Groupes existants par promotion</h2>
            @forelse($groupsByCohort as $cohortId => $batches)
                @php
                    $cohortName = $cohorts->where('id', $cohortId)->first()->name ?? 'Promotion inconnue';
                @endphp
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2 text-gray-700">{{ $cohortName }}</h3>
                    @foreach($batches as $batchName => $groups)
                        <div class="mb-4 border rounded-lg p-4 bg-gray-50 shadow-sm">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold text-gray-800">Fournée : {{ $batchName ?: '(Sans nom)' }}</span>
                                <form method="POST" action="{{ route('groups.batch.delete') }}" onsubmit="return confirm('Supprimer cette fournée ?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="cohort_id" value="{{ $cohortId }}">
                                    <input type="hidden" name="batch_name" value="{{ $batchName }}">
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer la fournée</button>
                                </form>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($groups as $group)
                                    <div class="card bg-white p-4 rounded-lg shadow-md">
                                        <div class="card-header pb-2">
                                            <h4 class="card-title text-primary font-semibold">{{ $group->name }}</h4>
                                            <p class="text-xs text-gray-500">
                                                @if(isset($group->generation_params['date_generated']))
                                                    Créé le: {{ \Carbon\Carbon::parse($group->generation_params['date_generated'])->format('d/m/Y H:i') }}
                                                @endif
                                            </p>
                                            @php
                                                $totalNotes = 0;
                                                $nbElevesAvecNote = 0;

                                                foreach ($group->users as $u) {
                                                    $grade = $u->grades->first();
                                                    $note = $grade ? $grade->value : ($u->competence_score ?? null);

                                                    if ($note !== null) {
                                                        $totalNotes += $note;
                                                        $nbElevesAvecNote++;
                                                    }
                                                }

                                                $avg = $nbElevesAvecNote > 0 ? round($totalNotes / $nbElevesAvecNote, 2) : null;
                                            @endphp
                                            @if($avg !== null)
                                                <p class="text-xs font-semibold {{ $avg > 14 ? 'text-green-600' : ($avg > 10 ? 'text-blue-600' : 'text-red-600') }}">
                                                    Moyenne du groupe : {{ $avg }}/20
                                                </p>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            @if($group->users->count() > 0)
                                                <ul class="list-disc pl-5 text-sm text-gray-700">
                                                    @foreach($group->users as $user)
                                                        <li>{{ $user->last_name }} {{ $user->first_name }}
                                                            @php
                                                                $grade = $user->grades->first();
                                                                $note = $grade ? $grade->value : ($user->competence_score ?? null);
                                                            @endphp
                                                            @if($note !== null)
                                                                <span class="text-xs font-semibold {{ $note > 14 ? 'text-green-600' : ($note > 10 ? 'text-blue-600' : 'text-red-600') }}">
                                                                    ({{ $note }}/20)
                                                                </span>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-gray-500 italic">Aucun étudiant dans ce groupe</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <p class="text-center text-gray-500">Aucun groupe n'a encore été créé</p>
            @endforelse
        </div>
    @endisset
</x-app-layout>
