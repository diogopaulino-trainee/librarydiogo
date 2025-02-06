<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggleFavorite(Book $book)
    {
        $user = Auth::user();

        if ($user->favorites()->where('book_id', $book->id)->exists()) {
            $user->favorites()->detach($book->id);
            return response()->json(['favorited' => false]);
        } else {
            $user->favorites()->attach($book->id);
            return response()->json(['favorited' => true]);
        }
    }

    public function removeFavorite($bookId)
    {
        $user = Auth::user();

        if (!method_exists($user, 'favorites')) {
            throw new \Exception('Método favorites() não existe no modelo User');
        }

        if ($user->favorites()->where('book_id', $bookId)->exists()) {
            $user->favorites()->detach($bookId);
            return response()->json(['message' => 'Favorite removed successfully']);
        }

        return response()->json(['message' => 'Book not found in favorites'], 404);
    }
}
