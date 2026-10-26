<?php

namespace Tests\Feature\Admin;

use App\Models\Hotel;
use App\Services\HotelImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HotelCreateTest extends AdminTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake(HotelImageService::DISK);
    }

    public function test_create_page_is_shown(): void
    {
        $this->get(route('adminHotelCreatePage'))->assertOk();
    }

    public function test_all_fields_are_required(): void
    {
        $this->post(route('adminHotelCreateProcess'))
            ->assertSessionHasErrors(['hotel_name', 'prefecture_id', 'hotel_image']);

        $this->assertDatabaseCount('hotels', 0);
    }

    public function test_invalid_values_are_rejected(): void
    {
        $this->post(route('adminHotelCreateProcess'), [
            'hotel_name' => str_repeat('a', 256),
            'prefecture_id' => 999,
            'hotel_image' => UploadedFile::fake()->create('hotel.pdf', 10, 'application/pdf'),
        ])->assertSessionHasErrors(['hotel_name', 'prefecture_id', 'hotel_image']);
    }

    public function test_too_large_image_is_rejected(): void
    {
        $this->post(route('adminHotelCreateProcess'), [
            'hotel_name' => 'ホテル',
            'prefecture_id' => 1,
            'hotel_image' => UploadedFile::fake()->image('hotel.jpg')->size(3000),
        ])->assertSessionHasErrors('hotel_image');
    }

    public function test_hotel_is_created_with_its_image(): void
    {
        $this->post(route('adminHotelCreateProcess'), [
            'hotel_name' => '新しいホテル',
            'prefecture_id' => 13,
            'hotel_image' => UploadedFile::fake()->image('hotel.jpg'),
        ])
            ->assertRedirect(route('adminHotelCreatePage'))
            ->assertSessionHas('successMessage');

        $hotel = Hotel::sole();
        $this->assertSame('新しいホテル', $hotel->hotel_name);
        $this->assertSame(13, $hotel->prefecture_id);
        $this->assertStringStartsWith(HotelImageService::DIR.'/', $hotel->file_path);
        Storage::disk(HotelImageService::DISK)->assertExists($hotel->file_path);
    }
}
