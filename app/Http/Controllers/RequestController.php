<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Request;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestConfirmationMail;

class RequestController extends Controller
{
    public function index(HttpRequest $request)
    {
        $user = Auth::user();
        $query = Request::query();

        if (!$user->hasRole('Admin')) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search, $user) {
                $q->whereHas('book', function ($query) use ($search) {
                    $query->where('title', 'LIKE', "%$search%");
                });

                if ($user->hasRole('Admin')) {
                    $q->orWhere('user_name_at_request', 'LIKE', "%$search%")
                    ->orWhere('user_email_at_request', 'LIKE', "%$search%");
                }
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $order = $request->input('order', 'desc');

        $allowedSorts = ['request_number', 'expected_return_date', 'created_at', 'status'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        $requests = $query->orderBy($sortBy, $order)->paginate(10);

        // Contagem de requisições com base no role
        if ($user->hasRole('Admin')) {
            $activeRequests = Request::where('status', 'borrowed')->count();
            $last30DaysRequests = Request::where('request_date', '>=', now()->subDays(30))->count();
            $returnedToday = Request::whereNotNull('actual_return_date')
                                    ->whereDate('actual_return_date', now())
                                    ->count();
        } else {
            // Se for Citizen, contar apenas as requisições deste user
            $activeRequests = Request::where('status', 'borrowed')->where('user_id', $user->id)->count();
            $last30DaysRequests = Request::where('request_date', '>=', now()->subDays(30))
                                         ->where('user_id', $user->id)
                                         ->count();
            $returnedToday = Request::whereNotNull('actual_return_date')
                                    ->whereDate('actual_return_date', now())
                                    ->where('user_id', $user->id)
                                    ->count();
        }

        return view('requests.index', compact('requests', 'activeRequests', 'last30DaysRequests', 'returnedToday'));
    }

    public function store(Book $book)
    {
        $user = Auth::user();

        // Verifica se o livro já está emprestado
        if (Request::where('book_id', $book->id)->where('status', 'borrowed')->exists()) {
            return back()->with('error', 'This book is currently unavailable.');
        }

        // Verifica se o cidadão já tem 3 livros requisitados
        if ($user->requests()->where('status', 'borrowed')->count() >= 3) {
            return back()->with('error', 'You can only have up to 3 active book requests.');
        }

        // Copia a foto do usuário para o diretório de fotos das requisições
        $photoPath = $this->copyUserPhoto($user->profile_photo_path);

        $newRequest = Request::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'request_date' => now(),
            'expected_return_date' => now()->addDays(5),
            'status' => 'borrowed',
            'request_number' => (Request::max('request_number') ?? 0) + 1,
            'user_name_at_request' => $user->name,
            'user_email_at_request' => $user->email,
            'user_photo_at_request' => $photoPath,
        ]);

        $book->update(['status' => 'unavailable']);

        $adminEmails = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin');
        })->pluck('email')->toArray();

        // Enviar email para o cidadão
        Mail::to($user->email)->send(new RequestConfirmationMail($newRequest));

        // Enviar email para todos os admins
        if (!empty($adminEmails)) {
            Mail::to($adminEmails)->send(new RequestConfirmationMail($newRequest));
        }

        return back()->with('success', 'Your book request was successful.');
    }

    public function storeByAdmin(HttpRequest $requestData, Book $book)
    {
        $requestData->validate([
            'citizen_id' => 'required|exists:users,id'
        ], [
            'citizen_id.required' => 'Please select a citizen to proceed with the book request.',
            'citizen_id.exists' => 'The selected citizen does not exist in the database.'
        ]);

        $citizen = User::find($requestData->citizen_id);

        if ($citizen->requests()->where('status', 'borrowed')->count() >= 3) {
            return back()->with('error', "{$citizen->name} already has 3 active book requests.");
        }

        // Copia a foto do cidadão para o diretório de fotos das requisições
        $photoPath = $this->copyUserPhoto($citizen->profile_photo_path);

        // Criar a requisição em nome do cidadão
        $newRequest = Request::create([
            'book_id' => $book->id,
            'user_id' => $citizen->id,
            'request_date' => now(),
            'expected_return_date' => now()->addDays(5),
            'status' => 'borrowed',
            'request_number' => (Request::max('request_number') ?? 0) + 1,
            'user_name_at_request' => $citizen->name,
            'user_email_at_request' => $citizen->email,
            'user_photo_at_request' => $photoPath,
        ]);

        $book->update(['status' => 'unavailable']);

        $adminEmails = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin');
        })->pluck('email')->toArray();

        Mail::to($citizen->email)->send(new RequestConfirmationMail($newRequest));

        if (!empty($adminEmails)) {
            Mail::to($adminEmails)->send(new RequestConfirmationMail($newRequest));
        }

        return back()->with('success', 'Book requested on behalf of the citizen.');
    }

    private function copyUserPhoto($profilePhotoPath)
    {
        if (!$profilePhotoPath) {
            return null;
        }

        $sourcePath = storage_path('app/public/' . $profilePhotoPath);
        $destinationDirectory = storage_path('app/public/request_photos');
        $fileName = uniqid('request_photo_') . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);

        if (!file_exists($destinationDirectory)) {
            mkdir($destinationDirectory, 0755, true);
        }

        copy($sourcePath, $destinationDirectory . '/' . $fileName);

        return 'request_photos/' . $fileName;
    }

    public function confirmReturn($id)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Only Admins can confirm book returns.');

        $request = Request::findOrFail($id);

        $request->update([
            'actual_return_date' => now(),
            'status' => 'returned',
        ]);

        $book = $request->book;
        $book->update(['status' => 'available']);

        return back()->with('success', 'Book return confirmed.');
    }

    public function show(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Admin') && auth()->user()->id != $request->user_id, 403, 'Unauthorized access.');

        $user = Auth::user();

        // Se for Admin, pode navegar entre todas as requisições
        if ($user->hasRole('Admin')) {
            $previousRequest = Request::where('id', '<', $request->id)
                                      ->orderBy('id', 'desc')
                                      ->first();
    
            $nextRequest = Request::where('id', '>', $request->id)
                                  ->orderBy('id', 'asc')
                                  ->first();
        } else {
            // Se for Citizen, só vê as suas próprias requisições
            $previousRequest = Request::where('id', '<', $request->id)
                                      ->where('user_id', $user->id)
                                      ->orderBy('id', 'desc')
                                      ->first();
    
            $nextRequest = Request::where('id', '>', $request->id)
                                  ->where('user_id', $user->id)
                                  ->orderBy('id', 'asc')
                                  ->first();
        }

        return view('requests.show', compact('request', 'previousRequest', 'nextRequest'));
    }
}
