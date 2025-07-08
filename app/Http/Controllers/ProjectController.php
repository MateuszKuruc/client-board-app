<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('client', 'service', 'payments')->paginate(10);

        return Inertia::render('projects/Index', [
            'projects' => $projects,
        ]);
    }
}
