<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\Loggable;

class FavoriteController extends Controller
{
    use Loggable;

    public function toggleFavorite(Book $book)
    {
        $user = Auth::user();

        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        if ($user->favorites()->where('book_id', $book->id)->exists()) {
            $user->favorites()->detach($book->id);

            // Logando a ação de remover o livro dos favoritos
            $this->logAction('Favorite', 'Removing book from favorites', 'User removed the book from their favorites.', $book->id);

            return response()->json(['favorited' => false]);
        } else {
            $user->favorites()->attach($book->id);

            // Logando a ação de adicionar o livro aos favoritos
            $this->logAction('Favorite', 'Adding book to favorites', 'User added the book to their favorites.', $book->id);

            return response()->json(['favorited' => true]);
        }
    }

    public function removeFavorite($bookId)
    {
        $user = Auth::user();

        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        if (!method_exists($user, 'favorites')) {
            throw new \Exception('Método favorites() não existe no modelo User');
        }

        if ($user->favorites()->where('book_id', $bookId)->exists()) {
            $user->favorites()->detach($bookId);

            // Logando a remoção do livro dos favoritos
            $this->logAction('Favorite', 'Removing book from favorites', 'User removed the book from their favorites.', $bookId);

            return response()->json(['message' => 'Favorite removed successfully']);
        }

        return response()->json(['message' => 'Book not found in favorites'], 404);
    }
}
