<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::with('user')->get();
        return view('authors.index', compact('authors'));
    }

    public function show(Author $author)
    {
        return view('authors.show', compact('author'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:authors,name',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('images'), $photoName);
        } else {
            $photoName = 'noimage.png';
        }

        Author::create([
            'name' => $request->name,
            'photo' => $photoName,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('authors.index')->with('success', 'Author created successfully!');
    }

    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('images'), $photoName);
        } else {
            $photoName = $author->photo;
        }

        $author->update([
            'name' => $request->name,
            'photo' => $photoName,
        ]);

        return redirect()->route('authors.index')->with('success', 'Author updated successfully!');
    }

    public function delete(Author $author)
    {
        return view('authors.delete', compact('author'));
    }
    
    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('authors.index')->with('success', 'author deleted successfully!');
    }
}
