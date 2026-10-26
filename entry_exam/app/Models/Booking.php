<?php

namespace App\Models;

use App\Models\Concerns\HasContainsScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasContainsScope;
    use HasFactory;

    /**
     * Rows per page on the admin search result
     */
    public const PER_PAGE = 20;

    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'hotel_id',
        'customer_name',
        'customer_contact',
        'checkin_time',
        'checkout_time',
    ];

    protected function casts(): array
    {
        return [
            'hotel_id' => 'integer',
            'checkin_time' => 'datetime',
            'checkout_time' => 'datetime',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }

    /**
     * Search booking by customer name, contact, check-in date and check-out date
     *
     * @param  array{customer_name?: ?string, customer_contact?: ?string, checkin_date?: ?string, checkout_date?: ?string}  $filter
     */
    public static function getBookingList(array $filter): LengthAwarePaginator
    {
        $checkinDate = $filter['checkin_date'] ?? null;
        $checkoutDate = $filter['checkout_date'] ?? null;

        return self::query()
            ->whereContains('customer_name', $filter['customer_name'] ?? null)
            ->whereContains('customer_contact', $filter['customer_contact'] ?? null)
            ->when($checkinDate !== null, fn ($query) => $query->whereDate('checkin_time', $checkinDate))
            ->when($checkoutDate !== null, fn ($query) => $query->whereDate('checkout_time', $checkoutDate))
            ->orderBy('checkin_time')
            ->paginate(self::PER_PAGE)
            ->withQueryString();
    }

    /**
     * Override serializeDate method to customize date format
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
