<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateArtistTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_artist(): void
    {
        $nameToCreate = 'name_to_create';
        $loggedUserId = 2;

        $user = User::find($loggedUserId);

        $this->actingAs($user);

        $response = $this->post(
            '/artists',
            [
                'name' => $nameToCreate,
            ]
        );

        $response->assertCreated();

        $artist = Artist::where(Artist::COLUMN_NAME, $nameToCreate)
            ->first();

        $this->assertSame($loggedUserId, $artist->user_id);
        $this->assertSame(0, $artist->no_of_tabs);
        $this->assertSame(0, $artist->no_of_views);

        $response->assertJson([
            'data' => [
                'id' => $artist->id,
                'name' => $nameToCreate,
                'user_id' => $loggedUserId,
                'no_of_tabs' => 0,
                'no_of_views' => 0,
            ],
        ]);
    }
}
