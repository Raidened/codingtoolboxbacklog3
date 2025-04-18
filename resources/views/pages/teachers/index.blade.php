<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Enseignants') }}
            </span>
        </h1>
    </x-slot>

    <!-- begin: grid -->
    <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
        <div class="lg:col-span-2">
            <div class="grid">
                <div class="card card-grid h-full min-w-full">
                    <div class="card-header">
                        <h3 class="card-title">Liste des enseignants</h3>
                        <div class="input input-sm max-w-48">
                            <i class="ki-filled ki-magnifier"></i>
                            <input id="search-teachers" data-table="teachers-table" data-search="1,2" placeholder="Rechercher un enseignant" type="text" aria-label="Rechercher un enseignant" />
                        </div>
                    </div>
                    <div class="card-body">
                        <div data-datatable="true" data-datatable-page-size="5">
                            <form method="GET" class="mb-4 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                                <div class="input input-sm max-w-48" role="search">
                                    <i class="ki-filled ki-magnifier"></i>
                                    <input
                                        name="search"
                                        type="text"
                                        value="{{ request('search', '') }}"
                                        placeholder="Rechercher un enseignant"
                                        class="w-full" />
                                </div>
                                <div class="flex items-center gap-2">
                                    <label for="perpage" class="flex items-center gap-2">
                                        Afficher
                                        <select id="perpage" name="perpage" class="select select-sm w-16" onchange="this.form.submit()">
                                            @foreach([5,10,25,50] as $size)
                                                <option value="{{ $size }}" {{ request('perpage', 10)==$size ? 'selected' : '' }}>{{ $size }}</option>
                                            @endforeach
                                        </select>
                                        par page
                                    </label>
                                </div>
                            </form>
                            <div class="overflow-x-auto scrollable-x-auto">
                                <table class="table table-border">
                                    <thead>
                                        <tr>
                                            <th class="min-w-[135px]"><span class="sort asc"><span class="sort-label">Nom</span><span class="sort-icon"></span></span></th>
                                            <th class="min-w-[135px]"><span class="sort"><span class="sort-label">Prénom</span><span class="sort-icon"></span></span></th>
                                            <th class="w-[70px]"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($teachers as $teacher)
                                        <tr>
                                            <td>{{ $teacher->last_name }}</td>
                                            <td>{{ $teacher->first_name }}</td>
                                            <td>
                                                <div class="flex items-center justify-between">
                                                    <a href="#">
                                                        <i class="text-success ki-filled ki-shield-tick"></i>
                                                    </a>

                                                    <a class="hover:text-primary cursor-pointer" href="#"
                                                       data-modal-toggle="#teacher-modal"
                                                       data-teacher-id="{{ $teacher->id }}"
                                                       data-teacher-firstname="{{ $teacher->first_name }}"
                                                       data-teacher-lastname="{{ $teacher->last_name }}">
                                                        <i class="ki-filled ki-cursor"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4">Aucun enseignant trouvé.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $teachers->links() }}
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
                        Ajouter un Enseignant
                    </h3>
                </div>
                <div class="card-body flex flex-col gap-5">
                    <form action="{{ route('teacher.store') }}" method="POST">
                        @csrf
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <x-forms.input name="last_name" :label="__('Nom')" value="{{ old('last_name') }}" required />
                        
                        <x-forms.input name="first_name" :label="__('Prénom')" value="{{ old('first_name') }}" required />
                        
                        <x-forms.input name="email" type="email" :label="__('Email')" value="{{ old('email') }}" required />
                        
                        <x-forms.input name="password" type="password" :label="__('Mot de passe')" required />

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

@include('pages.teachers.teacher-modal')
