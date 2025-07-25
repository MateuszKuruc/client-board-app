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

    private function projectsQuery(string $year, string $monthNum): Builder
    {
        return Project::whereHas('payments', function (Builder $query) use ($year, $monthNum) {
            $query->whereYear('payment_date', $year)
                ->whereMonth('payment_date', $monthNum);
        });
    }

    private function getYearMonth(Carbon $date): array
    {
        return [
            $date->format('Y'),
            $date->format('m')
        ];
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

    public function getMonthlyTotalPayments(Carbon $date): int
    {
        return $this->paymentsQuery($date->format('Y'), $date->format('m'))
            ->sum('amount');
    }

    public function getMonthlyTotalExpenses(Carbon $date): int
    {
        return $this->expensesQuery($date->format('Y'), $date->format('m'))
            ->sum('amount');
    }

    public function getPayments(Carbon $date)
    {
        return $this->paymentsQuery($date->format('Y'), $date->format('m'))
            ->paginate(10)
            ->withQueryString();
    }

    public function getExpenses(Carbon $date)
    {
        return $this->expensesQuery($date->format('Y'), $date->format('m'))
            ->paginate(10)
            ->withQueryString();
    }

    public function getChangeInPayments(Carbon $date): ?float
    {
        [$year, $monthNum] = $this->getYearMonth($date);
        $thisMonth = $this->getMonthlyTotalPayments($date);

        [$prevYear, $prevMonth] = $this->getYearMonth($date->copy()->subMonth());
        $lastMonth = $this->getMonthlyTotalPayments($date);

        return ($thisMonth && $lastMonth)
            ? round((($thisMonth / $lastMonth) - 1) * 100, 2)
            : null;
    }

    public function getChangeInExpenses(Carbon $date): ?float
    {
        [$year, $monthNum] = $this->getYearMonth($date);
        $thisMonth = $this->getMonthlyTotalExpenses($date);

        [$prevYear, $prevMonth] = $this->getYearMonth($date->copy()->subMonth());
        $lastMonth = $this->getMonthlyTotalExpenses($date);

        return ($thisMonth && $lastMonth)
            ? round((($thisMonth / $lastMonth) - 1) * 100, 2)
            : null;
    }

    public function getPreviousMonthSummary(Carbon $date): ?float
    {
        [$prevYear, $prevMonth] = $this->getYearMonth($date->copy()->subMonth());

        $lastMonthTotalPayments = $this->getMonthlyTotalPayments($date);
        $lastMonthTotalExpenses = $this->getMonthlyTotalExpenses($date);

        return $lastMonthTotalPayments - $lastMonthTotalExpenses;
    }

    public function getStandardProjectsCount(Carbon $date)
    {
        return $this->projectsQuery($date->format('Y'), $date->format('m'))->where('type', 'Standard')->count();
    }

    public function getSubProjectsCount(Carbon $date)
    {
        return $this->projectsQuery($date->format('Y'), $date->format('m'))->where('type', 'Subskrypcja')->count();
    }

    public function getSubPercentage(Carbon $date): ?float
    {
        $standardProjectsCount = $this->getStandardProjectsCount($date);
        $subProjectsCount = $this->getSubProjectsCount($date);
        $total = $standardProjectsCount + $subProjectsCount;

        return $total
            ? round(($subProjectsCount / $total) * 100, 2)
            : 0.0;
    }


    public function getPreviousSubPercentage(Carbon $date): ?float
    {
        $prevMonth = $date->copy()->subMonth();

        return $this->getSubPercentage($prevMonth);
    }

    public function getAveragePayment(Carbon $date): float
    {
        $average = $this->paymentsQuery($date->format('Y'), $date->format('m'))
            ->avg('amount') ?? 0.0;

        return round($average, 2);

    }

    public function getAverageSubPayment(Carbon $date): float
    {
        $averageSub = Payment::whereYear('payment_date', $date->format('Y'))
            ->whereMonth('payment_date', $date->format('m'))
            ->whereHas('project', fn($q) => $q->where('type', 'Subskrypcja'))
            ->avg('amount') ?? 0.0;

        return round($averageSub, 2);
    }

    public function getAverageStandardPayment(Carbon $date): float
    {
        $averageStandard = Payment::whereYear('payment_date', $date->format('Y'))
            ->whereMonth('payment_date', $date->format('m'))
            ->whereHas('project', fn($q) => $q->where('type', 'Standard'))
            ->avg('amount') ?? 0.0;

        return round($averageStandard, 2);
    }

    public function biggestSub(Carbon $date): float
    {
        $maxSub = Payment::whereYear('payment_date', $date->format('Y'))
            ->whereMonth('payment_date', $date->format('m'))
            ->whereHas('project', fn($q) => $q->where('type', 'Subskrypcja'))
            ->max('amount');

        return round($maxSub ?? 0.0, 2);
    }

    public function getMonthlySubPaymentsTotal(Carbon $date): float
    {
        return $this->paymentsQuery($date->format('Y'), $date->format('m'))
            ->whereHas('project', fn(Builder $q) => $q->where('type', 'Subskrypcja'))
            ->sum('amount');
    }

    public function getMonthlyStandardPaymentsTotal(Carbon $date): float
    {
        return $this->paymentsQuery($date->format('Y'), $date->format('m'))
            ->whereHas('project', fn(Builder $q) => $q->where('type', 'Standard'))
            ->sum('amount');
    }

    public function getDashboardData(Carbon $date): array
    {
        $totalPayments = $this->getMonthlyTotalPayments($date);
        $totalExpenses = $this->getMonthlyTotalExpenses($date);
        $summary = $totalPayments - $totalExpenses;

        $previousSummary = $this->getPreviousMonthSummary($date);
        $changeInSummary = ($summary && $previousSummary)
            ? round((($summary / $previousSummary) - 1) * 100, 2)
            : null;

        return [
            'payments' => $this->getPayments($date),
            'expenses' => $this->getExpenses($date),

            'totalPayments' => $totalPayments,
            'totalExpenses' => $totalExpenses,
            'summary' => $summary,

            'changeInPayments' => $this->getChangeInPayments($date),
            'changeInExpenses' => $this->getChangeInExpenses($date),
            'changeInSummary' => $changeInSummary,

            'subCount' => $this->getSubProjectsCount($date),
            'standardCount' => $this->getStandardProjectsCount($date),
            'subPercentage' => $this->getSubPercentage($date),
            'previousSubPercentage' => $this->getPreviousSubPercentage($date),

            'averagePayment' => $this->getAveragePayment($date),
            'averageSub' => $this->getAverageSubPayment($date),
            'averageStandard' => $this->getAverageStandardPayment($date),
            'biggestSub' => $this->biggestSub($date),

            'subPaymentsTotal' => $this->getMonthlySubPaymentsTotal($date),
            'standardPaymentsTotal' => $this->getMonthlyStandardPaymentsTotal($date),
        ];
    }
}



