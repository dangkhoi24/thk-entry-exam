<?php

namespace App\Models;

use App\Models\Concerns\HasContainsScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    use HasContainsScope;
    use HasFactory;

    /**
     * Rows per page on the admin search result
     */
    public const PER_PAGE = 20;

    protected $primaryKey = 'hotel_id';

    protected $fillable = [
        'hotel_name',
        'prefecture_id',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'prefecture_id' => 'integer',
        ];
    }

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id', 'prefecture_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'hotel_id', 'hotel_id');
    }

    /**
     * A null prefecture adds no condition
     */
    public function scopeInPrefecture(Builder $query, ?int $prefectureId): void
    {
        if ($prefectureId !== null) {
            $query->where('prefecture_id', $prefectureId);
        }
    }

    /**
     * Search hotel by partial hotel name and/or prefecture (admin search result)
     */
    public static function getHotelList(?string $hotelName, ?int $prefectureId): LengthAwarePaginator
    {
        return self::query()
            ->whereContains('hotel_name', $hotelName)
            ->inPrefecture($prefectureId)
            ->with('prefecture')
            ->orderBy('hotel_id')
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
