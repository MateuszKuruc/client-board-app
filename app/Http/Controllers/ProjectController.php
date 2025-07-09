<?php

namespace App\Http\Controllers;

use App\Exports\ProjectsExport;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $projects = Project::with('client', 'service', 'payments')
            ->when($search, function($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('projects/Index', [
            'projects' => $projects,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }

    public function export(Request $request)
    {
        $search = $request->input('search');

        return Excel::download(new ProjectsExport($search), 'projects.xlsx');
    }
}
