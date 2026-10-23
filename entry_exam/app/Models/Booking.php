<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'booking_id';

    /**
     * @var array
     */
    protected $guarded = ['booking_id'];

    /**
     * @var array
     */
    protected $casts = [
        'checkin_time' => 'datetime',
        'checkout_time' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }

    /**
     * Search booking by customer name, contact, check-in date and check-out date
     *
     * @param array $filter keys: customer_name, customer_contact, checkin_date, checkout_date
     * @return LengthAwarePaginator
     */
    static public function getBookingList(array $filter): LengthAwarePaginator
    {
        $customerName = $filter['customer_name'] ?? null;
        $customerContact = $filter['customer_contact'] ?? null;
        $checkinDate = $filter['checkin_date'] ?? null;
        $checkoutDate = $filter['checkout_date'] ?? null;

        $result = Booking::query()
            ->when($customerName !== null, function ($query) use ($customerName) {
                $query->where('customer_name', 'like', '%' . addcslashes($customerName, '\\%_') . '%');
            })
            ->when($customerContact !== null, function ($query) use ($customerContact) {
                $query->where('customer_contact', 'like', '%' . addcslashes($customerContact, '\\%_') . '%');
            })
            ->when($checkinDate !== null, function ($query) use ($checkinDate) {
                $query->whereDate('checkin_time', $checkinDate);
            })
            ->when($checkoutDate !== null, function ($query) use ($checkoutDate) {
                $query->whereDate('checkout_time', $checkoutDate);
            })
            ->orderBy('checkin_time')
            ->paginate(Hotel::PER_PAGE)
            ->withQueryString();

        return $result;
    }

    /**
     * Override serializeDate method to customize date format
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
