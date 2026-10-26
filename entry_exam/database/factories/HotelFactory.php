<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Prefectures must exist (PrefectureSeeder)
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hotel_name' => 'ホテル '.fake()->unique()->lastName(),
            'prefecture_id' => 1,
            'file_path' => 'hoteltype/business.png',
        ];
    }
}
