<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Contact::factory(30)->create();
        \App\Models\Email::factory(90)->create();
        \App\Models\Phone::factory(90)->create();
    }
}
