<!-- base view -->
@extends('common.admin.base')

<!-- main contents -->
@section('main_contents')
    <x-templates.form-page
        :title="__('admin.hotel.edit_title')"
        :back-href="$returnUrl"
        :back-label="__('admin.hotel.action.back_to_search')"
    >
        <div class="flex flex-col flex-1 items-center justify-center gap-sm py-xl text-center">
            <x-icon name="check-circle" class="text-success" />
            <p class="headline-1">{{ __('admin.hotel.message.updated', ['name' => $hotel->hotel_name]) }}</p>
            <a class="body-2 text-secondary" href="{{ route('hotelDetail', ['hotel_id' => $hotel->hotel_id]) }}" target="_blank">{{ __('admin.hotel.action.view_detail') }}</a>
        </div>
    </x-templates.form-page>
@endsection
