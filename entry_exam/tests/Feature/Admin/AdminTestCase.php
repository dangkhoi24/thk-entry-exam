<?php

namespace Tests\Feature\Admin;

use Database\Seeders\PrefectureSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class AdminTestCase extends TestCase
{
    use RefreshDatabase;

    /**
     * Hotels need the prefecture master data. It is seeded once when the test database
     * is rebuilt (the seeder truncates, so it cannot run inside each test's transaction).
     */
    protected bool $seed = true;

    protected string $seeder = PrefectureSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        app()->setLocale('ja');
    }
}
