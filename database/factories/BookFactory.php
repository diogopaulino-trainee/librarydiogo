<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        return [
            'isbn' => $this->faker->unique()->isbn13(),
            'name' => $this->faker->sentence(3),
            'bibliography' => $this->faker->paragraph(),
            'cover_image' => $this->faker->imageUrl(200, 300, 'books', true),
            'price' => $this->faker->randomFloat(2, 5, 100),
        ];
    }
}
