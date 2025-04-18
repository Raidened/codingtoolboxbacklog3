@props([
    'label'         => false,
    'name'          => 'select_name',
    'value'         => '',
    'disabled'      => false,
    'messages'      => false
])

<div class="flex flex-col gap-1">
    @if($label)
        <label class="form-label font-normal text-gray-900">{{ $label }}</label>
    @endif
    <select class="select" {{ $disabled ? 'disabled' : '' }} name="{{ $name }}">
        {{ $slot }}
    </select>

    @if($messages)
        <x-forms.input-error :messages="$messages" class="mt-1" />
    @endif
</div>

