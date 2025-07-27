<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Payment;
use App\Models\Project;
use Carbon\Carbon;

class DashboardService
{
    public function getLatestPayments()
    {
        return Payment::with('project.client')
            ->latest()
            ->take(5)
            ->get();
    }

    public function getLatestClients()
    {
        return Client::latest()
            ->take(5)
            ->get();
    }

    public function getLongestClients()
    {
        return Client::with([
            'projects' => function ($query) {
                $query->where('active', true)->orderBy('start_date');
            }
        ])
            ->whereHas('projects', function ($query) {
                $query->where('active', true);
            })
            ->get()
            ->sortBy(function ($client) {
                return $client->projects->first()?->start_date;
            })
            ->take(5);
    }

    public function getActiveProjects()
    {
        return Project::where('active', true)
            ->count();
    }

    public function getNewestClient()
    {
        return Client::latest()
            ->first();
    }

    public function getNewestProject()
    {
        return Project::with('client')
            ->latest()
            ->first();
    }

    public function getEndingProjects()
    {
        return Project::with([
            'payments' => function ($query) {
                $query->where('status', 'paid');
            }
        ])
            ->whereHas('payments', function ($query) {
                $query->where('status', 'paid');
            })
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [Carbon::now(), Carbon::now()->addWeek()])
            ->orderBy('end_date')
            ->take(5)
            ->get();
    }

    public function getRecentlyEndedProjects()
    {
        return Project::with('client')
            ->where('active', false)
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [Carbon::now()->subMonth(), Carbon::now()])
            ->orderBy('end_date', 'desc')
            ->take(5)
            ->get();
    }

    public function getBiggestValueClients()
    {
        return Client::with('projects.payments')
            ->whereHas('projects.payments', function ($query) {
                $query->where('status', 'paid');
            })
            ->get()
            ->sortByDesc(function ($client) {
                return $client->projects->sum(function ($project) {
                    return $project->payments->where('status', 'paid')->sum('amount');
                });
            })
            ->take(5)
            ->values();
    }
}
