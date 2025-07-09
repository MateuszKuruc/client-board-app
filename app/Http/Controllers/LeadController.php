<?php

namespace App\Http\Controllers;

use App\Exports\LeadsExport;
use App\Models\Lead;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $leads = Lead::when($search, function ($query) use ($search) {
             $query->where('email', 'like', '%' . $search . '%')
                 ->orWhere('phone', 'like', '%' . $search . '%');
        })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('leads/Index', [
            'leads' => $leads,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }

    public function export(Request $request)
    {
       return Excel::download(new LeadsExport(), 'leads.xlsx');
    }
}
