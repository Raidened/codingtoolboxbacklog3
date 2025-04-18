<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter une note') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('grades.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="cohort_id" class="block text-sm font-medium text-gray-700">Promotion</label>
                            <select id="cohort_id" name="cohort_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Sélectionner une promotion</option>
                                @foreach($cohorts as $cohort)
                                    <option value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                                @endforeach
                            </select>
                            @error('cohort_id')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700">Étudiant</label>
                            <select id="user_id" name="user_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Sélectionner un étudiant</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Titre de l'évaluation</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('title')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="value" class="block text-sm font-medium text-gray-700">Note (sur 20)</label>
                            <input type="number" name="value" id="value" min="0" max="20" step="0.1" value="{{ old('value') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('value')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="evaluation_date" class="block text-sm font-medium text-gray-700">Date d'évaluation</label>
                            <input type="date" name="evaluation_date" id="evaluation_date" value="{{ old('evaluation_date', date('Y-m-d')) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('evaluation_date')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('grades.index') }}" class="text-indigo-600 hover:text-indigo-900">Retour</a>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Charger les étudiants quand la promotion change
            const cohortSelect = document.getElementById('cohort_id');
            const userSelect = document.getElementById('user_id');
            // Récupérer le jeton CSRF depuis la meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            cohortSelect.addEventListener('change', function() {
                const cohortId = this.value;
                if (cohortId) {
                    // Réinitialiser la liste des étudiants
                    userSelect.innerHTML = '<option value="">Chargement des étudiants...</option>';
                    
                    // Faire une requête AJAX pour récupérer les étudiants de cette promotion
                    fetch(`{{ route('grades.getStudents') }}?cohort_id=${cohortId}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 419) { // Code spécifique à l'expiration de session CSRF dans Laravel
                                throw new Error('Session expirée. Les données seront rechargées automatiquement.');
                            }
                            throw new Error(`Erreur HTTP: ${response.status}`);
                        }
                        return response.text();
                    })
                    .then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            // Si la réponse n'est pas un JSON valide, cela peut être une page de redirection
                            // Au lieu de demander à l'utilisateur de rafraîchir, nous le ferons automatiquement
                            console.error('Réponse non-JSON détectée, rafraîchissement automatique...');
                            window.location.reload();
                            throw new Error('Redirection en cours...');
                        }
                    })
                    .then(data => {
                        userSelect.innerHTML = '<option value="">Sélectionner un étudiant</option>';
                        
                        if (data.students && data.students.length > 0) {
                            data.students.forEach(student => {
                                const option = document.createElement('option');
                                option.value = student.id;
                                option.textContent = `${student.first_name} ${student.last_name}`;
                                userSelect.appendChild(option);
                            });
                        } else {
                            userSelect.innerHTML += '<option value="" disabled>Aucun étudiant trouvé dans cette promotion</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des étudiants:', error);
                        
                        // Plutôt que de proposer un message de confirmation, nous rechargerons
                        // automatiquement la page si nécessaire
                        if (error.message.includes('Session expirée') || error.message.includes('Redirection')) {
                            // Déjà en cours de rechargement, ne rien faire de plus
                        } else {
                            // Pour les autres erreurs, afficher simplement un message dans le select
                            userSelect.innerHTML = '<option value="">Impossible de charger les étudiants</option>';
                        }
                    });
                } else {
                    // Si aucune promotion n'est sélectionnée, réinitialiser la liste des étudiants
                    userSelect.innerHTML = '<option value="">Sélectionner d\'abord une promotion</option>';
                }
            });
        });
    </script>
</x-app-layout>