<?php

namespace App\Exports;

use App\Models\MoneySave;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MoneySavesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $month;
    protected $year;

    public function __construct($month = null, $year = null)
    {
        $this->month = $month;
        $this->year  = $year;
    }

    public function collection()
    {
        $query = MoneySave::orderBy('date_saved', 'desc');

        if ($this->month && $this->year) {
            $query->whereMonth('date_saved', $this->month)
                  ->whereYear('date_saved', $this->year);
        } elseif ($this->year) {
            $query->whereYear('date_saved', $this->year);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['Date', 'BF Saved (Rp)', 'GF Saved (Rp)', 'Total (Rp)', 'Surplus (Rp)'];
    }

    public function map($item): array
    {
        return [
            \Carbon\Carbon::parse($item->date_saved)->format('M d, Y'),
            $item->amount_bf_saved,
            $item->amount_gf_saved,
            $item->total,
            $item->surplus,
        ];
    }
}