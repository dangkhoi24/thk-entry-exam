<?php

namespace Tests\Feature\Admin;

use App\Models\Hotel;
use App\Services\HotelImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HotelEditTest extends AdminTestCase
{
    private Hotel $hotel;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake(HotelImageService::DISK);
        $this->hotel = Hotel::factory()->create([
            'hotel_name' => '元のホテル',
            'prefecture_id' => 1,
        ]);
    }

    public function test_edit_page_is_shown(): void
    {
        $this->get(route('adminHotelEditPage', ['hotel_id' => $this->hotel->hotel_id]))
            ->assertOk()
            ->assertSee('元のホテル');
    }

    public function test_broken_draft_in_the_session_is_ignored(): void
    {
        $this->withSession(['hotelEdit' => ['hotel_id' => $this->hotel->hotel_id, 'tmp_file_path' => null]])
            ->get(route('adminHotelEditPage', ['hotel_id' => $this->hotel->hotel_id, 'back' => 1]))
            ->assertOk()
            ->assertSee('元のホテル');
    }

    public function test_unknown_hotel_goes_back_to_search(): void
    {
        $this->get(route('adminHotelEditPage', ['hotel_id' => 999]))
            ->assertRedirect(route('adminHotelSearchPage'))
            ->assertSessionHas('errorMessage');
    }

    public function test_confirm_screen_lists_only_changed_fields(): void
    {
        $this->confirm(['hotel_name' => '新しい名前'])
            ->assertOk()
            ->assertViewIs('admin.hotel.edit-confirm')
            ->assertViewHas('changes', fn ($changes) => count($changes) === 1
                && $changes[0]['before'] === '元のホテル'
                && $changes[0]['after'] === '新しい名前');

        // not saved yet
        $this->assertSame('元のホテル', $this->hotel->fresh()->hotel_name);
    }

    public function test_no_changes_goes_back_to_the_input_screen(): void
    {
        $this->confirm()
            ->assertRedirect(route('adminHotelEditPage', ['hotel_id' => $this->hotel->hotel_id, 'back' => 1]))
            ->assertSessionHas('infoMessage', __('admin.hotel.no_changes'));
    }

    public function test_invalid_input_is_rejected_on_confirm(): void
    {
        $this->confirm(['hotel_name' => '', 'prefecture_id' => 999])
            ->assertSessionHasErrors(['hotel_name', 'prefecture_id']);
    }

    public function test_back_from_confirm_keeps_the_entered_content(): void
    {
        $this->confirm(['hotel_name' => '入力途中の名前']);

        $this->get(route('adminHotelEditPage', ['hotel_id' => $this->hotel->hotel_id, 'back' => 1]))
            ->assertSee('入力途中の名前');
    }

    public function test_update_saves_the_confirmed_content(): void
    {
        $this->confirm(['hotel_name' => '新しい名前', 'prefecture_id' => 13]);

        $this->post(route('adminHotelEditProcess'), ['hotel_id' => $this->hotel->hotel_id])
            ->assertRedirect(route('adminHotelEditCompletePage'));

        $this->hotel->refresh();
        $this->assertSame('新しい名前', $this->hotel->hotel_name);
        $this->assertSame(13, $this->hotel->prefecture_id);

        $this->get(route('adminHotelEditCompletePage'))
            ->assertOk()
            ->assertSee('新しい名前');
    }

    public function test_new_image_is_kept_temporarily_until_the_update(): void
    {
        $disk = Storage::disk(HotelImageService::DISK);
        $oldImage = UploadedFile::fake()->image('old.jpg')->store(HotelImageService::DIR, HotelImageService::DISK);
        $this->hotel->update(['file_path' => $oldImage]);

        $this->confirm(['hotel_image' => UploadedFile::fake()->image('new.jpg')]);

        $tmpFiles = $disk->files(HotelImageService::TMP_DIR);
        $this->assertCount(1, $tmpFiles);
        $this->assertSame($oldImage, $this->hotel->fresh()->file_path);

        $this->post(route('adminHotelEditProcess'), ['hotel_id' => $this->hotel->hotel_id]);

        $newImage = $this->hotel->fresh()->file_path;
        $this->assertSame(HotelImageService::DIR.'/'.basename($tmpFiles[0]), $newImage);
        $disk->assertExists($newImage);
        $disk->assertMissing($tmpFiles[0]);
        $disk->assertMissing($oldImage);
    }

    public function test_update_without_confirm_is_rejected(): void
    {
        $this->post(route('adminHotelEditProcess'), ['hotel_id' => $this->hotel->hotel_id])
            ->assertRedirect(route('adminHotelSearchPage'))
            ->assertSessionHas('errorMessage');

        $this->assertSame('元のホテル', $this->hotel->fresh()->hotel_name);
    }

    public function test_complete_page_needs_an_update(): void
    {
        $this->get(route('adminHotelEditCompletePage'))
            ->assertRedirect(route('adminHotelSearchPage'));
    }

    /**
     * Submit the input screen with the current values overridden by $input
     */
    private function confirm(array $input = [])
    {
        return $this->post(route('adminHotelEditConfirm'), [
            'hotel_id' => $this->hotel->hotel_id,
            'hotel_name' => $this->hotel->hotel_name,
            'prefecture_id' => $this->hotel->prefecture_id,
            ...$input,
        ]);
    }
}
