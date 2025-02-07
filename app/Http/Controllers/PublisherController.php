<?php

namespace App\Http\Controllers;

use App\Exports\PublishersExport;
use App\Models\Publisher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
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
        return view('publishers.show', compact('publisher'));
    }

    public function create()
    {
        return view('publishers.create');
    }

    public function store(Request $request)
    {
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

        Publisher::create([
            'name' => $request->name,
            'logo' => $logoName,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('publishers.index')->with('success', 'Publisher created successfully!');
    }

    public function edit(Publisher $publisher)
    {
        return view('publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
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

        $publisher->update([
            'name' => $request->name,
            'logo' => $logoName,
        ]);

        return redirect()->route('publishers.index')->with('success', 'Publisher updated successfully!');
    }

    public function delete(Publisher $publisher)
    {
        return view('publishers.delete', compact('publisher'));
    }
    
    public function destroy(Publisher $publisher)
    {
        $publisher->delete();
        return redirect()->route('publishers.index')->with('success', 'Publisher deleted successfully!');
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'excel');

        if ($format === 'pdf') {
            $publishers = Publisher::all();
            $pdf = Pdf::loadView('exports.publishers_pdf', compact('publishers'));
            return $pdf->download('publishers.pdf');
        }

        return Excel::download(new PublishersExport, 'publishers.xlsx');
    }
}
