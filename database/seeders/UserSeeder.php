<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory(10)->create()->each(function ($user) {
            $seed = rand(0, 100000);
            $imageUrl = 'https://picsum.photos/seed/'.$seed.'/300/300';
            $imageData = file_get_contents($imageUrl);

            $filename = Str::uuid().'.jpg';
            $path = 'profile-photos/'.$filename;

            Storage::disk('public')->put($path, $imageData);

            $user->forceFill([
                'profile_photo_path' => $path,
            ])->save();
        });
    }
}
