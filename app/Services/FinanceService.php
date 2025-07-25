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
        [$year, $monthNum] = $this->getYearMonth($current);
        $thisMonth = $this->getMonthlyTotalPayments($year, $monthNum);

        [$prevYear, $prevMonth] = $this->getYearMonth($current->copy()->subMonth());
        $lastMonth = $this->getMonthlyTotalPayments($prevYear, $prevMonth);

        return ($thisMonth && $lastMonth)
            ? round((($thisMonth / $lastMonth) - 1) * 100, 2)
            : null;
    }

    public function getChangeInExpenses(Carbon $current): ?float
    {
        [$year, $monthNum] = $this->getYearMonth($current);
        $thisMonth = $this->getMonthlyTotalExpenses($year, $monthNum);

        [$prevYear, $prevMonth] = $this->getYearMonth($current->copy()->subMonth());
        $lastMonth = $this->getMonthlyTotalExpenses($prevYear, $prevMonth);

        return ($thisMonth && $lastMonth)
            ? round((($thisMonth / $lastMonth) - 1) * 100, 2)
            : null;
    }

    public function getPreviousMonthSummary(Carbon $current): ?float
    {
        [$prevYear, $prevMonth] = $this->getYearMonth($current->copy()->subMonth());

        $lastMonthTotalPayments = $this->getMonthlyTotalPayments($prevYear, $prevMonth);
        $lastMonthTotalExpenses = $this->getMonthlyTotalExpenses($prevYear, $prevMonth);

        return $lastMonthTotalPayments - $lastMonthTotalExpenses;
    }

    public function getStandardProjectsCount(string $year, string $monthNum)
    {
        return $this->projectsQuery($year, $monthNum)->where('type', 'Standard')->count();
    }

    public function getSubProjectsCount(string $year, string $monthNum)
    {
        return $this->projectsQuery($year, $monthNum)->where('type', 'Subskrypcja')->count();
    }

    public function getSubPercentage(string $year, string $monthNum): ?float
    {
        $standardProjectsCount = $this->getStandardProjectsCount($year, $monthNum);
        $subProjectsCount = $this->getSubProjectsCount($year, $monthNum);
        $total = $standardProjectsCount + $subProjectsCount;

        return $total
            ? round(($subProjectsCount / $total) * 100, 2)
            : 0.0;
    }

    public function getPreviousSubPercentage(string $year, string $monthNum): ?float
    {
        $current = Carbon::createFromFormat('Y-m', $year.'-'.$monthNum);
        [$prevYear, $prevMonth] = $this->getYearMonth($current->copy()->subMonth());

        return $this->getSubPercentage($prevYear, $prevMonth);
    }

    public function getAveragePayment(string $year, string $monthNum): float
    {
        $average = $this->paymentsQuery($year, $monthNum)
            ->avg('amount') ?? 0.0;

        return round($average, 2);

    }

    public function getAverageSubPayment(string $year, string $monthNum): float
    {
        $averageSub = Payment::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $monthNum)
            ->whereHas('project', fn($q) => $q->where('type', 'Subskrypcja'))
            ->avg('amount') ?? 0.0;

        return round($averageSub, 2);
    }

    public function getAverageStandardPayment(string $year, string $monthNum): float
    {
        $averageStandard = Payment::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $monthNum)
            ->whereHas('project', fn($q) => $q->where('type', 'Standard'))
            ->avg('amount') ?? 0.0;

        return round($averageStandard, 2);
    }

    public function biggestSub(string $year, string $monthNum): float
    {
        $maxSub = Payment::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $monthNum)
            ->whereHas('project', fn($q) => $q->where('type', 'Subskrypcja'))
            ->max('amount');

        return round($maxSub ?? 0.0, 2);
    }

//    public function getAllProjectsCount(string $year, string $monthNum): int
//    {
//        $totalSubs = $this->getSubProjectsCount($year, $monthNum);
//        $totalStandard = $this->getStandardProjectsCount($year, $monthNum);
//
//        return ($totalSubs && $totalStandard)
//            ? $totalSubs + $totalStandard
//            : 0;
//    }

    public function getMonthlySubPaymentsTotal(string $year, string $monthNum): float
    {
        return $this->paymentsQuery($year, $monthNum)
            ->whereHas('project', fn(Builder $q) => $q->where('type', 'Subskrypcja'))
            ->sum('amount');
    }

    public function getMonthlyStandardPaymentsTotal(string $year, string $monthNum): float
    {
        return $this->paymentsQuery($year, $monthNum)
            ->whereHas('project', fn(Builder $q) => $q->where('type', 'Standard'))
            ->sum('amount');
    }
}



