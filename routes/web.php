<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
})->name('homepage');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ])->group(function () {
        Route::get('/', function () {
            return view('homepage');
        })->name('homepage');
    });

Route::get('/books', function () {
    return view('books.index');
})->name('books.index');

Route::get('/authors', function () {
    return view('authors.index');
})->name('authors.index');

Route::get('/publishers', function () {
    return view('publishers.index');
})->name('publishers.index');
