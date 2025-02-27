<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Traits\Loggable;

class GoogleBooksController extends Controller
{
    use Loggable;

    public function searchPage()
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        // Logando a pesquisa do administrador
        $this->logAction('GoogleBooks', 'Searching for books on Google API', 'Admin searched for books using Google Books API.');

        $client = new Client();
        $suggestions = [];

        try {
            $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes', [
                'query' => [
                    'q' => 'laravel',
                    'key' => env('GOOGLE_BOOKS_API_KEY'),
                    'maxResults' => 10
                ]
            ]);

            $booksData = json_decode($response->getBody(), true);

            if (!empty($booksData['items'])) {
                foreach ($booksData['items'] as $item) {
                    $volumeInfo = $item['volumeInfo'];

                    if (!empty($volumeInfo['industryIdentifiers'][0]['identifier'])) {
                        $suggestions[] = [
                            'title' => $volumeInfo['title'] ?? 'No Title',
                            'authors' => $volumeInfo['authors'] ?? ['Unknown Author'],
                            'isbn' => $volumeInfo['industryIdentifiers'][0]['identifier'] ?? null,
                            'cover' => $volumeInfo['imageLinks']['extraLarge'] 
                                    ?? $volumeInfo['imageLinks']['large']
                                    ?? $volumeInfo['imageLinks']['medium']
                                    ?? $volumeInfo['imageLinks']['thumbnail']
                                    ?? asset('images/noimage.png'),
                            'description' => $volumeInfo['description'] ?? 'No description',
                            'publisher' => $volumeInfo['publisher'] ?? 'Unknown Publisher',
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error fetching book suggestions: ' . $e->getMessage());
        }

        return view('admin.books.search', ['books' => [], 'suggestions' => $suggestions]);
    }

    public function search(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $query = $request->input('query', '');
        $client = new Client();
        $books = [];
        $suggestions = [];

        // Logando a busca
        $this->logAction(
            'GoogleBooks', 
            'Searching for books using Google API', 
            "Admin searched for books. Search query: " . $query
        );

        try {
            if (empty($query)) {
                $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes', [
                    'query' => [
                        'q' => 'laravel',
                        'key' => env('GOOGLE_BOOKS_API_KEY'),
                        'maxResults' => 10
                    ]
                ]);

                $booksData = json_decode($response->getBody(), true);

                if (!empty($booksData['items'])) {
                    foreach ($booksData['items'] as $item) {
                        $volumeInfo = $item['volumeInfo'];

                        $isbn = null;
                        if (!empty($volumeInfo['industryIdentifiers'])) {
                            foreach ($volumeInfo['industryIdentifiers'] as $identifier) {
                                if ($identifier['type'] === 'ISBN_13') {
                                    $isbn = $identifier['identifier'];
                                    break;
                                } elseif ($identifier['type'] === 'ISBN_10' && !$isbn) {
                                    $isbn = $identifier['identifier'];
                                }
                            }
                        }

                        $suggestions[] = [
                            'title' => $volumeInfo['title'] ?? 'No Title',
                            'authors' => $volumeInfo['authors'] ?? ['Unknown Author'],
                            'isbn' => $isbn,
                            'cover' => $volumeInfo['imageLinks']['extraLarge'] 
                                    ?? $volumeInfo['imageLinks']['large']
                                    ?? $volumeInfo['imageLinks']['medium']
                                    ?? $volumeInfo['imageLinks']['thumbnail']
                                    ?? asset('images/noimage.png'),
                            'description' => $volumeInfo['description'] ?? 'No description',
                            'publisher' => $volumeInfo['publisher'] ?? 'Unknown Publisher',
                        ];
                    }
                }
            } else {
                $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes', [
                    'query' => [
                        'q' => $query,
                        'key' => env('GOOGLE_BOOKS_API_KEY'),
                        'maxResults' => 10
                    ]
                ]);

                $booksData = json_decode($response->getBody(), true);

                if (!empty($booksData['items'])) {
                    foreach ($booksData['items'] as $item) {
                        $volumeInfo = $item['volumeInfo'];

                        $isbn = null;
                        if (!empty($volumeInfo['industryIdentifiers'])) {
                            foreach ($volumeInfo['industryIdentifiers'] as $identifier) {
                                if ($identifier['type'] === 'ISBN_13') {
                                    $isbn = $identifier['identifier'];
                                    break;
                                } elseif ($identifier['type'] === 'ISBN_10' && !$isbn) {
                                    $isbn = $identifier['identifier'];
                                }
                            }
                        }

                        $books[] = [
                            'title' => $volumeInfo['title'] ?? 'No Title',
                            'authors' => $volumeInfo['authors'] ?? ['Unknown Author'],
                            'isbn' => $isbn,
                            'cover' => $volumeInfo['imageLinks']['extraLarge'] 
                                    ?? $volumeInfo['imageLinks']['large']
                                    ?? $volumeInfo['imageLinks']['medium']
                                    ?? $volumeInfo['imageLinks']['thumbnail']
                                    ?? asset('images/noimage.png'),
                            'description' => $volumeInfo['description'] ?? 'No description',
                            'publisher' => $volumeInfo['publisher'] ?? 'Unknown Publisher',
                        ];
                    }
                }
            }

            return view('admin.books.search', compact('books', 'suggestions'));

        } catch (\Exception $e) {
            return back()->withError('Error fetching books: ' . $e->getMessage())->withInput();
        }
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $bookData = json_decode($request->input('book'), true);

        // Verifica se o livro já existe pelo ISBN
        if (!empty($bookData['isbn'])) {
            $existingBook = Book::where('isbn', $bookData['isbn'])->first();
            if ($existingBook) {
                return back()->with([
                    'error' => 'Book with this ISBN already exists!',
                    'isbn' => $bookData['isbn']
                ]);
            }
        } else {
            // Se não tem ISBN, verifica pelo título e autor
            $query = Book::where('title', $bookData['title']);

            if (!empty($bookData['authors']) && $bookData['authors'][0] !== 'Unknown Author') {
                $query->whereHas('authors', function ($q) use ($bookData) {
                    $q->where('name', $bookData['authors'][0]);
                });
            } else {
                // Para Unknown Author, verificar apenas pelo título
                $query->whereDoesntHave('authors'); 
            }

            $existingBook = $query->first();
            if ($existingBook) {
                $this->logAction('Book', 'Attempted to add a book', 'Book with this title and author already exists: ' . $bookData['title'], $existingBook->id);
                return back()->with([
                    'error' => 'Book with this title and author already exists!',
                    'isbn' => 'N/A'
                ]);
            }
        }

        try {
            $publisher = Publisher::firstOrCreate(
                ['name' => $bookData['publisher']],
                ['logo' => $this->downloadImage('https://picsum.photos/seed/' . rand(0, 100000) . '/3840/2160', 'publisher')]
            );

            $coverPath = $this->downloadImage($bookData['cover'], 'book');

            $book = Book::create([
                'title' => $bookData['title'],
                'isbn' => $bookData['isbn'] ?? null,
                'bibliography' => $bookData['description'],
                'cover_image' => $coverPath,
                'price' => rand(10, 100),
                'publisher_id' => $publisher->id,
                'user_id' => Auth::id(),
                'status' => 'available',
            ]);

            foreach ($bookData['authors'] as $authorName) {
                $author = Author::firstOrCreate(
                    ['name' => $authorName],
                    ['photo' => $this->downloadImage('https://picsum.photos/seed/' . rand(0, 100000) . '/3840/2160', 'author')]
                );
                $book->authors()->attach($author->id);
            }

            // Logando a adição do livro
            $this->logAction('Book', 'Added new book', 'Book added: ' . $book->title, $book->id);

            return back()->with([
                'success' => 'Book successfully added!',
                'isbn' => $book->isbn ?? 'N/A'
            ]);
        } catch (\Exception $e) {
            // Logando falha ao adicionar o livro
            $this->logAction('Book', 'Failed to add book', 'Error: ' . $e->getMessage(), 0);
            return back()->with([
                'error' => 'Failed to add book: ' . $e->getMessage(),
                'isbn' => $bookData['isbn'] ?? 'N/A'
            ]);
        }
    }

    private function downloadImage($url, $type)
    {
        if (!$url) {
            return $this->fetchRandomImage($type);
        }

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get($url, ['timeout' => 5]);
            $contents = $response->getBody()->getContents();

            if (!$contents) {
                throw new \Exception('Empty image content.');
            }

            $fileName = "{$type}_" . uniqid() . ".jpg";
            $path = public_path('images/' . $fileName);
            file_put_contents($path, $contents);

            return $fileName;
        } catch (\Exception $e) {
            Log::error('Error downloading image: ' . $e->getMessage());
            return $this->fetchRandomImage($type);
        }
    }

    private function fetchRandomImage($type)
    {
        $seed = rand(0, 100000);
        return "https://picsum.photos/seed/{$seed}/3840/2160";
    }
}
