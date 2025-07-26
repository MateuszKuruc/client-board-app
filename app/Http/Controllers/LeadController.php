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
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $allowedSorts = ['converted_at', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        $leads = Lead::with('client')
        ->when($search, function ($query) use ($search) {
            $query->where('email', 'like', '%'.$search.'%')
                ->orWhere('phone', 'like', '%'.$search.'%');
        })
            ->orderBy($sortBy, $sortDir)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('leads/Index', [
            'leads' => $leads,
            'filters' => [
                'search' => $search,
                'sort_by' => $sortBy,
                'sort_dir' => $sortDir,
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
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $allowed = ['converted_at', 'created_at'];
        if (!in_array($sortBy, $allowed)) {
            $sortBy = 'created_at';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        return Excel::download(new LeadsExport($search, $sortBy, $sortDir), 'leads.xlsx');
    }
}
