<?php

namespace App\Http\Controllers;

use App\Exports\PublishersExport;
use App\Models\Publisher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\Loggable;

class PublisherController extends Controller
{
    use Loggable;

    public function index(Request $request)
    {
        if (auth()->check()) {
        // Logando o acesso ao módulo Publisher
        $this->logAction('Publisher', 'Accessing publisher list', 'User accessed the publisher list.', Auth::id());
        }

        $query = Publisher::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort_by') && $request->has('order')) {
            $query->orderBy($request->sort_by, $request->order);
        } else {
            $query->orderBy('name', 'asc');
        }

        $publishers = $query->paginate(10);
        return view('publishers.index', compact('publishers'));
    }

    public function show(Publisher $publisher)
    {
        if (auth()->check()) {
        // Logando o acesso à página de detalhes da editora
        $this->logAction('Publisher', 'Accessing publisher details', 'User accessed the details of publisher: ' . $publisher->name, Auth::id());
        }

        $previousPublisher = Publisher::where('id', '<', $publisher->id)->orderBy('id', 'desc')->first();
        $nextPublisher = Publisher::where('id', '>', $publisher->id)->orderBy('id', 'asc')->first();

        return view('publishers.show', compact('publisher', 'previousPublisher', 'nextPublisher'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        // Logando o acesso à página de criação de editoras
        $this->logAction('Publisher', 'Accessing create publisher form', 'User accessed the create publisher form.', Auth::id());

        return view('publishers.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');
        
        $request->validate([
            'name' => 'required|unique:publishers,name',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('images'), $logoName);
        } else {
            $logoName = 'noimage.png';
        }

        $publisher = Publisher::create([
            'name' => $request->name,
            'logo' => $logoName,
            'user_id' => Auth::id(),
        ]);

        // Logando a criação da editora
        $this->logAction('Publisher', 'Creating a new publisher', 'Admin created a new publisher: ' . $publisher->name, Auth::id());

        return redirect()->route('publishers.index')->with('success', 'Publisher created successfully!');
    }

    public function edit(Publisher $publisher)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        // Logando o acesso à página de edição da editora
        $this->logAction('Publisher', 'Accessing publisher edit page', 
        'Admin accessed the publisher edit page. Publisher name: ' . $publisher->name, Auth::id());

        return view('publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('images'), $logoName);
        } else {
            $logoName = $publisher->logo;
        }

        // Registrar os dados anteriores antes da atualização
        $previousName = $publisher->name;

        $publisher->update([
            'name' => $request->name,
            'logo' => $logoName,
        ]);

        // Logando a atualização
        $this->logAction('Publisher', 'Updating publisher details', 
        'Admin updated publisher details. Name changed from ' . $previousName . ' to ' . $request->name, Auth::id());

        return redirect()->route('publishers.index')->with('success', 'Publisher updated successfully!');
    }

    public function delete(Publisher $publisher)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        // Logando o acesso à página de exclusão da editora
        $this->logAction('Publisher', 'Accessing publisher delete page', 
        'Admin accessed the publisher delete page. Publisher name: ' . $publisher->name, Auth::id());

        return view('publishers.delete', compact('publisher'));
    }
    
    public function destroy(Publisher $publisher)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        if ($publisher->books()->exists()) {
            return back()->with('error', 'Cannot delete publisher with associated books! Remove the books first.');
        }

        // Logando a exclusão da editora
        $this->logAction('Publisher', 'Deleting publisher', 
        'Admin deleted the publisher. Publisher name: ' . $publisher->name, Auth::id());

        $publisher->delete();
        return redirect()->route('publishers.index')->with('success', 'Publisher deleted successfully!');
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'excel');

        // Logando a ação de exportação
        $this->logAction('Publisher', 'Exporting publisher data', 
        'Admin exported publisher data in ' . strtoupper($format) . ' format.', Auth::id());

        if ($format === 'pdf') {
            $publishers = Publisher::all();
            $pdf = Pdf::loadView('exports.publishers_pdf', compact('publishers'));
            return $pdf->download('publishers.pdf');
        }

        return Excel::download(new PublishersExport, 'publishers.xlsx');
    }
}
