{{--
    Dialog shown over the page (rendered open by the server)
    <x-modal :title="__('...')" :cancel-href="route('...')">
        body
        <x-slot:footer>buttons</x-slot:footer>
    </x-modal>

    @param string $title
    @param string|null $cancelHref  where the backdrop click / Esc key goes
    @slot footer  action buttons, aligned right
--}}
@props([
    'title',
    'cancelHref' => null,
])

<div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" @if ($cancelHref !== null) data-modal-cancel="{{ $cancelHref }}" @endif>
    <a class="modal__backdrop" @if ($cancelHref !== null) href="{{ $cancelHref }}" @endif tabindex="-1" aria-hidden="true"></a>
    <div class="modal__dialog">
        <h2 id="modal-title" class="heading-2">{{ $title }}</h2>
        <div class="modal__body">{{ $slot }}</div>
        @isset($footer)
            <div class="flex flex-wrap justify-end gap-sm">{{ $footer }}</div>
        @endisset
    </div>
</div>
