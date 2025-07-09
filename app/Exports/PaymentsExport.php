<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsExport implements FromCollection, WithHeadings
{
    protected $search
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($search)
    {
        $this->search = $search;
    }
    public function collection()
    {
        return Payment::with('project', 'project.client')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('project', function ($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%');
                    })->orWhereHas('project.client', function ($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%');
                    });
                });
            })
            ->get()
            ->map(function ($payment) {
                return [
                    $payment->id,
                    $payment->project->name,
                    $payment->project->client->name,
                    $payment->amount,
                    $payment->status,
                    $payment->payment_date ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Projekt',
            'Klient',
            'Kwota',
            'Status',
            'Data płatności'
        ];
    }
}
