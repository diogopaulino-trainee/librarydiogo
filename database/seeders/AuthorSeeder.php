<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        Author::factory(25)->make()->each(function ($author) use ($users) {
            $author->user_id = $users->random()->id;
            $author->save();
        });
    }
}
