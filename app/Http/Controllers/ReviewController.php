<?php

namespace App\Http\Controllers;

use App\Mail\ReviewNotificationMail;
use App\Mail\ReviewStatusNotification;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Traits\Loggable;

class ReviewController extends Controller
{
    use Loggable;

    public function index(HttpRequest $request)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $status = $request->input('status');
        $search = $request->input('search');

        // Logando a ação de acesso a revisão
        $logDescription = 'Admin accessed review history';

        if ($status) {
            $logDescription .= ' with status filter: ' . $status;
        }
        
        if ($search) {
            $logDescription .= ' and search query: ' . $search;
        }

        // Logando a ação
        $this->logAction('Review', 'Admin accessed review history', $logDescription);

        $historyQuery = Review::whereIn('status', ['approved', 'rejected'])->orderBy('updated_at', 'desc');

        if ($status) {
            $historyQuery->where('status', $status);
        }

        if ($search) {
            $historyQuery->where(function ($query) use ($search) {
                $query->whereHas('book', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhere('rating', 'like', "%{$search}%");
            });
        }

        $historyReviews = $historyQuery->paginate(10)->appends($request->query());

        $pendingReviews = Review::where('status', 'suspended')->paginate(10);
        
        $averageRating = Review::where('status', 'approved')->avg('rating');

        return view('admin.reviews.index', compact('pendingReviews', 'historyReviews', 'averageRating'));
    }

    public function store(HttpRequest $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $bookId = $request->book_id;

        // Verifica se o utilizador requisitou e devolveu o livro
        $hasReturnedRequest = Request::where('book_id', $bookId)
            ->where('user_id', auth()->id())
            ->whereNotNull('actual_return_date')
            ->exists();

        if (!$hasReturnedRequest) {
            return back()->with('error', 'You can only review books you have borrowed and returned.');
        }

        // Buscar a última review do utilizador para este livro
        $existingReview = Review::where('book_id', $bookId)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        // Permitir nova review apenas se a anterior foi rejeitada
        if ($existingReview && $existingReview->status !== 'rejected') {
            return back()->with('error', 'You have already submitted a review for this book.');
        }

        $review = Review::create([
            'book_id' => $bookId,
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'suspended',
        ]);

        // Logando após a criação da review
        $logDescription = "User {$user->name} submitted a review for book ID {$bookId}, rating: {$review->rating}. Comment: {$review->comment}";
        $this->logAction('Review', 'New review submitted', $logDescription, $bookId);

        // Enviar email para os admins informando da nova review
        $adminEmails = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin');
        })->pluck('email')->toArray();

        $reviewLink = route('reviews.show', ['review' => $review->id]);
        $manageReviewsLink = route('admin.reviews.index');
        
        Mail::to($adminEmails)->send(new ReviewNotificationMail($review, $reviewLink, $manageReviewsLink));

        return back()->with('success', 'Your review has been submitted and is awaiting approval.');
    }

    public function update(HttpRequest  $request, Review $review)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_justification' => 'nullable|string|max:1000',
        ]);

        // Guardar o status anterior da revisão
        $previousStatus = $review->status;

        $review->update([
            'status' => $request->status,
            'admin_justification' => $request->status === 'rejected' ? $request->admin_justification : null,
        ]);

        // Logando a alteração de status
        $logDescription = "Review status updated for book ID {$review->book_id} by Admin. Status changed from {$previousStatus} to {$review->status}.";
        $this->logAction('Review', 'Review status updated', $logDescription, $review->book_id);

        Mail::to($review->user->email)->send(new ReviewStatusNotification($review));

        return back()->with('success', 'Review status updated successfully.');
    }

    public function show(Review $review)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        // Logando a visualização da revisão
        $this->logAction('Review', 'Viewing review details', 'Admin viewed review details for book ID ' . $review->book_id, $review->book_id);
        
        return view('admin.reviews.show', compact('review'));
    }
}
