<?php

namespace Database\Seeders;

use App\Models\Artist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artist::create([
            "id" => 1,
            "user_id" => 1,
            "name" => "Abba",
            "no_of_views" => 101,
            "no_of_tabs" => 2,
        ]);

        Artist::create([
            "id" => 2,
            "user_id" => 1,
            "name" => "Rolling Stones",
            "no_of_views" => 253,
            "no_of_tabs" => 8,
        ]);

        Artist::create([
            "id" => 3,
            "user_id" => 2,
            "name" => "The Doors",
            "no_of_views" => 56,
            "no_of_tabs" => 2,
        ]);
    }
}
