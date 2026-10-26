<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\Hotel;
use App\Services\HotelImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HotelDeleteTest extends AdminTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake(HotelImageService::DISK);
    }

    public function test_hotel_and_its_uploaded_image_are_deleted(): void
    {
        $image = UploadedFile::fake()->image('hotel.jpg')->store(HotelImageService::DIR, HotelImageService::DISK);
        $hotel = Hotel::factory()->create(['file_path' => $image]);

        $this->from(route('adminHotelSearchPage'))
            ->post(route('adminHotelDeleteProcess'), ['hotel_id' => $hotel->hotel_id])
            ->assertRedirect(route('adminHotelSearchPage'))
            ->assertSessionHas('successMessage');

        $this->assertModelMissing($hotel);
        Storage::disk(HotelImageService::DISK)->assertMissing($image);
    }

    public function test_deletion_is_logged(): void
    {
        Log::spy();
        $hotel = Hotel::factory()->create();

        $this->post(route('adminHotelDeleteProcess'), ['hotel_id' => $hotel->hotel_id]);

        Log::shouldHaveReceived('info')->with('Hotel deleted', ['handler' => 'DeleteHotel', 'hotel_id' => $hotel->hotel_id]);
    }

    public function test_shared_sample_image_is_not_deleted(): void
    {
        Storage::disk(HotelImageService::DISK)->put('hoteltype/business.png', 'image');
        $hotel = Hotel::factory()->create(['file_path' => 'hoteltype/business.png']);

        $this->post(route('adminHotelDeleteProcess'), ['hotel_id' => $hotel->hotel_id]);

        $this->assertModelMissing($hotel);
        Storage::disk(HotelImageService::DISK)->assertExists('hoteltype/business.png');
    }

    public function test_hotel_with_bookings_is_not_deleted(): void
    {
        $booking = Booking::factory()->create();

        $this->post(route('adminHotelDeleteProcess'), ['hotel_id' => $booking->hotel_id])
            ->assertSessionHas('errorMessage');

        $this->assertModelExists($booking->hotel);
    }

    public function test_unknown_hotel_shows_an_error(): void
    {
        $this->post(route('adminHotelDeleteProcess'), ['hotel_id' => 999])
            ->assertSessionHas('errorMessage');
    }
}
