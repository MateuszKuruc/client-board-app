<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectsExport implements FromCollection, WithHeadings
{
    protected $search;

    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($search)
    {
        $this->search = $search;
    }

    public function collection()
    {
        return Project::with('client', 'service')
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%');
                    });
            })
            ->get()
            ->map(function ($project) {
                return [
                    $project->id,
                    $project->name,
                    $project->client->name ?? '-',
                    $project->service->name ?? '-',
                    $project->price,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nazwa projektu',
            'Klient',
            'Usługa',
            'Cena'
        ];
    }
}
