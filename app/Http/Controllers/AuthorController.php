<?php

namespace App\Http\Controllers;

use App\Exports\AuthorsExport;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\Loggable;

class AuthorController extends Controller
{
    use Loggable;

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

        // Verificando se o usuário está autenticado antes de registrar o log
        if (auth()->check()) {
        // Logando a visualização da lista de autores
        $this->logAction('Author', 'Viewing authors list', 'Accessing authors list.');
        }

        return view('authors.index', compact('authors'));
    }

    public function show(Author $author)
    {
        $previousAuthor = Author::where('id', '<', $author->id)->orderBy('id', 'desc')->first();
        $nextAuthor = Author::where('id', '>', $author->id)->orderBy('id', 'asc')->first();

        // Verificando se o usuário está autenticado antes de registrar o log
        if (auth()->check()) {
        // Logando a visualização de um autor
        $this->logAction('Author', 'Viewing author details', 'Accessing author details.', $author->id);
        }

        return view('authors.show', compact('author', 'previousAuthor', 'nextAuthor'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        // Logando o acesso ao formulário de criação de autor
        $this->logAction('Author', 'Accessing create author form', 'Accessing the form to create a new author.');

        return view('authors.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

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

        $author = Author::create([
            'name' => $request->name,
            'photo' => $photoName,
            'user_id' => Auth::id(),
        ]);

        // Logando a criação do autor
        $this->logAction('Author', 'Creating new author', "Created a new author: {$author->name}", $author->id);

        return redirect()->route('authors.index')->with('success', 'Author created successfully!');
    }

    public function edit(Author $author)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        // Logando o acesso à página de edição do autor
        $this->logAction('Author', 'Accessing edit author form', 'User accessed the edit form for author: ' . $author->name, $author->id);

        return view('authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $oldPhoto = $author->photo;

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

        // Descrição detalhada para o log
        $logDescription = "Updated author details: Name changed from {$author->getOriginal('name')} to {$request->name}.";
        if ($oldPhoto !== $photoName) {
            $logDescription .= " Photo changed.";
        }

        // Logando a atualização do autor
        $this->logAction('Admin', 'Updating author details', $logDescription, $author->id);

        return redirect()->route('authors.index')->with('success', 'Author updated successfully!');
    }

    public function delete(Author $author)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        // Logando o acesso à página de exclusão do autor
        $this->logAction('Author', 'Accessing delete author form', 'User accessed the delete form for author: ' . $author->name, $author->id);

        return view('authors.delete', compact('author'));
    }
    
    public function destroy(Author $author)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        // Verificar se o autor tem livros associados
        if ($author->books()->exists()) {
            // Logando a tentativa de exclusão impedida
            $this->logAction('Author', 'Attempted to delete author with books', 'User attempted to delete author with books associated: ' . $author->name, $author->id);
            
            return back()->with('error', 'Cannot delete author with associated books! Remove the books first.');
        }

        // Logando a exclusão do autor
        $this->logAction('Author', 'Deleting author', 'User deleted the author: ' . $author->name, $author->id);
        
        $author->delete();
        return redirect()->route('authors.index')->with('success', 'Author deleted successfully!');
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'excel');

        // Logando o tipo de exportação escolhido
        $this->logAction('Author', 'Exporting authors', 'Exporting authors data in ' . $format . ' format.', 0);

        if ($format === 'pdf') {
            $authors = Author::all();
            $pdf = Pdf::loadView('exports.authors_pdf', compact('authors'));
            return $pdf->download('authors.pdf');
        }

        return Excel::download(new AuthorsExport, 'authors.xlsx');
    }
}
