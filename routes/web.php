<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PublisherController;
use Illuminate\Support\Facades\Route;

// Página inicial acessível a todos
Route::get('/', function () {
    return view('homepage');
})->name('homepage');

// Página "Sobre Mim"
Route::get('/aboutme', function () {
    return view('aboutme');
})->name('about.me');

// Rotas protegidas (criação, edição e eliminação)
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
});

// Rotas públicas (index de livros, autores e publishers)
Route::resource('books', BookController::class)->only(['index', 'show']);
Route::resource('authors', AuthorController::class)->only(['index', 'show']);
Route::resource('publishers', PublisherController::class)->only(['index', 'show']);
