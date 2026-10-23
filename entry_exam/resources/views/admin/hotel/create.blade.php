<!-- base view -->
@extends('common.admin.base')

<!-- main contents -->
@section('main_contents')
    <x-templates.form-page :title="__('admin.hotel.create_title')">
        <form id="hotel-create-form" action="{{ route('adminHotelCreateProcess') }}" method="post" enctype="multipart/form-data">
            @csrf
            @include('admin.hotel.partials.form-fields', [
                'hotelName' => old('hotel_name'),
                'prefectureId' => old('prefecture_id'),
                'imagePath' => null,
                'isImageRequired' => true,
            ])
        </form>

        <x-slot:footer>
            <x-button variant="primary" type="submit" form="hotel-create-form">{{ __('admin.hotel.action.register') }}</x-button>
        </x-slot:footer>
    </x-templates.form-page>
@endsection
