<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    private function getCurrentPaymentsQuery(Carbon $date)
    {
        $currentStart = $date->copy()->startOfMonth();
        $currentEnd = $date->copy()->endOfMonth();

        return Payment::with('project.client')
            ->whereBetween('payment_date', [$currentStart, $currentEnd])
            ->orderBy('payment_date', 'desc');
    }

    private function getCurrentExpensesQuery(Carbon $date)
    {
        $currentStart = $date->copy()->startOfMonth();
        $currentEnd = $date->copy()->endOfMonth();

        return Expense::whereBetween('payment_date', [$currentStart, $currentEnd])
            ->orderBy('payment_date', 'desc');
    }

    public function getActiveProjectsCount()
    {
        return Project::where('active', true)->count();
    }

    public function getActiveSubscriptionsValues()
    {
        $currentMonthStart = Carbon::now()->endOfMonth();

        return (float) Project::where('type', 'Subskrypcja')
            ->where('active', true)
            ->where(function ($query) use ($currentMonthStart) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $currentMonthStart);
            })
            ->sum('price');
    }

    public function getLast3MonthsAverageNet(Carbon $date): float
    {
        $startDate = $date->copy()->subMonths(2)->startOfMonth();
        $endDate = $date->copy()->endOfMonth();

        $totalPayments = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        $totalExpenses = Expense::whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        return round(($totalPayments - $totalExpenses) / 3, 2);
    }

    public function getDashboardData(Carbon $date): array
    {
        $previousStart = $date->copy()->subMonth()->startOfMonth();
        $previousEnd = $date->copy()->subMonth()->endOfMonth();

        $paginatedCurrentPayments = $this->getCurrentPaymentsQuery($date)
            ->paginate(10)
            ->withQueryString();

        $paginatedCurrentExpenses = $this->getCurrentExpensesQuery($date)
            ->paginate(10)
            ->withQueryString();

        $currentPayments = $this->getCurrentPaymentsQuery($date)->get();

        $previousPayments = Payment::with('project.client')
            ->whereBetween('payment_date', [$previousStart, $previousEnd])
            ->get();

        $currentExpenses = $this->getCurrentExpensesQuery($date)
            ->get();

        $previousExpenses = Expense::whereBetween('payment_date', [$previousStart, $previousEnd])
            ->get();

        $totalPayments = round($currentPayments->sum('amount'), 2);
        $totalExpenses = round($currentExpenses->sum('amount'), 2);
        $summary = round($totalPayments - $totalExpenses, 2);

        $previousTotalPayments = $previousPayments->sum('amount');
        $previousTotalExpenses = $previousExpenses->sum('amount');
        $previousSummary = $previousTotalPayments - $previousTotalExpenses;

        $changeInPayments = ($totalPayments && $previousTotalPayments)
            ? round((($totalPayments / $previousTotalPayments) - 1) * 100, 2)
            : 0.0;

        $changeInExpenses = ($totalExpenses && $previousTotalExpenses)
            ? round((($totalExpenses / $previousTotalExpenses) - 1) * 100, 2)
            : 0.0;

        $changeInSummary = ($summary && $previousSummary)
            ? round((($summary / $previousSummary) - 1) * 100, 2)
            : 0.0;

        $currentSubPayments = $currentPayments->filter(function ($payment) {
            return $payment->project->type === 'Subskrypcja';
        });

        $currentStandardPayments = $currentPayments->filter(function ($payment) {
            return $payment->project->type === 'Standard';
        });

        $previousSubPayments = $previousPayments->filter(function ($payment) {
            return $payment->project->type === 'Subskrypcja';
        });

        $previousStandardPayments = $previousPayments->filter(function ($payment) {
            return $payment->project->type === 'Standard';
        });

        $subCount = $currentSubPayments->count();
        $standardCount = $currentStandardPayments->count();
        $previousSubCount = $previousSubPayments->count();
        $previousStandardCount = $previousStandardPayments->count();

        $totalCurrentProjects = $subCount + $standardCount;
        $totalPreviousProjects = $previousSubCount + $previousStandardCount;

        $subPercentage = $totalCurrentProjects
            ? round(($subCount / $totalCurrentProjects) * 100, 2)
            : 0.0;

        $previousSubPercentage = $totalPreviousProjects
            ? round(($previousSubCount / $totalPreviousProjects) * 100, 2)
            : 0.0;

        $averagePayment = $currentPayments->count()
            ? round($currentPayments->avg('amount'), 2)
            : 0.0;

        $averageSub = $currentPayments->count()
            ? round($currentSubPayments->avg('amount'), 2)
            : 0.0;

        $averageStandard = $currentPayments->count()
            ? round($currentStandardPayments->avg('amount'), 2)
            : 0.0;

        $biggestSub = $currentSubPayments->count()
            ? round($currentSubPayments->max('amount'), 2)
            : 0.0;

        $subPaymentsTotal = $currentSubPayments->sum('amount');
        $standardPaymentsTotal = $currentStandardPayments->sum('amount');

        return [
            'payments' => $paginatedCurrentPayments,
            'expenses' => $paginatedCurrentExpenses,

            'totalPayments' => $totalPayments,
            'totalExpenses' => $totalExpenses,
            'summary' => $summary,

            'changeInPayments' => $changeInPayments,
            'changeInExpenses' => $changeInExpenses,
            'changeInSummary' => $changeInSummary,

            'subCount' => $subCount,
            'standardCount' => $standardCount,
            'subPercentage' => $subPercentage,
            'previousSubPercentage' => $previousSubPercentage,

            'averagePayment' => $averagePayment,
            'averageSub' => $averageSub,
            'averageStandard' => $averageStandard,
            'biggestSub' => $biggestSub,

            'subPaymentsTotal' => $subPaymentsTotal,
            'standardPaymentsTotal' => $standardPaymentsTotal,
        ];
    }
}


