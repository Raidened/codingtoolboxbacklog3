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
                    <div class="bg-gray-100 rounded-lg p-4 shadow-sm column" data-column-id="{{ $column->id }}">
                        <h3 class="font-bold text-lg mb-3 bg-blue-600 text-white p-2 rounded-t-lg">{{ $column->name }}</h3>
                        
                        <div class="space-y-3">
                            @foreach($column->data as $item)
                                <div class="bg-white p-3 rounded shadow relative group card-item" draggable="true" data-item-id="{{ $item->id }}">
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

    <!-- Pusher JS for real-time updates -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Pusher client
            // Use actual Pusher key/cluster vars instead of MIX_ so values come directly from .env
            const pusher = new Pusher("{{ env('PUSHER_KEY') }}", { cluster: "{{ env('PUSHER_CLUSTER') }}", forceTLS: true });
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let fromColumnId;
            let dragged;
            function handleDragStart(e) {
                dragged = e.target;
                fromColumnId = e.target.closest('.column').getAttribute('data-column-id');
                e.dataTransfer.effectAllowed = "move";
            }
            function handleDragOver(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = "move";
            }
            async function handleDrop(e) {
                e.preventDefault();
                if (dragged && e.currentTarget.classList.contains('column')) {
                    const columnEl = e.currentTarget;
                    columnEl.querySelector('.space-y-3').appendChild(dragged);
                    // determine new position
                    const items = Array.from(columnEl.querySelectorAll('.card-item'));
                    const newPos = items.indexOf(dragged);
                    const itemId = dragged.getAttribute('data-item-id');
                    const columnId = columnEl.getAttribute('data-column-id');
                    // send update to server
                    try {
                        await fetch(`/retros/items/${itemId}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ column_id: columnId, position: newPos })
                        });
                    } catch (err) {
                        console.error('Move failed', err);
                    }
                    dragged = null;
                }
            }
            document.querySelectorAll('.card-item').forEach(item => item.addEventListener('dragstart', handleDragStart));
            document.querySelectorAll('.column').forEach(col => {
                col.addEventListener('dragover', handleDragOver);
                col.addEventListener('drop', handleDrop);
            });

            // Subscribe to Pusher channel for updates from other clients
            const channel = pusher.subscribe('kanban-channel');
            channel.bind('card-moved', ({ cardId, toColumnId, position }) => {
                const cardEl = document.querySelector(`.card-item[data-item-id="${cardId}"]`);
                if (!cardEl) return;
                const targetColumn = document.querySelector(`.column[data-column-id="${toColumnId}"] .space-y-3`);
                if (!targetColumn) return;
                // Move card in DOM to new column and position
                const refNode = targetColumn.children[position] || null;
                targetColumn.insertBefore(cardEl, refNode);
            });
            // Handle card added by other clients
            channel.bind('card-added', ({ cardId, columnId, name, description, position }) => {
                const columnEl = document.querySelector(`.column[data-column-id="${columnId}"] .space-y-3`);
                if (!columnEl) return;
                // Construct new card element
                const card = document.createElement('div');
                card.className = 'bg-white p-3 rounded shadow relative group card-item';
                card.setAttribute('draggable', 'true');
                card.setAttribute('data-item-id', cardId);
                card.innerHTML = `
                    <div class="flex justify-between items-start">
                        <h4 class="font-semibold text-break mb-2 pr-6">${name}</h4>
                        <form action="/retros/items/${cardId}" method="POST" class="opacity-0 group-hover:opacity-100 transition-opacity">
                            <input type="hidden" name="_token" value="${token}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-500 hover:text-red-700 bg-white rounded-full p-1 shadow-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce retour ?')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </form>
                    </div>
                    <p class="text-sm text-gray-600 break-words overflow-hidden" style="word-wrap: break-word; max-height: 150px; overflow-y: auto;">${description || ''}</p>
                `;
                // Attach drag event
                card.addEventListener('dragstart', handleDragStart);
                // Insert at position
                const ref = columnEl.children[position] || null;
                columnEl.insertBefore(card, ref);
            });
            // Handle card removed by other clients
            channel.bind('card-removed', ({ cardId }) => {
                const cardEl = document.querySelector(`.card-item[data-item-id="${cardId}"]`);
                if (cardEl) cardEl.remove();
            });
        });
    </script>
</x-app-layout>