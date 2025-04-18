<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold">{{ __('Créer une rétrospective') }}</h1>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto">
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('retro.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <x-forms.input name="name" :label="__('Nom de la rétrospective')" value="{{ old('name') }}" required />
            </div>

            <div>
                <label for="cohort_id" class="block text-sm font-medium text-gray-700">{{ __('Promotion') }}</label>
                <select id="cohort_id" name="cohort_id" class="select w-full mt-1" required>
                    <option value="">{{ __('Sélectionner une promotion') }}</option>
                    @foreach($cohorts as $cohort)
                        <option value="{{ $cohort->id }}" {{ old('cohort_id') == $cohort->id ? 'selected' : '' }}>{{ $cohort->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('Colonnes') }}</label>
                <div id="columns" class="space-y-2 mt-1">
                    <div class="flex items-center space-x-2">
                        <input type="text" name="columns[]" class="input flex-1" placeholder="Nom de la colonne" required />
                        <button type="button" id="add-column" class="btn btn-secondary">+</button>
                    </div>
                </div>
            </div>

            <div>
                <x-forms.primary-button type="submit">{{ __('Créer') }}</x-forms.primary-button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('add-column').addEventListener('click', function() {
            var container = document.getElementById('columns');
            var div = document.createElement('div');
            div.className = 'flex items-center space-x-2';
            div.innerHTML = '<input type="text" name="columns[]" class="input flex-1" placeholder="Nom de la colonne" required />'
                + '<button type="button" class="btn btn-danger remove-column">-</button>';
            container.appendChild(div);
        });
        document.getElementById('columns').addEventListener('click', function(e) {
            if (e.target.matches('.remove-column')) {
                e.target.parentNode.remove();
            }
        });
    </script>
</x-app-layout>