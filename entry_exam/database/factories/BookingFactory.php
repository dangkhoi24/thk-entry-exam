<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkin = fake()->dateTimeBetween('+1 day', '+1 month')->setTime(15, 0);

        return [
            'hotel_id' => Hotel::factory(),
            'customer_name' => fake()->name(),
            'customer_contact' => fake()->safeEmail(),
            'checkin_time' => $checkin,
            'checkout_time' => (clone $checkin)->modify('+1 day')->setTime(10, 0),
        ];
    }
}
