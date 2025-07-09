<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientsExport implements FromCollection, WithHeadings
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
        return Client::with(['projects.service', 'projects.client'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            })
            ->get()
            ->map(function ($client) {
                return [
                    $client->id,
                    $client->name,
                    $client->email,
                    $client->phone,
                    $client->source
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Klient',
            'Email',
            'Telefon',
            'Źródło'
        ];
    }
}
