<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // TRUNCATE DATABASE
        Schema::disableForeignKeyConstraints(); // invalid foreign key constraints
        DB::table('bookings')->truncate();
        Schema::enableForeignKeyConstraints(); // valid foreign key constraints

        $faker = fake('ja_JP');
        $hotelIds = DB::table('hotels')->pluck('hotel_id');

        $booking_data = [];
        for ($i = 0; $i < 50; $i++) {
            // check-in at 15:00, stay 1-5 nights, check-out at 10:00
            $checkin = (new DateTime($faker->dateTimeBetween('-1 month', '+2 months')->format('Y-m-d')))->setTime(15, 0);
            $checkout = (clone $checkin)->modify('+'.$faker->numberBetween(1, 5).' days')->setTime(10, 0);
            // booked within 2 months before check-in, and not in the future
            $bookedUntil = min($checkin, new DateTime);
            $createdAt = $faker->dateTimeBetween((clone $bookedUntil)->modify('-2 months'), $bookedUntil);

            $booking_data[] = [
                'hotel_id' => $hotelIds->random(),
                'customer_name' => $faker->name(),
                'customer_contact' => $faker->boolean() ? $faker->phoneNumber() : $faker->safeEmail(),
                'checkin_time' => $checkin,
                'checkout_time' => $checkout,
                'created_at' => $createdAt,
                'updated_at' => $faker->dateTimeBetween($createdAt, 'now'),
            ];
        }

        // INSERT INTO DATABASE
        DB::table('bookings')->insert($booking_data);
    }
}
