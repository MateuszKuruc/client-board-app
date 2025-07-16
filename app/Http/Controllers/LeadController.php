<?php

namespace App\Http\Controllers;

use App\Exports\LeadsExport;
use App\Http\Requests\Leads\StoreLeadRequest;
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
            $query->where('email', 'like', '%'.$search.'%')
                ->orWhere('phone', 'like', '%'.$search.'%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('leads/Index', [
            'leads' => $leads,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('leads/Create');
    }

    public function store(StoreLeadRequest $request)
    {
        $validated = $request->validated();

        Lead::create($validated);

        return redirect()->route('leads.index');
    }

    public function export(Request $request)
    {
        $search = $request->input('search');

        return Excel::download(new LeadsExport($search), 'leads.xlsx');
    }
}
