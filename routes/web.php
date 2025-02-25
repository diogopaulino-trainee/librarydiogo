<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GoogleBooksController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;








// Página inicial acessível a todos
Route::get('/', function () {
    return view('homepage');
})->name('homepage');

// Página "Sobre Mim"
Route::get('/aboutme', function () {
    return view('aboutme');
})->name('about.me');

// API para estatísticas (acessível publicamente)
Route::prefix('api')->group(function () {
    Route::get('/books/count', [StatsController::class, 'countBooks'])->name('api.books.count');
    Route::get('/authors/count', [StatsController::class, 'countAuthors'])->name('api.authors.count');
    Route::get('/publishers/count', [StatsController::class, 'countPublishers'])->name('api.publishers.count');
    Route::get('/users/count', [StatsController::class, 'countUsers'])->name('api.users.count');
    Route::get('/books/covers', [BookController::class, 'getBookCovers'])->name('api.books.covers');
});

// Grupo de rotas protegidas por autenticação
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Apenas Admins podem gerir livros, autores e editoras
    Route::middleware(['auth'])->group(function () {
        Route::get('books/{book}/delete', [BookController::class, 'delete'])->name('books.delete');
        Route::resource('books', BookController::class)->except(['index', 'show']);
        
        Route::get('authors/{author}/delete', [AuthorController::class, 'delete'])->name('authors.delete');
        Route::resource('authors', AuthorController::class)->except(['index', 'show']);
        
        Route::get('publishers/{publisher}/delete', [PublisherController::class, 'delete'])->name('publishers.delete');
        Route::resource('publishers', PublisherController::class)->except(['index', 'show']);

        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Rotas para administração de utilizadores (Admin apenas)
        Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
        Route::get('admin/users/{user}/show', [AdminController::class, 'show'])->name('admin.users.show');
        Route::post('/admin/users/{user}/change-role', [AdminController::class, 'changeRole'])->name('admin.users.change-role');
        Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users/store', [AdminController::class, 'store'])->name('admin.users.store');

        Route::get('/admin/books/search', [GoogleBooksController::class, 'searchPage'])->name('admin.books.search');
        Route::get('/admin/books/search/results', [GoogleBooksController::class, 'search'])->name('admin.books.search.results');
        Route::post('/admin/books/store', [GoogleBooksController::class, 'store'])->name('admin.books.store');
    });

    // Gestão do Carrinho de Compras
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add/{book}', [CartController::class, 'addToCart'])->name('cart.add');
        Route::delete('/remove/{cartItem}', [CartController::class, 'removeFromCart'])->name('cart.remove');
        Route::post('/update/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update');

        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

        Route::get('/items', [CartController::class, 'getCartItems'])->name('cart.items');
        Route::get('/count', [CartController::class, 'count'])->name('cart.count');
    });

    // Gestão das Encomendas (Citizens)
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/store', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/{order}/payment', [OrderController::class, 'payment'])->name('orders.payment');
        Route::get('/{order}/success', [OrderController::class, 'success'])->name('orders.success');
    });

    // Gestão de Encomendas (Admins)
    Route::prefix('admin/orders')->middleware('auth')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('admin.orders.index');  
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');  
        Route::put('/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update-status');  
    });

    // Rotas de Requisições
    Route::prefix('requests')->group(function () {
        Route::get('/', [RequestController::class, 'index'])->name('requests.index');
        Route::post('/{book}', [RequestController::class, 'store'])->name('requests.store');
        Route::post('/{book}/admin', [RequestController::class, 'storeByAdmin'])->name('requests.store.admin');
        Route::get('/{request}', [RequestController::class, 'show'])->name('requests.show');
        Route::post('/{request}/confirm-return', [RequestController::class, 'confirmReturn'])->name('requests.confirm_return');
        Route::get('/citizens/search', [RequestController::class, 'searchCitizens'])->name('citizens.search');
        Route::post('/books/{book}/notify', [BookController::class, 'notifyMe'])->name('books.notify');
        Route::delete('/books/{book}/cancel-notify', [BookController::class, 'cancelNotification'])->name('books.cancel_notify');
    });

    Route::prefix('admin/reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('admin.reviews.index');
        Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
        Route::put('/admin/reviews/{review}/update-status', [ReviewController::class, 'update'])->name('admin.reviews.update-status');
    });

    Route::post('/reviews/store', [ReviewController::class, 'store'])->name('reviews.store');

    // Exportação de dados - Disponível para todos os utilizadores autenticados
    Route::get('/books/export', [BookController::class, 'export'])->name('books.export');
    Route::get('/authors/export', [AuthorController::class, 'export'])->name('authors.export');
    Route::get('/publishers/export', [PublisherController::class, 'export'])->name('publishers.export');

    // Apenas Citizens podem adicionar/remover favoritos
    Route::middleware(['auth'])->group(function () {
        Route::post('/favorites/toggle/{book}', [FavoriteController::class, 'toggleFavorite'])->name('favorites.toggle');
        Route::delete('/favorites/remove/{book}', [FavoriteController::class, 'removeFavorite'])->name('favorites.remove');
    });
});

// Rotas públicas (acessíveis a todos)
Route::resource('books', BookController::class)->only(['index', 'show']);
Route::resource('authors', AuthorController::class)->only(['index', 'show']);
Route::resource('publishers', PublisherController::class)->only(['index', 'show']);
