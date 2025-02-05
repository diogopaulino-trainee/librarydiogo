<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
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
            'cover_image' => $this->downloadImage('https://picsum.photos/seed/' . rand(0, 100000) . '/150/150'),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'author_id' => Author::inRandomOrder()->first()?->id,
            'publisher_id' => Publisher::inRandomOrder()->first()?->id,
            'user_id' => User::inRandomOrder()->first()?->id,
        ];
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
