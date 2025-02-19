<?php

namespace Database\Factories;

use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PublisherFactory extends Factory
{
    protected $model = Publisher::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'logo' => $this->downloadLogo('https://picsum.photos/seed/' . rand(0, 100000) . '/3840/2160'),
            'user_id' => User::inRandomOrder()->first()?->id,
        ];
    }

    private function downloadLogo($url)
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

            $name = 'publisher_' . uniqid() . '.jpg';
            $path = public_path('images/' . $name);
            file_put_contents($path, $contents);

            return $name;
        } catch (\Exception $e) {
            Log::error('Erro ao fazer download da imagem: ' . $e->getMessage());
            return 'noimage.png';
        }
    }
} 
