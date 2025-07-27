<?php

namespace App\Http\Controllers;

use App\Services\FinanceService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class FinanceController extends Controller
{

    public function __construct(
        private FinanceService $financeService,
    ) {
    }

    public function index(Request $request)
    {
        $tab = $request->input('tab', 'Podsumowanie');
        $month = $request->input('month', now()->format('Y-m'));
        $currentDate = Carbon::createFromFormat('Y-m', $month);

        $dashboardData = $this->financeService->getDashboardData($currentDate);

        $activeProjects = $this->financeService->getActiveProjectsCount();
        $activeSubsValue = $this->financeService->getActiveSubscriptionsValues();
        $last3MonthsAverageNet = $this->financeService->getLast3MonthsAverageNet($currentDate);

        return Inertia::render('finances/Index', [
            ...$dashboardData,
            'activeProjects' => $activeProjects,
            'activeSubsValue' => $activeSubsValue,
            'last3MonthsAverageNet' => $last3MonthsAverageNet,
            'month' => $month,
            'tab' => $tab,
        ]);
    }
}
