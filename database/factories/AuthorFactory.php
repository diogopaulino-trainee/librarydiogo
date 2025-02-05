<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'photo' => $this->downloadImage($this->faker->imageUrl(200, 200, 'people', true)),
            'user_id' => User::inRandomOrder()->first()?->id,
        ];
    }

    private function downloadImage($url)
    {
        try {
            $contents = file_get_contents($url);
            $name = 'author_' . uniqid() . '.jpg';
            $path = public_path('images/' . $name);

            file_put_contents($path, $contents);
            return 'images/' . $name;
        } catch (\Exception $e) {
            return 'noimage.png';
        }
    }
}
