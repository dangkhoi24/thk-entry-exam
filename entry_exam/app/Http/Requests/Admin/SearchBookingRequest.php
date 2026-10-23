<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchBookingRequest extends FormRequest
{
    /**
     * Go back to the search page with the error message when validation fails
     *
     * @var string
     */
    protected $redirectRoute = 'adminBookingSearchPage';

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * At least one condition is required
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['nullable', 'string', 'max:255', 'required_without_all:customer_contact,checkin_date,checkout_date'],
            'customer_contact' => ['nullable', 'string', 'max:255'],
            'checkin_date' => ['nullable', 'date_format:Y-m-d'],
            'checkout_date' => [
                'nullable',
                'date_format:Y-m-d',
                // compare only when both dates are entered
                Rule::when($this->filled('checkin_date'), 'after_or_equal:checkin_date'),
            ],
        ];
    }
}
