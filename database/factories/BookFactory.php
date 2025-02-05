<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        return [
            'isbn' => $this->faker->unique()->isbn13,
            'title' => $this->faker->sentence,
            'bibliography' => $this->faker->paragraph,
            'cover_image' => $this->downloadImage($this->faker->imageUrl(200, 300, 'books', true)),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'author_id' => Author::inRandomOrder()->first()?->id,
            'publisher_id' => Publisher::inRandomOrder()->first()?->id,
            'user_id' => User::inRandomOrder()->first()?->id,
        ];
    }

    private function downloadImage($url)
    {
        try {
            $contents = file_get_contents($url);
            $name = 'book_' . uniqid() . '.jpg';
            $path = public_path('images/' . $name);

            file_put_contents($path, $contents);
            return 'images/' . $name;
        } catch (\Exception $e) {
            return 'noimage.png';
        }
    }
}
