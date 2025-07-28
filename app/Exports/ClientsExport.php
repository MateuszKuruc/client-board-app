<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientsExport implements FromCollection, WithHeadings
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
        return Client::query()
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('source', 'like', '%'.$search.'%');
            })
            ->orderBy($this->sortBy, $this->sortDir)
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
