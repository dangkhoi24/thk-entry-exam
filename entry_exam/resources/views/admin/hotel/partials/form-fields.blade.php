{{--
    Hotel input fields shared by create and edit
    @param Collection $prefectures
    @param string|null $hotelName
    @param int|null $prefectureId
    @param string|null $imagePath  current image (relative to /assets/img)
    @param bool $isImageRequired
--}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
    <x-form-field :label="__('admin.hotel.field.hotel_name')" name="hotel_name" required>
        <x-input
            name="hotel_name"
            :value="$hotelName"
            maxlength="255"
            :placeholder="__('admin.hotel.placeholder.hotel_name')"
        />
    </x-form-field>

    <x-form-field :label="__('admin.hotel.field.prefecture')" name="prefecture_id" required>
        <x-select
            name="prefecture_id"
            :options="$prefectures->pluck('display_name', 'prefecture_id')"
            :selected="$prefectureId"
            :placeholder="__('admin.common.select_placeholder')"
        />
    </x-form-field>

    <x-form-field
        class="md:col-span-2"
        :label="__('admin.hotel.field.hotel_image')"
        name="hotel_image"
        :required="$isImageRequired"
        :description="$imagePath ? __('admin.hotel.image_keep_hint') : __('admin.hotel.image_hint')"
    >
        @if ($imagePath)
            <img class="block max-w-xs rounded-md border" src="/assets/img/{{ $imagePath }}" alt="{{ __('admin.hotel.current_image') }}">
        @endif
        <x-input type="file" name="hotel_image" accept="image/jpeg,image/png,image/webp" />
    </x-form-field>
</div>
