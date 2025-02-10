<?php

use App\Http\Controllers\AdminController;
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

// API para estatísticas (acessível publicamente)
Route::prefix('api')->group(function () {
    Route::get('/books/count', [StatsController::class, 'countBooks'])->name('api.books.count');
    Route::get('/authors/count', [StatsController::class, 'countAuthors'])->name('api.authors.count');
    Route::get('/publishers/count', [StatsController::class, 'countPublishers'])->name('api.publishers.count');
    Route::get('/users/count', [StatsController::class, 'countUsers'])->name('api.users.count');
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

        // Rotas para administração de utilizadores (Admin apenas)
        Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users/{user}/change-role', [AdminController::class, 'changeRole'])->name('admin.users.change-role');
        Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users/store', [AdminController::class, 'store'])->name('admin.users.store');
    });

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
