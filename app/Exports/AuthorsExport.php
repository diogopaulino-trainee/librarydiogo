<?php

namespace App\Exports;

use App\Models\Author;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AuthorsExport implements FromCollection
{
    public function collection()
    {
        return Author::with('user')
            ->get()
            ->map(function ($author) {
                return [
                    'Name' => $author->name,
                    'Photo' => asset('images/' . $author->photo),
                    'Added By' => $author->user->name ?? 'N/A',
                ];
            });
    }

    public function headings(): array
    {
        return ['Name', 'Photo', 'Added By'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
