<?php

namespace App\Http\Controllers;

use App\Exports\BooksExport;
use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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

        if ($request->has('price_below_25')) {
            $query->where('price', '<', 25);
        }
        if ($request->has('price_above_25')) {
            $query->where('price', '>=', 25);
        }

        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
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
        // Apenas Admins podem ver a lista de cidadãos para solicitar livros
        $citizens = auth()->check() && auth()->user()->hasRole('Admin') 
            ? User::whereHas('roles', function($query) {
                $query->where('name', 'Citizen');
            })
            ->orderBy('name', 'asc')
            ->get()
            : collect();

        // Requisição pendente mais recente do livro
        $pendingRequest = $book->requests()
            ->where('status', 'pending')
            ->orderBy('expected_return_date', 'desc')
            ->first();

        foreach ($citizens as $citizen) {
            $pendingCount = $citizen->requests()->where('status', 'pending')->count();
            $citizen->requests_left = max(0, 3 - $pendingCount);
        }

        $requests = $book->requests()->orderBy('created_at', 'desc')->get();

        $previousBook = Book::where('id', '<', $book->id)->orderBy('id', 'desc')->first();
        $nextBook = Book::where('id', '>', $book->id)->orderBy('id', 'asc')->first();

        return view('books.show', compact('book', 'citizens', 'pendingRequest', 'requests', 'previousBook', 'nextBook'));
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
}
