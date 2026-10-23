<!-- base view -->
@extends('common.admin.base')

<!-- main containts -->
@section('main_contents')
    @php
        $isSearched = isset($hotelList);
    @endphp

    <x-templates.list-page
        :title="__('admin.hotel.search_title')"
        :is-empty="!$isSearched || $hotelList->isEmpty()"
        :empty-text="$isSearched ? __('admin.common.no_result') : __('admin.hotel.search_hint')"
    >
        <x-slot:filter>
            <form class="flex flex-col gap-sm" action="{{ route('adminHotelSearchResult') }}" method="get">
                <div class="flex flex-col md:flex-row md:items-end gap-sm">
                    <x-form-field class="flex-1" :label="__('admin.hotel.field.hotel_name')" for="hotel_name">
                        <x-input
                            name="hotel_name"
                            icon="search"
                            :value="old('hotel_name', request('hotel_name'))"
                            :placeholder="__('admin.hotel.placeholder.hotel_name_search')"
                        />
                    </x-form-field>
                    <x-form-field class="flex-1" :label="__('admin.hotel.field.prefecture')" for="prefecture_id">
                        <x-select
                            name="prefecture_id"
                            :options="$prefectures->pluck('display_name', 'prefecture_id')"
                            :selected="old('prefecture_id', request('prefecture_id'))"
                            :placeholder="__('admin.common.select_placeholder')"
                        />
                    </x-form-field>
                    <x-button variant="primary" type="submit">{{ __('admin.common.search') }}</x-button>
                </div>
                @foreach ($errors->all() as $error)
                    <p class="body-2 text-danger" role="alert">{{ $error }}</p>
                @endforeach
            </form>
        </x-slot:filter>

        @if ($isSearched)
            <x-slot:status>{{ trans_choice('admin.common.count', $hotelList->total(), ['count' => $hotelList->total()]) }}</x-slot:status>

            <div class="data-table">
                <table class="data-table__table">
                    <thead>
                        <tr>
                            <th class="data-table__head">{{ __('admin.hotel.field.hotel_name') }}</th>
                            <th class="data-table__head hidden md:table-cell">{{ __('admin.hotel.field.prefecture') }}</th>
                            <th class="data-table__head hidden lg:table-cell">{{ __('admin.hotel.field.created_at') }}</th>
                            <th class="data-table__head hidden md:table-cell text-right">{{ __('admin.hotel.field.updated_at') }}</th>
                            <th class="data-table__head"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hotelList as $hotel)
                            <tr>
                                <td class="data-table__cell">
                                    <div class="data-table__media">
                                        <img src="/assets/img/{{ $hotel->file_path }}" alt="" loading="lazy">
                                        <a class="data-table__link" href="{{ route('hotelDetail', ['hotel_id' => $hotel->hotel_id]) }}" target="_blank">{{ $hotel->hotel_name }}</a>
                                    </div>
                                </td>
                                <td class="data-table__cell hidden md:table-cell whitespace-nowrap">{{ $hotel->prefecture->display_name }}</td>
                                <td class="data-table__cell hidden lg:table-cell whitespace-nowrap text-secondary">{{ $hotel->created_at->format('Y-m-d H:i') }}</td>
                                <td class="data-table__cell hidden md:table-cell whitespace-nowrap text-secondary text-right">{{ $hotel->updated_at->format('Y-m-d H:i') }}</td>
                                <td class="data-table__cell data-table__cell--action">
                                    <div class="data-table__actions">
                                        <x-button
                                            variant="tertiary"
                                            size="small"
                                            class="is-icon-only"
                                            :href="route('adminHotelEditPage', ['hotel_id' => $hotel->hotel_id])"
                                            :aria-label="__('admin.hotel.action.edit')"
                                            :title="__('admin.hotel.action.edit')"
                                        ><x-icon name="edit" /></x-button>
                                        <form
                                            class="js-delete-form"
                                            action="{{ route('adminHotelDeleteProcess') }}"
                                            method="post"
                                            data-confirm-message="{{ __('admin.hotel.delete_confirm', ['name' => $hotel->hotel_name]) }}"
                                        >
                                            @csrf
                                            <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
                                            <x-button
                                                variant="tertiary"
                                                size="small"
                                                class="is-icon-only"
                                                type="submit"
                                                :aria-label="__('admin.hotel.action.delete')"
                                                :title="__('admin.hotel.action.delete')"
                                            ><x-icon name="trash" /></x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$hotelList" />
        @endif
    </x-templates.list-page>
@endsection

@section('page_js')
    <script>
        // ask before deleting; submit only when "OK" is selected
        document.querySelectorAll('.js-delete-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!window.confirm(form.dataset.confirmMessage)) {
                    event.preventDefault();
                }
            });
        });
    </script>
@endsection
