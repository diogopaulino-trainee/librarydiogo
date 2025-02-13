<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'photo' => $this->downloadImage('https://picsum.photos/seed/' . rand(0, 100000) . '/1200/1200'),
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

            $name = 'author_' . uniqid() . '.jpg';
            $path = public_path('images/' . $name);
            file_put_contents($path, $contents);

            return $name;
        } catch (\Exception $e) {
            Log::error('Erro ao fazer download da imagem: ' . $e->getMessage());
            return 'noimage.png';
        }
    }
}
