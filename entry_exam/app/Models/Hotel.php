<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    /**
     * Directory (under public/assets/img) for uploaded hotel images
     */
    public const IMAGE_DIR = 'hotel';

    /**
     * Rows per page on the admin search result
     */
    public const PER_PAGE = 20;

    /**
     * @var string
     */
    protected $primaryKey = 'hotel_id';

    /**
     * @var array
     */
    protected $guarded = ['hotel_id'];

    /**
     * @return BelongsTo
     */
    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id', 'prefecture_id');
    }

    /**
     * @return HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'hotel_id', 'hotel_id');
    }

    /**
     * Search hotel by partial hotel name and/or prefecture
     *
     * @param string|null $hotelName
     * @param int|null $prefectureId
     * @return LengthAwarePaginator
     */
    static public function getHotelList(?string $hotelName, ?int $prefectureId): LengthAwarePaginator
    {
        $result = Hotel::query()
            ->when($hotelName !== null, function ($query) use ($hotelName) {
                // escape LIKE wildcards so "%" and "_" are searched literally
                $query->where('hotel_name', 'like', '%' . addcslashes($hotelName, '\\%_') . '%');
            })
            ->when($prefectureId !== null, function ($query) use ($prefectureId) {
                $query->where('prefecture_id', $prefectureId);
            })
            ->with('prefecture')
            ->orderBy('hotel_id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return $result;
    }

    /**
     * Whether the image was uploaded from admin (not a shared sample image)
     *
     * @return bool
     */
    public function hasUploadedImage(): bool
    {
        return $this->file_path !== null && str_starts_with($this->file_path, self::IMAGE_DIR . '/');
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
