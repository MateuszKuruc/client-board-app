<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Project;
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
        $activeProjects = $this->financeService->getActiveProjectsCOunt();
        $activeSubsValue = $this->financeService->getActiveSubscriptionsValues();

        $tab = $request->input('tab', 'Podsumowanie');
        $month = $request->input('month', now()->format('Y-m'));
        $current = Carbon::createFromFormat('Y-m', $month);
        $year = $current->format('Y');
        $monthNum = $current->format('m');

        $payments = $this->financeService->getPayments($year, $monthNum);
        $expenses = $this->financeService->getExpenses($year, $monthNum);

        $totalPayments = $this->financeService->getMonthlyTotalPayments($year, $monthNum);
        $totalExpenses = $this->financeService->getMonthlyTotalExpenses($year, $monthNum);

        $changeInPayments = $this->financeService->getChangeInPayments($current);
        $changeInExpenses = $this->financeService->getChangeInExpenses($current);

        $summary = $totalPayments - $totalExpenses;
        $previousSummary = $this->financeService->getPreviousMonthSummary($current);

        $changeInSummary = ($summary && $previousSummary)
            ? round((($summary / $previousSummary) - 1) * 100, 2)
            : null;

        return Inertia::render('finances/Index', [
            'month' => $month,
            'tab' => $tab,
            'payments' => $payments,
            'expenses' => $expenses,
            'totalPayments' => $totalPayments,
            'totalExpenses' => $totalExpenses,
            'changeInPayments' => $changeInPayments,
            'changeInExpenses' => $changeInExpenses,
            'summary' => $summary,
            'changeInSummary' => $changeInSummary,
            'activeSubsValue' => $activeSubsValue,
            'activeProjects' => $activeProjects,
        ]);
    }
}
