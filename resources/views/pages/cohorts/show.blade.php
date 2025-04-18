<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">{{ $cohort->name }}</span>
        </h1>
    </x-slot>

    <!-- begin: grid -->
    <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
        <div class="lg:col-span-2">
            <div class="grid">
                <div class="card card-grid h-full min-w-full">
                    <div class="card-header">
                        <h3 class="card-title">Etudiants</h3>
                    </div>
                    <div class="card-body">
                        <div data-datatable="true" data-datatable-page-size="30">
                            <div class="scrollable-x-auto">
                                <table class="table table-border" data-datatable-table="true">
                                    <thead>
                                    <tr>
                                        <th class="min-w-[135px]">
                                            <span class="sort asc">
                                                 <span class="sort-label">Nom</span>
                                                 <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="min-w-[135px]">
                                            <span class="sort">
                                                <span class="sort-label">Prénom</span>
                                                <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="min-w-[135px]">
                                            <span class="sort">
                                                <span class="sort-label">Date de naissance</span>
                                                <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="max-w-[50px]"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cohort->users as $user)
                                        <tr>
                                            <td>{{ $user->last_name }}</td>
                                            <td>{{ $user->first_name }}</td>
                                            <td>{{ $user->birth_date ?? 'Non renseigné' }}</td>
                                            <td>
                                                <form action="{{ route('cohort.removeStudent', ['cohort' => $cohort->id, 'user' => $user->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-danger hover:text-red-700" onclick="return confirm('Êtes-vous sûr de vouloir retirer cet étudiant de la promotion?')">
                                                        <i class="ki-filled ki-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                Aucun étudiant dans cette promotion
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-gray-600 text-2sm font-medium">
                                <div class="flex items-center gap-2 order-2 md:order-1">
                                    Show
                                    <select class="select select-sm w-16" data-datatable-size="true" name="perpage"></select>
                                    per page
                                </div>
                                <div class="flex items-center gap-4 order-1 md:order-2">
                                    <span data-datatable-info="true"></span>
                                    <div class="pagination" data-datatable-pagination="true"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1">
            <div class="card h-full">
                <div class="card-header">
                    <h3 class="card-title">
                        Ajouter un étudiant à la promotion
                    </h3>
                </div>
                <div class="card-body flex flex-col gap-5">
                    <form action="{{ route('cohort.addStudent', $cohort->id) }}" method="POST">
                        @csrf
                        
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <x-forms.dropdown name="user_id" :label="__('Etudiant')">
                            <option value="">Sélectionnez un étudiant</option>
                            @foreach ($availableStudents ?? [] as $student)
                                <option value="{{ $student->id }}">{{ $student->last_name }} {{ $student->first_name }}</option>
                            @endforeach
                        </x-forms.dropdown>

                        <x-forms.primary-button type="submit">
                            {{ __('Ajouter') }}
                        </x-forms.primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end: grid -->
</x-app-layout>
