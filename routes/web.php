<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PublisherController;
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

Route::prefix('api')->group(function () {
    Route::get('/books/count', [StatsController::class, 'countBooks'])->name('api.books.count');
    Route::get('/authors/count', [StatsController::class, 'countAuthors'])->name('api.authors.count');
    Route::get('/publishers/count', [StatsController::class, 'countPublishers'])->name('api.publishers.count');
    Route::get('/users/count', [StatsController::class, 'countUsers'])->name('api.users.count');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('books/{book}/delete', [BookController::class, 'delete'])->name('books.delete');
    Route::resource('books', BookController::class)->except(['index', 'show']);
    Route::get('authors/{author}/delete', [AuthorController::class, 'delete'])->name('authors.delete');
    Route::resource('authors', AuthorController::class)->except(['index', 'show']);
    Route::get('publishers/{publisher}/delete', [PublisherController::class, 'delete'])->name('publishers.delete');
    Route::resource('publishers', PublisherController::class)->except(['index', 'show']);

    Route::post('/favorites/toggle/{book}', [FavoriteController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::delete('/favorites/remove/{book}', [FavoriteController::class, 'removeFavorite'])->name('favorites.remove');

    Route::get('/books/export', [BookController::class, 'export'])->name('books.export');
    Route::get('/authors/export', [AuthorController::class, 'export'])->name('authors.export');
    Route::get('/publishers/export', [PublisherController::class, 'export'])->name('publishers.export');
});

// Rotas públicas (index de livros, autores e publishers)
Route::resource('books', BookController::class)->only(['index', 'show']);
Route::resource('authors', AuthorController::class)->only(['index', 'show']);
Route::resource('publishers', PublisherController::class)->only(['index', 'show']);
