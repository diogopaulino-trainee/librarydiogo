<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        return [
            'isbn' => $this->faker->unique()->isbn13,
            'title' => $this->faker->sentence,
            'bibliography' => $this->faker->paragraph,
            'cover_image' => $this->downloadImage('https://picsum.photos/seed/' . rand(0, 100000) . '/3840/2160'),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'publisher_id' => Publisher::inRandomOrder()->first()?->id,
            'user_id' => 1,
            'status' => 'available',
        ];
    }

    protected static function newFactory()
    {
        return parent::newFactory()->afterCreating(function (Book $book) {
            $authors = Author::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $book->authors()->attach($authors);
        });
    }

    private function downloadImage($url)
    {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $contents = curl_exec($ch);
            curl_close($ch);

            if ($contents === false) {
                throw new \Exception('Falha ao obter a imagem.');
            }

            $name = 'book_' . uniqid() . '.jpg';
            $path = public_path('images/' . $name);
            file_put_contents($path, $contents);

            return $name;
        } catch (\Exception $e) {
            Log::error('Erro ao fazer download da imagem: ' . $e->getMessage());
            return 'noimage.png';
        }
    }
}
