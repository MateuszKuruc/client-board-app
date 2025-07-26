<?php

namespace App\Exports;

use App\Models\Expense;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpensesExport implements FromCollection, WithHeadings
{
    protected $search;
    protected $sortBy;
    protected $sortDir;

    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($search, $sortBy, $sortDir)
    {
        $this->search = $search;
        $this->sortBy = $sortBy;
        $this->sortDir = $sortDir;
    }

    public function collection()
    {
        return Expense::when($this->search, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('type', 'like', '%'.$search.'%');
        })
            ->orderBy($this->sortBy, $this->sortDir)
            ->get()
            ->map(function ($expense) {
                $statusText = match ($expense->is_paid) {
                    0 => 'Nieopłacona',
                    1 => "Opłacona",
                };
                return [
                    $expense->id,
                    $expense->name,
                    $expense->amount,
                    $expense->type,
                    $statusText,
                    $expense->payment_date,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Koszt',
            'Cena',
            'Rodzaj płatności',
            'Status płatności',
            'Data płatności'
        ];
    }
}
