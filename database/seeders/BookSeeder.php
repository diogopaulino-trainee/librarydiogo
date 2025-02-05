<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BookSeeder extends Seeder
{
    public function run()
    {
        $this->deleteOldBookImages();
        
        $publishers = Publisher::all();
        $users = User::all();
        $authors = Author::all();

        Book::factory(50)->make()->each(function ($book) use ($publishers, $users, $authors) {
            $book->publisher_id = $publishers->random()->id;
            $book->user_id = $users->random()->id;
            $book->author_id = $authors->random()->id;
            $book->save();
        });
    }

    private function deleteOldBookImages()
    {
        $imagePath = public_path('images');
        $files = File::glob($imagePath . '/book_*.jpg');

        foreach ($files as $file) {
            File::delete($file);
        }
    }
}
