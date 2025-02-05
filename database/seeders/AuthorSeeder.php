<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AuthorSeeder extends Seeder
{
    public function run()
    {
        $this->deleteOldAuthorImages();

        $users = User::all();

        Author::factory(10)->make()->each(function ($author) use ($users) {
            $author->user_id = $users->random()->id;
            $author->save();
        });
    }

    private function deleteOldAuthorImages()
    {
        $imagePath = public_path('images');
        $files = File::glob($imagePath . '/author_*.jpg');

        foreach ($files as $file) {
            File::delete($file);
        }
    }
}
