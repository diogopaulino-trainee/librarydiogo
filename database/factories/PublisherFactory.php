<?php

namespace Database\Factories;

use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublisherFactory extends Factory
{
    protected $model = Publisher::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'logo' => $this->downloadLogo($this->faker->imageUrl(200, 100, 'business', true)),
            'user_id' => User::inRandomOrder()->first()?->id,
        ];
    }

    private function downloadLogo($url)
    {
        try {
            $contents = file_get_contents($url);
            $name = 'publisher_' . uniqid() . '.jpg';
            $path = public_path('images/' . $name);

            file_put_contents($path, $contents);
            return 'images/' . $name;
        } catch (\Exception $e) {
            return 'noimage.png';
        }
    }
} 
