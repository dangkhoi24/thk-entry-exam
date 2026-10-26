<?php

namespace Tests\Feature\Admin;

use App\Models\Hotel;

class HotelSearchTest extends AdminTestCase
{
    public function test_search_page_is_shown(): void
    {
        $this->get(route('adminHotelSearchPage'))
            ->assertOk()
            ->assertSee(__('admin.hotel.search_hint'));
    }

    public function test_empty_search_shows_an_error_below_the_form(): void
    {
        $this->get(route('adminHotelSearchResult', ['hotel_name' => '', 'prefecture_id' => '']))
            ->assertRedirect(route('adminHotelSearchPage'))
            ->assertSessionHasErrors(['hotel_name' => '何も入力されていません']);

        $this->get(route('adminHotelSearchPage'))->assertSee('何も入力されていません');
    }

    public function test_hotel_name_is_partially_matched(): void
    {
        Hotel::factory()->create(['hotel_name' => 'ビジネスホテル 東京 A']);
        Hotel::factory()->create(['hotel_name' => 'シティホテル 大阪']);

        $this->get(route('adminHotelSearchResult', ['hotel_name' => '東京']))
            ->assertOk()
            ->assertSee('ビジネスホテル 東京 A')
            ->assertDontSee('シティホテル 大阪');
    }

    public function test_like_wildcards_are_searched_literally(): void
    {
        Hotel::factory()->create(['hotel_name' => 'A_B']);
        Hotel::factory()->create(['hotel_name' => 'AXB']);

        $this->get(route('adminHotelSearchResult', ['hotel_name' => '_']))
            ->assertSee('A_B')
            ->assertDontSee('AXB');
    }

    public function test_hotels_can_be_searched_by_prefecture(): void
    {
        Hotel::factory()->create(['hotel_name' => 'in hokkaido', 'prefecture_id' => 1]);
        Hotel::factory()->create(['hotel_name' => 'in aomori', 'prefecture_id' => 2]);

        $this->get(route('adminHotelSearchResult', ['prefecture_id' => 2]))
            ->assertSee('in aomori')
            ->assertDontSee('in hokkaido');
    }

    public function test_invalid_prefecture_is_rejected(): void
    {
        $this->get(route('adminHotelSearchResult', ['prefecture_id' => 999]))
            ->assertSessionHasErrors('prefecture_id');
    }

    public function test_results_are_paginated(): void
    {
        Hotel::factory()->count(Hotel::PER_PAGE + 5)->create();

        $this->get(route('adminHotelSearchResult', ['prefecture_id' => 1]))
            ->assertViewHas('hotelList', fn ($hotelList) => $hotelList->count() === Hotel::PER_PAGE
                && $hotelList->total() === Hotel::PER_PAGE + 5);
    }
}
