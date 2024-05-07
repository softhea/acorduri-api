<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ListArtistsTest extends TestCase
{
    public function test_get_all_artists(): void
    {
        $response = $this->get('/artists');

        $response->assertOk();

        $actualArtists = $response->json()['data'];

        $expectedArtists = [
            [
                "id" => 1,
                "user_id" => 1,
                "name" => "Abba",
                "no_of_tabs" => 2,
                "no_of_views" => 101,
            ],
            [
                "id" => 2,
                "user_id" => 1,
                "name" => "Rolling Stones",
                "no_of_tabs" => 8,
                "no_of_views" => 253,
            ],
            [
                "id" => 3,
                "user_id" => 2,
                "name" => "The Doors",
                "no_of_tabs" => 2,
                "no_of_views" => 56,
            ],
        ];

        $this->assertSame($expectedArtists, $actualArtists);
    }

    public function test_get_filtered_artists(): void
    {
        $response = $this->get('/artists?search=rolling');

        $response->assertOk();

        $actualArtists = $response->json()['data'];

        $expectedArtists = [
            [
                "id" => 2,
                "user_id" => 1,
                "name" => "Rolling Stones",
                "no_of_tabs" => 8,
                "no_of_views" => 253,
            ],
        ];

        $this->assertSame($expectedArtists, $actualArtists);
    }

    public function test_get_returns_no_artist_when_filtered(): void
    {
        $response = $this->get('/artists?search=aaa');

        $response->assertOk();
        $response->assertJson([
            'data' => [],
        ]);
    }
}
