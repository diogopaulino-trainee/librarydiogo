<?php

namespace App\Http\Controllers;

use App\Exports\BooksExport;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookNotification;
use App\Models\Publisher;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Text_LanguageDetect;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['authors', 'publisher']);

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('isbn', 'like', '%' . $request->search . '%')
                ->orWhereHas('authors', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('publisher', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
        }

        if ($request->has('availability')) {
            if ($request->availability == 'available') {
                $query->where('status', 'available');
            } elseif ($request->availability == 'unavailable') {
                $query->where('status', 'unavailable');
            }
        }

        if ($request->has('date_sort')) {
            if ($request->date_sort == 'oldest') {
                $query->orderBy('created_at', 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        }

        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'az':
                    $query->orderBy('title', 'asc');
                    break;
                case 'za':
                    $query->orderBy('title', 'desc');
                    break;
                default:
                    $query->orderBy('title', 'asc');
            }
        } else {
            $query->orderBy('title', 'asc');
        }        

        $books = $query->paginate(10);
        return view('books.index', compact('books'));
    }

    public function show(Book $book)
    {
        $relatedBooks = $this->getRelatedBooks($book);

        // Apenas Admins podem ver a lista de cidadãos para solicitar livros
        $citizens = auth()->check() && auth()->user()->hasRole('Admin') 
            ? User::whereHas('roles', function($query) {
                $query->where('name', 'Citizen');
            })
            ->orderBy('name', 'asc')
            ->get()
            : collect();

        // Requisição pendente mais recente do livro
        $borrowedRequest = $book->requests()
            ->where('status', 'borrowed')
            ->orderBy('expected_return_date', 'desc')
            ->first();

        foreach ($citizens as $citizen) {
            $borrowedCount = $citizen->requests()->where('status', 'borrowed')->count();
            $citizen->requests_left = max(0, 3 - $borrowedCount);
        }

        $requests = $book->requests()->orderBy('created_at', 'desc')->get();

        $previousBook = Book::where('id', '<', $book->id)->orderBy('id', 'desc')->first();
        $nextBook = Book::where('id', '>', $book->id)->orderBy('id', 'asc')->first();

        // Carregar apenas as reviews aprovadas
        $book->load(['reviews' => function ($query) {
            $query->where('status', 'approved')->orderBy('created_at', 'desc');
        }]);

        // Verificar se o Citizen autenticado pode adicionar uma review
        $returnedRequest = null;
        $canReview = false;
        if (auth()->check() && auth()->user()->hasRole('Citizen')) {
            $returnedRequest = auth()->user()
                ->requests()
                ->where('book_id', $book->id)
                ->where('status', 'returned')
                ->latest()
                ->first();

            // Se existe uma requisição devolvida, o cidadão pode avaliar o livro
            $canReview = !is_null($returnedRequest);
        }

        $userReview = auth()->check() 
            ? $book->reviews()
                ->where('user_id', auth()->id())
                ->where('status', '!=', 'rejected')
                ->first()
            : null;

        return view('books.show', compact('book', 'citizens', 'borrowedRequest', 'requests', 'previousBook', 'nextBook', 'returnedRequest', 'canReview', 'userReview', 'relatedBooks'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $authors = Author::all();
        $publishers = Publisher::all();
        return view('books.create', compact('authors', 'publishers'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $request->validate([
            'isbn' => 'required|unique:books,isbn',
            'title' => 'required',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'price' => 'required|numeric|min:0',
            'cover_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
        } else {
            $imageName = 'noimage.png';
        }

        $book = Book::create([
            'isbn' => $request->isbn,
            'title' => $request->title,
            'bibliography' => $request->bibliography,
            'cover_image' => $imageName,
            'price' => $request->price,
            'publisher_id' => $request->publisher_id,
            'user_id' => Auth::id(),
        ]);

        $book->authors()->attach($request->authors);

        return redirect()->route('books.index')->with('success', 'Book created successfully!');
    }

    public function edit(Book $book)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $authors = Author::all();
        $publishers = Publisher::all();
        return view('books.edit', compact('book', 'authors', 'publishers'));
    }

    public function update(Request $request, Book $book)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $request->validate([
            'isbn' => 'required|unique:books,isbn,' . $book->id,
            'title' => 'required',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'price' => 'required|numeric|min:0',
            'cover_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image !== 'noimage.png' && file_exists(public_path('images/' . $book->cover_image))) {
                unlink(public_path('images/' . $book->cover_image));
            }

            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
        } else {
            $imageName = $book->cover_image;
        }

        $book->update([
            'isbn' => $request->isbn,
            'title' => $request->title,
            'bibliography' => $request->bibliography,
            'cover_image' => $imageName,
            'price' => $request->price,
            'publisher_id' => $request->publisher_id,
        ]);

        $book->authors()->sync($request->authors);

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    public function delete(Book $book)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        return view('books.delete', compact('book'));
    }
    
    public function destroy(Book $book)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        if ($book->requests()->exists()) {
            return back()->with('error', 'Cannot delete book with existing requests!');
        }

        $book->authors()->detach();
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'excel');

        if ($format === 'pdf') {
            $books = Book::with(['authors', 'publisher'])->get();
            $pdf = Pdf::loadView('exports.books_pdf', compact('books'));
            return $pdf->download('books.pdf');
        }

        return Excel::download(new BooksExport, 'books.xlsx');
    }

    public function getBookCovers(): JsonResponse
    {
        return response()->json(Book::with('authors', 'publisher')
            ->whereNotNull('cover_image')
            ->select('id', 'cover_image', 'title', 'isbn', 'status', 'publisher_id')
            ->get());
    }

    private function extractKeywords($text)
    {
        // Normalizar texto (remover pontuação e converter para minúsculas)
        $text = mb_strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', '', $text));

        // Tokenizar em palavras
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        $ld = new Text_LanguageDetect();
        $lang = $ld->detectSimple($text);

        $stopwords = [
            'en' => ['the', 'of', 'and', 'to', 'in', 'is', 'it', 'for', 'with', 'on', 'this', 'that'],
            'la' => ['et', 'in', 'ad', 'cum', 'non', 'per', 'ex', 'est', 'quod', 'sed', 'neque', 'autem'],
            'pt' => ['o', 'a', 'de', 'do', 'da', 'um', 'para', 'com', 'por', 'que', 'na', 'no']
        ];

        $keywords = collect($words)->filter(function ($word) use ($stopwords, $lang) {
            return strlen($word) > 3 && !is_numeric($word) &&
                (!isset($stopwords[$lang]) || !in_array($word, $stopwords[$lang] ?? []));
        })->countBy()->sortDesc()->keys()->take(5);

        return $keywords;
    }

    private function getRelatedBooks(Book $book)
    {
        $keywords = $this->extractKeywords($book->bibliography);

        // Criar estrutura para armazenar os pesos dos livros
        $bookScores = [];

        // Priorizar Bibliografia Semelhante (Peso: 5)
        if (!$keywords->isEmpty()) {
            $query = Book::where('id', '!=', $book->id);

            $hasFullText = collect(DB::select("SHOW INDEX FROM books WHERE Column_name = 'bibliography'"))
                ->contains(fn ($index) => $index->Index_type === 'FULLTEXT');

            if ($hasFullText) {
                $query->whereRaw("MATCH(bibliography) AGAINST(? IN BOOLEAN MODE)", [$book->bibliography]);
            } else {
                foreach ($keywords as $word) {
                    $query->orWhere('bibliography', 'LIKE', "%{$word}%");
                }
            }

            $similarBooks = $query->orderByRaw("LENGTH(bibliography) DESC")->limit(8)->get();
            
            foreach ($similarBooks as $related) {
                $bookScores[$related->id] = ($bookScores[$related->id] ?? 0) + 5;
            }
        }

        // Depois, buscar livros do mesmo autor (Peso: 3)
        $authorBooks = Book::where('id', '!=', $book->id)
            ->whereHas('authors', fn ($query) => $query->whereIn('authors.id', $book->authors->pluck('id')))
            ->limit(8)
            ->get();
        foreach ($authorBooks as $related) {
            $bookScores[$related->id] = ($bookScores[$related->id] ?? 0) + 3;
        }

        // Finalmente, livros da mesma editora (Peso: 2)
        $publisherBooks = Book::where('id', '!=', $book->id)
            ->where('publisher_id', $book->publisher_id)
            ->limit(8)
            ->get();
        foreach ($publisherBooks as $related) {
            $bookScores[$related->id] = ($bookScores[$related->id] ?? 0) + 2;
        }

        arsort($bookScores);

        $relatedBooks = collect(Book::whereIn('id', array_keys($bookScores))->get())
            ->sortByDesc(fn ($book) => $bookScores[$book->id])
            ->take(9)
            ->shuffle();

        return [
            'similar' => $relatedBooks->where(fn ($book) => isset($bookScores[$book->id]) && $bookScores[$book->id] >= 5)->take(3),
            'authors' => $relatedBooks->where(fn ($book) => isset($bookScores[$book->id]) && $bookScores[$book->id] == 3)->take(3),
            'publishers' => $relatedBooks->where(fn ($book) => isset($bookScores[$book->id]) && $bookScores[$book->id] == 2)->take(3),
        ];
    }

    public function notifyMe(Request $request, $bookId)
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Only Citizens can request notifications.');

        $exists = BookNotification::where('book_id', $bookId)
            ->where('user_id', auth()->id())
            ->exists();

        if (!$exists) {
            BookNotification::create([
                'book_id' => $bookId,
                'user_id' => auth()->id(),
            ]);
        }

        return back()->with('success', 'You will be notified when this book is available.');
    }

    public function cancelNotification(Book $book)
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Only Citizens can cancel notifications.');

        BookNotification::where('book_id', $book->id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Notification subscription cancelled.');
    }
}
