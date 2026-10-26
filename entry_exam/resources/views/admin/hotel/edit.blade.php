<!-- base view -->
@extends('common.admin.base')

<!-- main contents -->
@section('main_contents')
    @php
        // the save button is enabled from the start only when there is an unsaved edit
        $isDirty = session()->hasOldInput() || ($draft?->hasChangesFrom($hotel) ?? false);
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
                'hotelName' => old('hotel_name', $draft?->hotelName ?? $hotel->hotel_name),
                'prefectureId' => old('prefecture_id', $draft?->prefectureId ?? $hotel->prefecture_id),
                'imagePath' => $draft?->tmpImagePath ?? $hotel->file_path,
                'isImageRequired' => false,
            ])
        </form>
    </x-templates.form-page>
@endsection
