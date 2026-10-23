<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SearchBookingRequest;
use Illuminate\View\View;
use App\Models\Booking;

class BookingController extends Controller
{
    /** get methods */

    public function showSearch(): View
    {
        return view('admin.booking.search');
    }

    public function searchResult(SearchBookingRequest $request): View
    {
        $var = [];

        $bookingList = Booking::getBookingList($request->validated());

        $var['bookingList'] = $bookingList;

        return view('admin.booking.search', $var);
    }
}
