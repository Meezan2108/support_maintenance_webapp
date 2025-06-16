<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeederTest extends TestCase
{
    public function testMigration()
    {
        $this->artisan('migrate');
        $this->assertTrue(true);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSeeder()
    {
        $this->seed();
        $this->assertTrue(true);
    }
}
