<?php

namespace App\Exports;

use App\Models\Publisher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PublishersExport implements FromCollection
{
    public function collection()
    {
        return Publisher::with('user')
            ->get()
            ->map(function ($publisher) {
                return [
                    'Name' => $publisher->name,
                    'Logo' => asset('images/' . $publisher->logo),
                    'Added By' => $publisher->user->name ?? 'N/A',
                ];
            });
    }

    public function headings(): array
    {
        return ['Name', 'Logo', 'Added By'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
