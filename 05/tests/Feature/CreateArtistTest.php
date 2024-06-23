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

    public function test_artist_is_created_successfully(): void
    {
        $nameToCreate = 'name_to_create';
        $loggedUserId = 2;

        $user = User::find($loggedUserId);

        $this->actingAs($user);

        $response = $this->postJson(
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

        $user->refresh();

        $this->assertSame(13, $user->no_of_artists);
    }

    public function test_create_artist_fails_when_not_logged(): void
    {
        $response = $this->postJson(
            '/artists'
        );

        $response->assertUnauthorized();
        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function test_create_artist_fails_when_name_is_missing(): void
    {
        $user = User::find(2);

        $this->actingAs($user);

        $response = $this->postJson(
            '/artists'
        );

        $response->assertUnprocessable();
        $response->assertJson([
            'message' => 'Name is required',
            'errors' => [
                'name' => [
                    'Name is required',
                ],
            ],
        ]);        
    }

    public function test_create_artist_fails_when_name_already_exists(): void
    {
        $user = User::find(2);

        $this->actingAs($user);

        $response = $this->postJson(
            '/artists',
            [
                'name' => 'Abba',
            ]
        );

        $response->assertUnprocessable();
        $response->assertJson([
            'message' => 'Name already exists',
            'errors' => [
                'name' => [
                    'Name already exists',
                ],
            ],
        ]);        
    }
}
