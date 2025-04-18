<div class="modal" data-modal="true" id="{{ $id }}">
    <div class="modal-content max-w-[600px] top-[20%]">
        <div class="modal-header">
            <h3 class="modal-title">
                {{ $title }}
            </h3>
            <button class="btn btn-xs btn-icon btn-light" data-modal-dismiss="true">
                <i class="ki-outline ki-cross">
                </i>
            </button>
        </div>
        <div class="modal-body">
            @yield('modal-content')
        </div>
    </div>
</div>
