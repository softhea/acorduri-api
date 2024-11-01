<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Snapshots\MatchesSnapshots;
use Tests\TestCase;

class CreateArtistTest extends TestCase
{
    use DatabaseTransactions;
    use MatchesSnapshots;

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

        $id = $artist->getId();

        $artist->makeHidden(['id', 'created_at', 'updated_at']);

        $this->assertMatchesJsonSnapshot($artist->toArray());

        $response = $response->json();

        $this->assertSame($id, $response['data']['id']);
        unset($response['data']['id']);

        $this->assertMatchesJsonSnapshot(actual: $response);

        $user->refresh();

        $this->assertSame(expected: 13, actual: $user->no_of_artists);
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
        $this->assertMatchesJsonSnapshot($response->json());
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
        $this->assertMatchesJsonSnapshot($response->json());       
    }
}
