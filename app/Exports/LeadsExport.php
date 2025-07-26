<?php

namespace App\Exports;

use App\Models\Lead;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeadsExport implements FromCollection, WithHeadings
{
    protected $search;

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
        return Lead::when($this->search, function ($query, $search) {
            $query->where('email', 'like', '%'.$search.'%')
                ->orWhere('phone', 'like', '%'.$search.'%');
        })
            ->orderBy($this->sortBy, $this->sortDir)
            ->get()
            ->map(function ($lead) {
                $statusText = $lead->converted_at
                    ? 'Przekonwertowany'
                    : 'Lead';
                $conversionDate = $lead->converted_at
                    ? Carbon::parse($lead->converted_at)->format('d-m-Y')
                    : '-';
                return [
                    $lead->id,
                    $lead->email,
                    $lead->phone,
                    $statusText,
                    $conversionDate,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Email',
            'Telefon',
            'Status',
            'Data konwersji'
        ];
    }
}
