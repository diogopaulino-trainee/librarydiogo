<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BooksExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Book::with(['authors', 'publisher', 'user'])
            ->get()
            ->map(function ($book) {
                return [
                    'ISBN' => "'" . $book->isbn,
                    'Title' => $book->title,
                    'Authors' => $book->authors->pluck('name')->join(', ') ?: 'N/A',
                    'Publisher' => $book->publisher->name ?? 'N/A',
                    'Price (€)' => number_format($book->price, 2, ',', '.'),
                    'Cover Image' => asset('images/' . $book->cover_image),
                    'Added By' => $book->user->name ?? 'N/A',
                ];
            });
    }

    public function headings(): array
    {
        return ['ISBN', 'Title', 'Authors', 'Publisher', 'Price (€)', 'Cover Image', 'Added By'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
