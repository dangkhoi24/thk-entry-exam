<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hotel_name' => ['required', 'string', 'max:255'],
            'prefecture_id' => ['required', 'integer', 'exists:prefectures,prefecture_id'],
            'hotel_image' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];
    }
}
