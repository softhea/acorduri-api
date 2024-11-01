<?php

declare(strict_types=1);

namespace Tests\Feature;

use Spatie\Snapshots\MatchesSnapshots;
use Tests\TestCase;

class ListArtistsTest extends TestCase
{
    use MatchesSnapshots;

    public function test_get_all_artists(): void
    {
        $response = $this->get('/artists');

        $response->assertOk();
        $this->assertMatchesJsonSnapshot($response->json());
    }

    public function test_get_filtered_artists(): void
    {
        $response = $this->get('/artists?search=rolling');

        $response->assertOk();
        $this->assertMatchesJsonSnapshot($response->json());
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
