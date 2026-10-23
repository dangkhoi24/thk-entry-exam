{{--
    Inline SVG icon (stroke style, follows currentColor)
    <x-icon name="search" />

    @param string $name  search | plus | calendar | menu | close | check-circle | warning-circle | edit | trash | arrow-left | info-circle
--}}
@props([
    'name',
])

@php
    $paths = [
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
        'close' => '<path d="M6 6l12 12M18 6 6 18"/>',
        'check-circle' => '<circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/>',
        'warning-circle' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v6M12 16.5v.5"/>',
        'edit' => '<path d="M4 20h4L18.5 9.5a2.1 2.1 0 0 0-4-4L4 16v4Z"/><path d="m13.5 6.5 4 4"/>',
        'trash' => '<path d="M4 7h16M10 11v6M14 11v6M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12M9 7V4h6v3"/>',
        'arrow-left' => '<path d="M19 12H5M11 6l-6 6 6 6"/>',
        'info-circle' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7.5v.5"/>',
    ];
@endphp

<svg {{ $attributes->class('icon') }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $paths[$name] !!}</svg>
