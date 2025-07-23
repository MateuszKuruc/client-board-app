<?php

namespace App\Http\Controllers;

use App\Exports\ProjectsExport;
use App\Http\Requests\Projects\StoreProjectRequest;
use App\Http\Requests\Tags\SyncTagsRequest;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Models\Client;
use App\Models\Project;
use App\Models\Service;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $allowedSorts = ['price', 'start_date', 'end_date', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        $projects = Project::with('client', 'service', 'payments', 'users')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%');
                    });
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('projects/Index', [
            'projects' => $projects,
            'filters' => [
                'search' => $search,
                'sort_by' => $sortBy,
                'sort_dir' => $sortDir,
            ]
        ]);
    }

    public function create(Client $client = null)
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
            'client' => $client,
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

        $project->load('client', 'service', 'payments', 'users', 'tags');

        $services = Service::all();
        $users = User::get(['id', 'name']);

        $tags = Tag::all();

        return Inertia::render('projects/Show', [
            'project' => $project,
            'services' => $services,
            'users' => $users,
            'tags' => $tags
        ]);
    }

    public function update(UpdateProjectRequest $request, Client $client, Project $project)
    {
        $validated = $request->validated();

        $userIds = Arr::pull($validated, 'user_ids', []);

        $project->update($validated);
        $project->users()->sync($userIds);


        return redirect()->route('projects.show', ['client' => $client->slug, 'project' => $project->id]);
    }

    public function export(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $allowed = ['name', 'price', 'start_date', 'end_date', 'created_at'];
        if (!in_array($sortBy, $allowed)) {
            $sortBy = 'created_at';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        return Excel::download(new ProjectsExport($search, $sortBy, $sortDir), 'projects.xlsx');
    }

    public function updateTags(SyncTagsRequest $request, Project $project)
    {
        $project->load('client');

        $validated = $request->validated();

        $project->tags()->sync($validated['tags']);

        return redirect()->route('projects.show', ['client' => $project->client->slug, 'project' => $project->id]);
    }
}
