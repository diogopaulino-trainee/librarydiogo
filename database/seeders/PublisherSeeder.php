<?php

namespace Database\Seeders;

use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PublisherSeeder extends Seeder
{
    public function run()
    {
        $this->deleteOldPublisherImages();

        $users = User::all();

        Publisher::factory(15)->make()->each(function ($publisher) use ($users) {
            $publisher->user_id = $users->random()->id;
            $publisher->save();
        });
    }

    private function deleteOldPublisherImages()
    {
        $imagePath = public_path('images');
        $files = File::glob($imagePath . '/publisher_*.jpg');

        foreach ($files as $file) {
            File::delete($file);
        }
    }
}
