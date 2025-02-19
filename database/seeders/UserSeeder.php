<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        Storage::disk('public')->deleteDirectory('profile-photos');
        Storage::disk('public')->makeDirectory('profile-photos');
        
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Citizen']);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->assignPhoto($admin);
        $admin->assignRole('Admin');

        $citizen = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->assignPhoto($citizen);
        $citizen->assignRole('Citizen');

        User::factory(20)->create()->each(function ($user) {
            $this->assignPhoto($user);
            $user->assignRole('Citizen');
        });
    }

    private function assignPhoto($user)
    {
        $seed = rand(0, 100000);
        $imageUrl = 'https://picsum.photos/seed/'.$seed.'/3840/2160';
        $imageData = file_get_contents($imageUrl);

        $filename = Str::uuid().'.jpg';
        $path = 'profile-photos/'.$filename;

        Storage::disk('public')->put($path, $imageData);

        $user->forceFill([
            'profile_photo_path' => $path,
        ])->save();
    }
}
