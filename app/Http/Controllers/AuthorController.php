<?php

namespace App\Http\Controllers;

use App\Exports\AuthorsExport;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort_by') && $request->has('order')) {
            $query->orderBy($request->sort_by, $request->order);
        } else {
            $query->orderBy('name', 'asc');
        }

        $authors = $query->paginate(10);
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

    public function export(Request $request)
    {
        $format = $request->query('format', 'excel');

        if ($format === 'pdf') {
            $authors = Author::all();
            $pdf = Pdf::loadView('exports.authors_pdf', compact('authors'));
            return $pdf->download('authors.pdf');
        }

        return Excel::download(new AuthorsExport, 'authors.xlsx');
    }
}
