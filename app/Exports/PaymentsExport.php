<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsExport implements FromCollection, WithHeadings
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
        return Payment::with('project', 'project.client')
            ->when($this->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('project', function ($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%');
                    })->orWhereHas('project.client', function ($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%');
                    });
                });
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->get()
            ->map(function ($payment) {
                $statusText = match ($payment->status) {
                    'pending' => 'Oczekująca',
                    'paid' => 'Opłacona',
                    'cancelled' => 'Anulowana',
                    default => $payment->status,
                };

                return [
                    $payment->id,
                    $payment->project->name,
                    $payment->project->client->name,
                    $payment->amount,
                    $statusText,
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
