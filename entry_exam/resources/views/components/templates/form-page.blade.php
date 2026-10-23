{{--
    Form screen (like artech-client's FormPage): back button + title, actions at the top right, content card
    <x-templates.form-page title="ホテル編集" :back-href="route('...')" :back-label="__('...')">
        <x-slot:actions><x-button variant="primary" type="submit" form="hotel-form">確認画面へ</x-button></x-slot:actions>
        form fields
    </x-templates.form-page>

    Action buttons are outside the <form>, so link them with the form="..." attribute.

    @param string|null $title
    @param string|null $backHref   shows the back button when given
    @param string|null $backLabel  text of the back button
    @slot actions  buttons at the top right
    @slot footer   buttons under the content, aligned right
--}}
@props([
    'title' => null,
    'backHref' => null,
    'backLabel' => null,
])

<x-templates.default-page {{ $attributes }}>
    <x-slot:header>
        <div class="flex flex-col items-start gap-xs">
            @if ($backHref !== null)
                <x-button variant="tertiary" size="small" :href="$backHref">
                    <x-icon name="arrow-left" />{{ $backLabel }}
                </x-button>
            @endif
            <h1 class="page__title">{{ $title }}</h1>
        </div>
    </x-slot:header>

    @isset($actions)
        <x-slot:actions>{{ $actions }}</x-slot:actions>
    @endisset

    <x-card class="flex flex-col flex-1 gap-lg">
        {{ $slot }}

        @isset($footer)
            <div class="flex flex-wrap justify-end gap-sm">{{ $footer }}</div>
        @endisset
    </x-card>
</x-templates.default-page>
