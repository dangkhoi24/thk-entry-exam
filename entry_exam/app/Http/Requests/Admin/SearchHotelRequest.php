<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SearchHotelRequest extends FormRequest
{
    /**
     * Go back to the search page with the error message when validation fails
     *
     * @var string
     */
    protected $redirectRoute = 'adminHotelSearchPage';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * At least one of hotel name or prefecture is required
     */
    public function rules(): array
    {
        return [
            'hotel_name' => ['nullable', 'string', 'max:255', 'required_without:prefecture_id'],
            'prefecture_id' => ['nullable', 'integer', 'exists:prefectures,prefecture_id'],
        ];
    }
}
