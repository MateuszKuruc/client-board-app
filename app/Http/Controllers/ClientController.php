<?php

namespace App\Http\Controllers;

use App\Exports\ClientsExport;
use App\Http\Requests\Clients\StoreClientRequest;
use App\Models\Client;
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

        $clients = Client::with(['projects.service', 'projects.client'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('clients/Index', [
            'clients' => $clients,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('clients/Create');
    }

    public function store(StoreClientRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        Client::create($validated);

        return redirect()->route('clients.index');
    }

    public function show(Request $request, Client $client)
    {
        $client->load(['projects', 'projects.service', 'projects.payments']);

        return Inertia::render('clients/Show', [
            'client' => $client,
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clients')->ignore($client->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients')->ignore($client->id),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'nip' => ['nullable', 'string', 'max:20'],
            'source' => [
                'required',
                Rule::in(['Strona internetowa', 'Social media', 'Polecenie', 'Ads', 'Grupki', 'Useme', 'Inne']),
            ],
            'location' => ['required', Rule::in(['local', 'remote', 'international'])],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $client->update($validated);

        return redirect()->route('clients.show', $client->slug);
    }

    public function export(Request $request)
    {
        $search = $request->input('search');

        return Excel::download(new ClientsExport($search), 'clients.xlsx');
    }
}
