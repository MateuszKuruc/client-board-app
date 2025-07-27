<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\FinanceService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
    ) {

    }

    public function index()
    {
        $newestClient = $this->dashboardService->getNewestClient();
        $newestProject = $this->dashboardService->getNewestProject();
        $latestPayments = $this->dashboardService->getLatestPayments();
        $latestClients = $this->dashboardService->getLatestClients();
        $longestClients = $this->dashboardService->getLongestClients();
        $activeProjects = $this->dashboardService->getActiveProjects();
        $endingProjects = $this->dashboardService->getEndingProjects();
        $recentlyEndedProjects = $this->dashboardService->getRecentlyEndedProjects();
        $biggestValueClients = $this->dashboardService->getBiggestValueClients();

        return Inertia::render('dashboard/Index', [
            'newestClient' => $newestClient,
            'newestProject' => $newestProject,
            'latestPayments' => $latestPayments,
            'latestClients' => $latestClients,
            'longestClients' => $longestClients,
            'activeProjects' => $activeProjects,
            'endingProjects' => $endingProjects,
            'recentlyEndedProjects' => $recentlyEndedProjects,
            'biggestValueClients' => $biggestValueClients,
        ]);
    }
}
