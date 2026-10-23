<!-- base view -->
@extends('common.admin.base')

<!-- main contents -->
@section('main_contents')
    @php
        // the save button is enabled from the start only when there is an unsaved edit
        $isDirty = session()->hasOldInput() || ($draft !== null && (
            $draft['hotel_name'] !== $hotel->hotel_name
            || $draft['prefecture_id'] !== (int) $hotel->prefecture_id
            || $draft['tmp_file_path'] !== null
        ));
    @endphp

    <x-templates.form-page
        :title="__('admin.hotel.edit_title')"
        :back-href="$returnUrl"
        :back-label="__('admin.hotel.action.back_to_search')"
    >
        <x-slot:actions>
            <x-button variant="primary" type="submit" form="hotel-edit-form" :disabled="!$isDirty">{{ __('admin.hotel.action.save') }}</x-button>
        </x-slot:actions>

        <form id="hotel-edit-form" action="{{ route('adminHotelEditConfirm') }}" method="post" enctype="multipart/form-data" data-dirty-check>
            @csrf
            <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
            @include('admin.hotel.partials.form-fields', [
                'hotelName' => old('hotel_name', $draft['hotel_name'] ?? $hotel->hotel_name),
                'prefectureId' => old('prefecture_id', $draft['prefecture_id'] ?? $hotel->prefecture_id),
                'imagePath' => $draft['tmp_file_path'] ?? $hotel->file_path,
                'isImageRequired' => false,
            ])
        </form>
    </x-templates.form-page>
@endsection
