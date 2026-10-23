{{--
    List screen: DefaultPage + one card holding the filter, the count and the list
    <x-templates.list-page title="ホテル検索" :is-empty="empty($hotelList)" empty-text="検索結果がありません">
        <x-slot:filter>search form</x-slot:filter>
        <x-slot:status>19件</x-slot:status>
        table
    </x-templates.list-page>

    @param string|null $title
    @param bool $isEmpty      show $emptyText instead of the list
    @param string $emptyText
    @slot actions  header buttons (forwarded to DefaultPage)
    @slot filter   search form, kept visible even when the result is empty
    @slot status   row count etc.
--}}
@props([
    'title' => null,
    'isEmpty' => false,
    'emptyText' => null,
])

@php
    $emptyText ??= __('admin.common.no_data');
@endphp

<x-templates.default-page :title="$title" {{ $attributes }}>
    @isset($actions)
        <x-slot:actions>{{ $actions }}</x-slot:actions>
    @endisset

    <x-card class="flex flex-col flex-1 gap-md">
        @isset($filter)
            <div>{{ $filter }}</div>
        @endisset

        @if ($isEmpty)
            <p class="flex flex-1 items-center justify-center body-2 text-secondary text-center py-xl">{{ $emptyText }}</p>
        @else
            @isset($status)
                <div class="label-1 text-secondary">{{ $status }}</div>
            @endisset

            {{ $slot }}
        @endif
    </x-card>
</x-templates.default-page>
