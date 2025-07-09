<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;

class LeadsExport implements FromCollection
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
        })->get(['id', 'email', 'phone']);
    }
}
