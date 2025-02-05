<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run()
    {
        $publishers = Publisher::all();
        $users = User::all();
        $authors = Author::all();

        Book::factory(100)->make()->each(function ($book) use ($publishers, $users, $authors) {
            $book->publisher_id = $publishers->random()->id;
            $book->user_id = $users->random()->id;
            $book->author_id = $authors->random()->id;
            $book->save();
        });
    }
}
