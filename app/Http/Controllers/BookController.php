<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with(['author', 'publisher', 'user'])->get();
        return view('books.index', compact('books'));
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function create()
    {
        $authors = Author::all();
        $publishers = Publisher::all();
        return view('books.create', compact('authors', 'publishers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'isbn' => 'required|unique:books,isbn',
            'title' => 'required',
            'author_id' => 'required|exists:authors,id',
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
    
        $book = new Book([
            'isbn' => $request->isbn,
            'title' => $request->title,
            'bibliography' => $request->bibliography,
            'cover_image' => $imageName,
            'price' => $request->price,
            'author_id' => $request->author_id,
            'publisher_id' => $request->publisher_id,
            'user_id' => Auth::id(),
        ]);
        $book->save();
    
        return redirect()->route('books.index')->with('success', 'Book created successfully!');
    }

    public function edit(Book $book)
    {
        $authors = Author::all();
        $publishers = Publisher::all();
        return view('books.edit', compact('book', 'authors', 'publishers'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'isbn' => 'required|unique:books,isbn,' . $book->id,
            'title' => 'required',
            'author_id' => 'required|exists:authors,id',
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
            'author_id' => $request->author_id,
            'publisher_id' => $request->publisher_id,
        ]);

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    public function delete(Book $book)
    {
        return view('books.delete', compact('book'));
    }
    
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }
}
