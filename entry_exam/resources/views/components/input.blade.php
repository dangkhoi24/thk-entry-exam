{{--
    Text-like input (text, date, file ...).
    "class" goes to the outer box (for layout), other attributes go to the native <input>.
    <x-input name="hotel_name" :value="old('hotel_name')" placeholder="..." />
    <x-input name="keyword" icon="search" class="flex-1" />

    @param string $size        sm | md | lg
    @param bool $invalid       red border (also applied by a parent .form-field.is-invalid)
    @param string|null $icon   <x-icon> name shown at the left
--}}
@props([
    'size' => 'md',
    'invalid' => false,
    'icon' => null,
])

<div {{ $attributes->only('class')->class(['input', "is-{$size}", 'is-invalid' => $invalid]) }}>
    @if ($icon !== null)
        <x-icon :name="$icon" class="input__icon" />
    @endif
    <input {{ $attributes->except('class')->merge(['type' => 'text', 'id' => $attributes->get('name')])->class('input__field') }}>
</div>
