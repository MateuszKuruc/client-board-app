<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class FinanceService
{

    private function paymentsQuery(string $year, string $monthNum): Builder
    {
        return Payment::with('project.client')
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $monthNum);
    }

    private function expensesQuery(string $year, string $monthNum): Builder
    {
        return Expense::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $monthNum);
    }

    public function getActiveProjectsCount(): int
    {
        return Project::where('active', true)->count();
    }

    public function getActiveSubscriptionsValues(Carbon $date = null): float
    {
        $startOfCurrentMonth = $date ? $date->copy()->startOfMonth() : Carbon::now()->startOfMonth();

        return Project::with('payments')
            ->where('type', 'Subskrypcja')
            ->whereDate('end_date', '>=', $startOfCurrentMonth)
            ->sum('price');
    }

    public function getMonthlyTotalPayments(string $year, string $monthNum): int
    {
        return $this->paymentsQuery($year, $monthNum)
            ->sum('amount');
    }

    public function getMonthlyTotalExpenses(string $year, string $monthNum): int
    {
        return $this->expensesQuery($year, $monthNum)
            ->sum('amount');
    }

    public function getPayments(string $year, string $monthNum)
    {
        return $this->paymentsQuery($year, $monthNum)
            ->paginate(10)
            ->withQueryString();
    }

    public function getExpenses(string $year, string $monthNum)
    {
        return $this->expensesQuery($year, $monthNum)
            ->paginate(10)
            ->withQueryString();
    }

    public function getChangeInPayments(Carbon $current): ?float
    {
        $year = $current->format('Y');
        $monthNum = $current->format('m');

        $thisMonth = $this->getMonthlyTotalPayments($year, $monthNum);

        $prev = $current->copy()->subMonth();
        $prevYear = $prev->format('Y');
        $prevMonth = $prev->format('m');

        $lastMonth = $this->getMonthlyTotalPayments($prevYear, $prevMonth);

        return ($thisMonth && $lastMonth)
            ? round((($thisMonth / $lastMonth) - 1) * 100, 2)
            : null;
    }

    public function getChangeInExpenses(Carbon $current): ?float
    {
        $year = $current->format('Y');
        $monthNum = $current->format('m');

        $thisMonth = $this->getMonthlyTotalExpenses($year, $monthNum);

        $prev = $current->copy()->subMonth();
        $prevYear = $prev->format('Y');
        $prevMonth = $prev->format('m');

        $lastMonth = $this->getMonthlyTotalExpenses($prevYear, $prevMonth);

        return ($thisMonth && $lastMonth)
            ? round((($thisMonth / $lastMonth) - 1) * 100, 2)
            : null;
    }

    public function getPreviousMonthSummary(Carbon $current): ?float
    {
        $prev = $current->copy()->subMonth();
        $prevYear = $prev->format('Y');
        $prevMonth = $prev->format('m');

        $lastMonthTotalPayments = $this->getMonthlyTotalPayments($prevYear, $prevMonth);
        $lastMonthTotalExpenses = $this->getMonthlyTotalExpenses($prevYear, $prevMonth);

        return $lastMonthTotalPayments - $lastMonthTotalExpenses;
    }

//    public function getPreviousTotalPayments(Carbon $current): ?float
//    {
//        $prev = $current->copy()->subMonth();
//        $prevYear = $prev->format('Y');
//        $prevMonth = $prev->format('m');
//
//        return $this->getMonthlyTotalPayments($prevYear, $prevMonth);
//    }
}
