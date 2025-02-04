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

        Book::factory(20)->make()->each(function ($book) use ($publishers, $users, $authors) {
            $book->publisher_id = $publishers->random()->id;
            $book->user_id = $users->random()->id;
            $book->save();
        
            $randomAuthors = $authors->random(rand(1, 3))->pluck('id')->toArray();
            $book->authors()->attach($randomAuthors);
        });
    }
}
