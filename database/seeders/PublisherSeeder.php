<?php

namespace Database\Seeders;

use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        Publisher::factory(25)->make()->each(function ($publisher) use ($users) {
            $publisher->user_id = $users->random()->id;
            $publisher->save();
        });
    }
}
