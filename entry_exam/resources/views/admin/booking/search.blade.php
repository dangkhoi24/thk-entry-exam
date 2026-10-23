<!-- base view -->
@extends('common.admin.base')

<!-- main contents -->
@section('main_contents')
    @php
        $isSearched = isset($bookingList);
    @endphp

    <x-templates.list-page
        :title="__('admin.booking.search_title')"
        :is-empty="!$isSearched || $bookingList->isEmpty()"
        :empty-text="$isSearched ? __('admin.common.no_result') : __('admin.booking.search_hint')"
    >
        <x-slot:filter>
            <form class="flex flex-col gap-sm" action="{{ route('adminBookingSearchResult') }}" method="get">
                <div class="flex flex-col lg:flex-row lg:items-end gap-sm">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-sm">
                        <x-form-field :label="__('admin.booking.field.customer_name')" for="customer_name">
                            <x-input
                                name="customer_name"
                                icon="search"
                                :value="old('customer_name', request('customer_name'))"
                                :placeholder="__('admin.booking.placeholder.customer_name')"
                            />
                        </x-form-field>
                        <x-form-field :label="__('admin.booking.field.customer_contact')" for="customer_contact">
                            <x-input
                                name="customer_contact"
                                icon="search"
                                :value="old('customer_contact', request('customer_contact'))"
                                :placeholder="__('admin.booking.placeholder.customer_contact')"
                            />
                        </x-form-field>
                        <x-form-field :label="__('admin.booking.field.checkin_date')" for="checkin_date">
                            <x-input
                                name="checkin_date"
                                :value="old('checkin_date', request('checkin_date'))"
                                :placeholder="__('admin.common.date_placeholder')"
                                autocomplete="off"
                                data-date-picker
                            />
                        </x-form-field>
                        <x-form-field :label="__('admin.booking.field.checkout_date')" for="checkout_date">
                            <x-input
                                name="checkout_date"
                                :value="old('checkout_date', request('checkout_date'))"
                                :placeholder="__('admin.common.date_placeholder')"
                                autocomplete="off"
                                data-date-picker
                            />
                        </x-form-field>
                    </div>
                    <x-button variant="primary" type="submit">{{ __('admin.common.search') }}</x-button>
                </div>
                @foreach ($errors->all() as $error)
                    <p class="body-2 text-danger" role="alert">{{ $error }}</p>
                @endforeach
            </form>
        </x-slot:filter>

        @if ($isSearched)
            <x-slot:status>{{ trans_choice('admin.common.count', $bookingList->total(), ['count' => $bookingList->total()]) }}</x-slot:status>

            <div class="data-table">
                <table class="data-table__table">
                    <thead>
                        <tr>
                            <th class="data-table__head">{{ __('admin.booking.field.customer_name') }}</th>
                            <th class="data-table__head">{{ __('admin.booking.field.customer_contact') }}</th>
                            <th class="data-table__head">{{ __('admin.booking.field.checkin_time') }}</th>
                            <th class="data-table__head">{{ __('admin.booking.field.checkout_time') }}</th>
                            <th class="data-table__head">{{ __('admin.booking.field.created_at') }}</th>
                            <th class="data-table__head text-right">{{ __('admin.booking.field.updated_at') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookingList as $booking)
                            <tr>
                                <td class="data-table__cell font-semibold whitespace-nowrap">{{ $booking->customer_name }}</td>
                                <td class="data-table__cell whitespace-nowrap">{{ $booking->customer_contact }}</td>
                                <td class="data-table__cell whitespace-nowrap">{{ $booking->checkin_time->format('Y-m-d H:i') }}</td>
                                <td class="data-table__cell whitespace-nowrap">{{ $booking->checkout_time->format('Y-m-d H:i') }}</td>
                                <td class="data-table__cell whitespace-nowrap text-secondary">{{ $booking->created_at->format('Y-m-d H:i') }}</td>
                                <td class="data-table__cell whitespace-nowrap text-secondary text-right">{{ $booking->updated_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$bookingList" />
        @endif
    </x-templates.list-page>
@endsection
