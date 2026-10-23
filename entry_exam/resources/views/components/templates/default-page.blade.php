{{--
    Base page: header (title or custom heading + actions) and content
    <x-templates.default-page title="ホテル検索">
        <x-slot:actions>...</x-slot:actions>
        ...
    </x-templates.default-page>

    @param string|null $title
    @slot header   replaces the title
    @slot actions  buttons on the right of the header
--}}
@props([
    'title' => null,
])

<div {{ $attributes->class('page') }}>
    @if ($title !== null || isset($header) || isset($actions))
        <div class="flex items-end gap-md pb-sm border-b">
            <div class="flex-1 min-w-0">
                @isset($header)
                    {{ $header }}
                @else
                    <h1 class="page__title">{{ $title }}</h1>
                @endisset
            </div>

            @isset($actions)
                <div class="flex items-end justify-end gap-sm">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    {{ $slot }}
</div>
