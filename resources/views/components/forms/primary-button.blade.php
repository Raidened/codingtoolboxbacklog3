<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary flex justify-center']) }}>
    {{ $slot }}
</button>
