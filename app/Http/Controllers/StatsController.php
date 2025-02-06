<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function countBooks(): JsonResponse
    {
        return response()->json(['count' => Book::count()]);
    }

    public function countAuthors(): JsonResponse
    {
        return response()->json(['count' => Author::count()]);
    }

    public function countPublishers(): JsonResponse
    {
        return response()->json(['count' => Publisher::count()]);
    }

    public function countUsers(): JsonResponse
    {
        return response()->json(['count' => User::count()]);
    }
}
