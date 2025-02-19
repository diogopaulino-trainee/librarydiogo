<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BookSeeder extends Seeder
{
    public function run()
    {
        $this->deleteOldBookImages();
        
        $publishers = Publisher::all();
        $users = User::all();
        $authors = Author::all();

        Book::factory(50)->create()->each(function ($book) use ($publishers, $users, $authors) {
            $book->publisher_id = $publishers->random()->id;
            $book->user_id = $users->random()->id;
            $book->save();
        
            $book->authors()->attach($authors->random(rand(1, 3))->pluck('id'));
        });
    }

    private function deleteOldBookImages()
    {
        $imagePath = public_path('images');
        $files = File::glob($imagePath . '/book_*.jpg');

        foreach ($files as $file) {
            File::delete($file);
        }
    }
}

/*
class BookSeeder extends Seeder
{
    public function run()
    {
        $this->deleteOldBookImages();
        $this->fetchAndStoreBooks();
    }

    private function fetchAndStoreBooks()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes', [
            'query' => [
                'q' => 'horror',
                'key' => env('GOOGLE_BOOKS_API_KEY'),
                'maxResults' => 10
            ]
        ]);

        $booksData = json_decode($response->getBody()->getContents(), true);

        if (!empty($booksData['items'])) {
            foreach ($booksData['items'] as $item) {
                if (!empty($item['volumeInfo'])) {
                    $bookData = $item['volumeInfo'];

                    $publisher = Publisher::firstOrCreate(['name' => $bookData['publisher'] ?? 'Unknown Publisher']);

                    if (!$publisher->logo) {
                        $logoPath = $this->downloadImage($this->fetchRandomImage('publisher'), 'publisher');
                        $publisher->update(['logo' => $logoPath]);
                    }

                    $authors = !empty($bookData['authors']) ? $bookData['authors'] : ['Unknown Author'];
                    $user = User::inRandomOrder()->first();

                    $book = Book::create([
                        'isbn' => $bookData['industryIdentifiers'][0]['identifier'] ?? 'N/A',
                        'title' => $bookData['title'] ?? 'No Title',
                        'bibliography' => $bookData['description'] ?? 'No Description',
                        'cover_image' => $this->fetchBookCover($bookData),
                        'price' => rand(10, 100),
                        'publisher_id' => $publisher->id,
                        'user_id' => $user->id ?? 1,
                        'status' => 'available'
                    ]);

                    foreach ($authors as $authorName) {
                        $author = Author::firstOrCreate(
                            ['name' => $authorName],
                            [
                                'photo' => $this->downloadImage($this->fetchRandomImage('author'), 'author'),
                                'user_id' => User::inRandomOrder()->first()->id ?? 1
                            ]
                        );
                    
                        $book->authors()->attach($author->id);
                    }
                }
            }
        } else {
            Log::info('No books found from the Google Books API.');
        }
    }

    private function fetchBookCover($bookData)
    {
        $coverUrl = $bookData['imageLinks']['extraLarge'] 
                ?? $bookData['imageLinks']['large']
                ?? $bookData['imageLinks']['medium']
                ?? $bookData['imageLinks']['thumbnail']
                ?? null;

        if ($coverUrl) {
            return $this->downloadImage($coverUrl, 'book');
        }

        return $this->fetchRandomImage('book');
    }

    private function downloadImage($url, $type)
    {
        try {
            $contents = file_get_contents($url);
            if ($contents === false) {
                throw new \Exception('Falha ao obter a imagem.');
            }

            $name = "{$type}_" . uniqid() . '.jpg';
            $path = public_path('images/' . $name);
            file_put_contents($path, $contents);

            return 'images/' . $name;
        } catch (\Exception $e) {
            Log::error('Erro ao baixar imagem: ' . $e->getMessage());
            return 'images/noimage.png';
        }
    }

    private function fetchRandomImage($type)
    {
        $seed = rand(0, 100000);
        return "https://picsum.photos/seed/{$seed}/3840/2160";
    }

    private function deleteOldBookImages()
    {
        $imagePath = public_path('images');
        $files = File::glob($imagePath . '/book_*.jpg');
        foreach ($files as $file) {
            File::delete($file);
        }
    }
}*/
