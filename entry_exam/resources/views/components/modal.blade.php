{{--
    Dialog shown over the page

    Rendered open by the server (closing goes to a URL):
    <x-modal :title="__('...')" :cancel-href="route('...')">body<x-slot:footer>buttons</x-slot:footer></x-modal>

    Opened / closed by JavaScript (resources/js/admin/app.js):
    <x-modal id="confirm-modal" :title="__('...')" :open="false">...</x-modal>
    elements with [data-modal-close] inside close it

    @param string $title
    @param string|null $cancelHref  server-rendered dialog: where the backdrop click / Esc key goes
    @param bool $open               false = hidden until opened by JavaScript
    @slot footer  action buttons, aligned right
--}}
@props([
    'title',
    'cancelHref' => null,
    'open' => true,
])

@php
    $titleId = ($attributes->get('id') ?? 'modal') . '-title';
@endphp

<div
    {{ $attributes->class('modal') }}
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $titleId }}"
    @if (!$open) hidden @endif
    @if ($cancelHref !== null) data-modal-cancel="{{ $cancelHref }}" @endif
>
    @if ($cancelHref !== null)
        <a class="modal__backdrop" href="{{ $cancelHref }}" tabindex="-1" aria-hidden="true"></a>
    @else
        <div class="modal__backdrop" data-modal-close aria-hidden="true"></div>
    @endif
    <div class="modal__dialog">
        <h2 id="{{ $titleId }}" class="heading-2">{{ $title }}</h2>
        <div class="modal__body">{{ $slot }}</div>
        @isset($footer)
            <div class="flex flex-wrap justify-end gap-sm">{{ $footer }}</div>
        @endisset
    </div>
</div>
