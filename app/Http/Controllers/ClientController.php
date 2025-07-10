<?php

namespace App\Http\Controllers;

use App\Exports\ClientsExport;
use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $clients = Client::with(['projects.service', 'projects.client'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('clients/Index', [
            'clients' => $clients,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function show(Request $request, Client $client)
    {
        return Inertia::render('clients/Show', [
            'client' => $client,
        ]);
    }

    public function export(Request $request)
    {
        $search = $request->input('search');

        return Excel::download(new ClientsExport($search), 'clients.xlsx');
    }
}
