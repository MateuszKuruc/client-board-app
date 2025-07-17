<?php

namespace App\Http\Controllers;

use App\Exports\ProjectsExport;
use App\Http\Requests\Projects\StoreProjectRequest;
use App\Models\Client;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('projects/Index', [
            'projects' => $projects,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }

    public function create()
    {
        $clients = Client::latest()
            ->select('id', 'name')
            ->get()
            ->map(fn($c) => ['value' => $c->id, 'label' => $c->name])
            ->toArray();

        $services = Service::orderBy('id')
            ->select('id', 'name')
            ->get()
            ->map(fn($s) => ['value' => $s->id, 'label' => $s->name])
            ->toArray();

        return Inertia::render('projects/Create', [
            'clients' => $clients,
            'services' => $services
        ]);
    }

    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        Project::create($validated);

        return redirect()->route('projects.index');
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
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
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
