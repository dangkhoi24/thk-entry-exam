{{--
    Label + control + description + validation error
    <x-form-field label="ホテル名" name="hotel_name" required>
        <x-input name="hotel_name" />
    </x-form-field>

    @param string|null $label
    @param string|null $name         field name: links the label and shows $errors->first($name)
    @param string|null $for          links the label only (no error), defaults to $name
    @param bool $required            shows the required mark
    @param string|null $description  hint under the control
--}}
@props([
    'label' => null,
    'name' => null,
    'for' => null,
    'required' => false,
    'description' => null,
])

@php
    $error = $name !== null ? $errors->first($name) : null;
    $for ??= $name;
@endphp

<div {{ $attributes->class(['form-field', 'is-invalid' => $error]) }}>
    @if ($label !== null)
        <label class="form-field__label" @if ($for !== null) for="{{ $for }}" @endif>
            {{ $label }}
            @if ($required)
                <span class="form-field__required">*</span>
            @endif
        </label>
    @endif

    <div class="form-field__control">
        {{ $slot }}
    </div>

    @if ($description !== null)
        <p class="form-field__description">{{ $description }}</p>
    @endif

    @if ($error)
        <p class="form-field__error">{{ $error }}</p>
    @endif
</div>
