<!-- base view -->
@extends('common.admin.base')

<!-- main contents -->
@section('main_contents')
    @php
        $backToInputUrl = route('adminHotelEditPage', ['hotel_id' => $hotel->hotel_id, 'back' => 1]);
    @endphp

    {{-- the input screen stays behind the confirm dialog --}}
    <x-templates.form-page
        :title="__('admin.hotel.edit_title')"
        :back-href="$returnUrl"
        :back-label="__('admin.hotel.action.back_to_search')"
    >
        <x-slot:actions>
            <x-button variant="primary" disabled>{{ __('admin.hotel.action.save') }}</x-button>
        </x-slot:actions>

        @include('admin.hotel.partials.form-fields', [
            'hotelName' => $draft->hotelName,
            'prefectureId' => $draft->prefectureId,
            'imagePath' => $draft->tmpImagePath ?? $hotel->file_path,
            'isImageRequired' => false,
        ])
    </x-templates.form-page>

    {{-- only the changed fields (the controller redirects back when nothing changed) --}}
    <x-modal :title="__('admin.hotel.confirm_title')" :cancel-href="$backToInputUrl">
        <p class="body-2 text-secondary">{{ __('admin.hotel.confirm_message') }}</p>

        @foreach ($changes as $change)
            <div class="flex flex-col gap-xs">
                <p class="label-1 text-secondary">{{ $change['label'] }}</p>
                <p class="body-1">
                    <span class="text-secondary line-through">{{ $change['before'] }}</span>
                    <span class="text-secondary mx-xs">&rarr;</span>
                    <span class="font-semibold">{{ $change['after'] }}</span>
                </p>
            </div>
        @endforeach

        @if ($draft->tmpImagePath !== null)
            <div class="flex flex-col gap-xs">
                <p class="label-1 text-secondary">{{ __('admin.hotel.field.hotel_image') }}</p>
                <div class="flex items-center gap-sm">
                    <img class="modal__thumbnail" src="/assets/img/{{ $hotel->file_path }}" alt="">
                    <span class="text-secondary">&rarr;</span>
                    <img class="modal__thumbnail" src="/assets/img/{{ $draft->tmpImagePath }}" alt="">
                </div>
            </div>
        @endif

        <x-slot:footer>
            <x-button :href="$backToInputUrl">{{ __('admin.hotel.action.cancel') }}</x-button>
            <form action="{{ route('adminHotelEditProcess') }}" method="post">
                @csrf
                <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
                <x-button variant="primary" type="submit">{{ __('admin.hotel.action.update') }}</x-button>
            </form>
        </x-slot:footer>
    </x-modal>
@endsection
