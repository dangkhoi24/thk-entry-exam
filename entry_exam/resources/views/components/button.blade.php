{{--
    Button / link styled as a button
    <x-button variant="primary" type="submit">登録する</x-button>
    <x-button :href="route('...')">戻る</x-button>

    @param string $variant  primary | secondary | danger | tertiary
    @param string $size     small | medium | large
    @param string $shape    square | rounded | pill
    @param string|null $href  renders <a> when given
    @param string $type     button type when rendered as <button>
--}}
@props([
    'variant' => 'secondary',
    'size' => 'medium',
    'shape' => 'rounded',
    'href' => null,
    'type' => 'button',
])

@php
    $classes = "button button-{$variant} button-{$size} button-{$shape}";
@endphp

@if ($href !== null)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>{{ $slot }}</button>
@endif
