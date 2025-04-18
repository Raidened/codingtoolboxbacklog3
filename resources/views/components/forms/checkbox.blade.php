@props([
    'label'         => 'Text',
    'disabled'      => false,
    'name'          => 'input_name'
])

<label class="checkbox-group">
    <input class="checkbox checkbox-sm" {{ $disabled ? 'disabled' : '' }} name="{{ $name }}"
           type="checkbox" value="1"/>
    <span class="checkbox-label">
       {{ $label }}
      </span>
</label>
