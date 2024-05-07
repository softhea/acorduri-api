<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtistRequest;
use App\Http\Resources\ArtistResource;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection
    {
        $search = $request->input("search");

        $artists = Artist::query();
        if (null !== $search) {
            $artists = $artists->whereFullText(Artist::COLUMN_NAME, $search);    
        }
        $artists = $artists->orderBy(Artist::COLUMN_NAME)->get();

        return ArtistResource::collection($artists);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArtistRequest $request): ArtistResource
    {
        /** @var Artist $artist */
        $artist = Artist::query()->create($request->validated());

        if (null !== $artist->getUserId()) {
            $artist->getUser()->increaseNoOfArtists();
        }

        return new ArtistResource($artist);
    }

    /**
     * Display the specified resource.
     */
    public function show(Artist $artist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artist $artist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artist $artist)
    {
        //
    }
}
