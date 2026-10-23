<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->autoIncrement();
            $table->unsignedBigInteger('hotel_id')->comment('hotel');
            $table->string('customer_name')->comment('customer name　顧客名');
            $table->string('customer_contact')->comment('customer contact　顧客連絡先');
            $table->timestamp('checkin_time')->comment('check-in time　チェックイン日時');
            $table->timestamp('checkout_time')->comment('check-out time　チェックアウト日時');
            $table->timestamps();
            $table->foreign('hotel_id')->references('hotel_id')->on('hotels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
