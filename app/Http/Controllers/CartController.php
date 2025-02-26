<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\CartItem;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        $cartItems = CartItem::where('user_id', Auth::id())->with('book')->get();
        return view('cart.index', compact('cartItems'));
    }

    public function addToCart($bookId)
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        $book = Book::findOrFail($bookId);

        $isBorrowed = Request::where('book_id', $bookId)
            ->whereNull('actual_return_date')
            ->exists();

        if ($isBorrowed) {
            return response()->json([
                'success' => false,
                'message' => 'This book is currently borrowed and cannot be purchased.'
            ], 400);
        }

        $existingCartItem = CartItem::where('user_id', Auth::id())
            ->where('book_id', $bookId)
            ->first();

        if ($existingCartItem) {
            return response()->json([
                'success' => false,
                'message' => 'This book is already in your cart.'
            ], 400);
        }

        CartItem::create([
            'user_id' => Auth::id(),
            'book_id' => $bookId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Book added to cart successfully.'
        ]);
    }

    public function removeFromCart($cartItemId)
    {
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        $cartItem = CartItem::where('id', $cartItemId)->where('user_id', Auth::id())->firstOrFail();
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Book removed from cart successfully.');
    }

    public function getCartItems()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        abort_if(!auth()->user()->hasRole('Citizen'), 403, 'Access denied.');

        $cartItems = CartItem::where('user_id', Auth::id())->with('book')->get();

        return response()->json([
            'cartItems' => $cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->book->title,
                    'price' => number_format($item->book->price, 2, ',', '.') . ' €',
                ];
            })
        ]);
    }

    public function clearCart()
    {
        if (!auth()->check() || !auth()->user()->hasRole('Citizen')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        CartItem::where('user_id', auth()->id())->delete();

        return response()->json(['success' => true, 'message' => 'Cart cleared successfully']);
    }
}
