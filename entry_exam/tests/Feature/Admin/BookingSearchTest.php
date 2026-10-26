<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;

class BookingSearchTest extends AdminTestCase
{
    public function test_search_page_is_shown(): void
    {
        $this->get(route('adminBookingSearchPage'))->assertOk();
    }

    public function test_empty_search_shows_an_error(): void
    {
        $this->get(route('adminBookingSearchResult'))
            ->assertRedirect(route('adminBookingSearchPage'))
            ->assertSessionHasErrors(['customer_name' => '何も入力されていません']);
    }

    public function test_bookings_can_be_searched_by_each_condition(): void
    {
        $target = Booking::factory()->create([
            'customer_name' => '山田 太郎',
            'customer_contact' => 'yamada@example.com',
            'checkin_time' => '2030-01-10 15:00:00',
            'checkout_time' => '2030-01-12 10:00:00',
        ]);
        $other = Booking::factory()->create([
            'customer_name' => '佐藤 花子',
            'customer_contact' => '090-0000-0000',
            'checkin_time' => '2030-02-01 15:00:00',
            'checkout_time' => '2030-02-02 10:00:00',
        ]);

        $conditions = [
            ['customer_name' => '山田'],
            ['customer_contact' => 'yamada@'],
            ['checkin_date' => '2030-01-10'],
            ['checkout_date' => '2030-01-12'],
        ];

        foreach ($conditions as $condition) {
            $this->get(route('adminBookingSearchResult', $condition))
                ->assertOk()
                ->assertSee($target->customer_name)
                ->assertDontSee($other->customer_name);
        }
    }

    public function test_result_shows_the_required_columns(): void
    {
        Booking::factory()->create(['customer_name' => '山田 太郎']);

        $this->get(route('adminBookingSearchResult', ['customer_name' => '山田']))
            ->assertSeeInOrder([
                __('admin.booking.field.customer_name'),
                __('admin.booking.field.customer_contact'),
                __('admin.booking.field.checkin_time'),
                __('admin.booking.field.checkout_time'),
                __('admin.booking.field.created_at'),
                __('admin.booking.field.updated_at'),
            ]);
    }

    public function test_checkout_before_checkin_is_rejected(): void
    {
        $this->get(route('adminBookingSearchResult', ['checkin_date' => '2030-01-10', 'checkout_date' => '2030-01-09']))
            ->assertSessionHasErrors('checkout_date');
    }
}
