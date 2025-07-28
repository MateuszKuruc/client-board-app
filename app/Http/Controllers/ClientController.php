<?php

namespace App\Http\Controllers;

use App\Exports\ClientsExport;
use App\Http\Requests\Clients\StoreClientRequest;
use App\Http\Requests\Clients\UpdateClientRequest;
use App\Http\Requests\Tags\SyncTagsRequest;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $allowedSorts = ['name', 'email', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        $clients = Client::with(['projects.service', 'projects.client'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('source', 'like', '%'.$search.'%');
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('clients/Index', [
            'clients' => $clients,
            'filters' => [
                'search' => $search,
                'sort_by' => $sortBy,
                'sort_dir' => $sortDir,
            ],
        ]);
    }

    public function create(Lead $lead = null)
    {
        return Inertia::render('clients/Create', [
            'lead' => $lead,
        ]);
    }

    public function store(StoreClientRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $leadId = $validated['lead_id'] ?? null;
        unset($validated['lead_id']);

        $client = Client::create($validated);

        if ($leadId) {
            Lead::where('id', $leadId)->update([
                'client_id' => $client->id,
                'converted_at' => now(),
                'phone' => $client->phone,
            ]);
        }

        return redirect()->route('clients.index');
    }

    public function show(Request $request, Client $client)
    {
        $client->load(['projects', 'projects.service', 'projects.payments', 'tags']);
        $tags = Tag::all();

        return Inertia::render('clients/Show', [
            'client' => $client,
            'tags' => $tags
        ]);
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['name']);

        $client->update($validated);

        return redirect()->route('clients.show', $client->slug);
    }

    public function export(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $allowed = ['id', 'name', 'email', 'phone', 'source', 'created_at'];
        if (!in_array($sortBy, $allowed)) {
            $sortBy = 'created_at';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        return Excel::download(new ClientsExport($search, $sortBy, $sortDir), 'clients.xlsx');
    }

    public function updateTags(SyncTagsRequest $request, Client $client)
    {
        $validated = $request->validated();

        $client->tags()->sync($validated['tags']);

        return redirect()->route('clients.show', ['client' => $client->slug]);
    }
}
