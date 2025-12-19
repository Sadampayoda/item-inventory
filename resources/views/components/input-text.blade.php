@props([
    'name',
    'label' => '',
    'id' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'error' => null,
])

<label for="{{ $id ?? $name }}" class="form-label">
    {{ $label }}
</label>

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    value="{{ old($name, $value) }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes->merge([
        'class' => 'form-control border-2 rounded-0 ' . ($errors->has($error ?? $name) ? 'is-invalid' : '')
    ]) }}
>

@error($error ?? $name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror
