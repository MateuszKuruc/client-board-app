<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeadsExport implements FromCollection, WithHeadings
{
    protected $search;

    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        return Lead::when($this->search, function ($query, $search) {
            $query->where('email', 'like', '%'.$search.'%')
                ->orWhere('phone', 'like', '%'.$search.'%');
        })->get()
            ->map(function ($lead) {
                return [
                    $lead->id,
                    $lead->email,
                    $lead->phone,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Email',
            'Telefon'
        ];
    }
}
