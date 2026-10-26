<?php

namespace App\Http\Requests\Admin;

class EditHotelRequest extends CreateHotelRequest
{
    /**
     * Same as create, but the image is optional (keep current image when empty)
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'hotel_id' => ['required', 'integer', 'exists:hotels,hotel_id'],
            'hotel_image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];
    }
}
