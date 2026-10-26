<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SearchBookingRequest;
use App\Models\Booking;
use Illuminate\View\View;

class BookingController extends Controller
{
    /** get methods */
    public function showSearch(): View
    {
        return view('admin.booking.search');
    }

    public function searchResult(SearchBookingRequest $request): View
    {
        return view('admin.booking.search', [
            'bookingList' => Booking::getBookingList($request->validated()),
        ]);
    }
}
