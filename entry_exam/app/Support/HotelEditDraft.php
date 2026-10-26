<?php

namespace App\Support;

use App\Models\Hotel;
use App\Models\Prefecture;

/**
 * Content entered on the hotel edit screen, kept in the session
 * between input -> confirm -> complete.
 */
final class HotelEditDraft
{
    private const SESSION_KEY = 'hotelEdit';

    public function __construct(
        public readonly int $hotelId,
        public readonly string $hotelName,
        public readonly int $prefectureId,
        public readonly ?string $tmpImagePath = null,
    ) {}

    /**
     * The draft in the session, null when there is none or it is not in the expected shape
     */
    public static function current(): ?self
    {
        $data = session(self::SESSION_KEY);

        if (! is_array($data) || ! isset($data['hotelId'], $data['hotelName'], $data['prefectureId'])) {
            return null;
        }

        return new self(
            hotelId: (int) $data['hotelId'],
            hotelName: (string) $data['hotelName'],
            prefectureId: (int) $data['prefectureId'],
            tmpImagePath: $data['tmpImagePath'] ?? null,
        );
    }

    /**
     * The draft of the given hotel, null when the session holds none (or another hotel's)
     */
    public static function of(Hotel $hotel): ?self
    {
        $draft = self::current();

        return $draft?->hotelId === $hotel->hotel_id ? $draft : null;
    }

    public static function forget(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function save(): void
    {
        session([self::SESSION_KEY => [
            'hotelId' => $this->hotelId,
            'hotelName' => $this->hotelName,
            'prefectureId' => $this->prefectureId,
            'tmpImagePath' => $this->tmpImagePath,
        ]]);
    }

    /**
     * Name / prefecture that differ from the hotel, for the confirm dialog
     *
     * @return list<array{label: string, before: string, after: string}>
     */
    public function changesFrom(Hotel $hotel): array
    {
        $changes = [];

        if ($this->hotelName !== $hotel->hotel_name) {
            $changes[] = [
                'label' => __('admin.hotel.field.hotel_name'),
                'before' => $hotel->hotel_name,
                'after' => $this->hotelName,
            ];
        }

        if ($this->prefectureId !== $hotel->prefecture_id) {
            $changes[] = [
                'label' => __('admin.hotel.field.prefecture'),
                'before' => $hotel->prefecture->display_name,
                'after' => Prefecture::findOrFail($this->prefectureId)->display_name,
            ];
        }

        return $changes;
    }

    public function hasChangesFrom(Hotel $hotel): bool
    {
        return $this->tmpImagePath !== null || $this->changesFrom($hotel) !== [];
    }
}
