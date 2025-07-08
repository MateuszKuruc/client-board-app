<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with('projects.service', 'projects.client')->get();

        return Inertia::render('clients/Index', [
            'clients' => $clients
        ]);
    }
}
