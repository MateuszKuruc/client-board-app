<?php

namespace App\Http\Controllers;

use App\Exports\ProjectsExport;
use App\Models\Client;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $projects = Project::with('client', 'service', 'payments')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%');
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

    public function show(Request $request, Client $client, Project $project)
    {
        if ($project->client_id !== $client->id) {
            abort(403);
        }

        $project->load('client', 'service', 'payments');

        $services = Service::all();

        return Inertia::render('projects/Show', [
            'project' => $project,
            'services' => $services,
        ]);
    }

    public function update(Request $request, Client $client, Project $project)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'service_id' => ['required', 'integer', Rule::exists('services', 'id')],
            'active' => ['required', 'boolean'],
            'price' => ['required', 'numeric', 'max:999999.99'],
            'type' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', ['client' => $client->slug, 'project' => $project->id]);
    }

    public
    function export(
        Request $request
    ) {
        $search = $request->input('search');

        return Excel::download(new ProjectsExport($search), 'projects.xlsx');
    }
}
