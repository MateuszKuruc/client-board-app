<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $currentDate = Carbon::now();
        $endOfCurrentMonth = $currentDate->endOfMonth();

        $activeSubs = Project::with('payments')
            ->where('type', 'Subskrypcja')
            ->whereDate('end_date', '>=', $endOfCurrentMonth)
            ->get();

        $activeSubsValue = $activeSubs->sum('price');

        $tab = $request->input('tab', 'Podsumowanie');

        $month = $request->input('month', now()->format('Y-m'));
        $current = Carbon::createFromFormat('Y-m', $month);
        $year = $current->format('Y');
        $monthNum = $current->format('m');


        $paymentsQuery = Payment::with('project.client')
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $monthNum);

        $expensesQuery = Expense::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $monthNum);


        $payments = (clone $paymentsQuery)->paginate(10)->withQueryString();
        $expenses = (clone $expensesQuery)->paginate(10)->withQueryString();

        $totalPayments = (clone $paymentsQuery)->sum('amount');
        $totalExpenses = (clone $expensesQuery)->sum('amount');

        $prev = $current->copy()->subMonth();
        $prevYear = $prev->format('Y');
        $prevMonth = $prev->format('m');

        $previousTotalPayments = Payment::with('project.client')
            ->whereYear('payment_date', $prevYear)
            ->whereMonth('payment_date', $prevMonth)
            ->sum('amount');

        $previousTotalExpenses = Expense::whereYear('payment_date', $prevYear)
            ->whereMonth('payment_date', $prevMonth)
            ->sum('amount');

        $changeInPayments = ($totalPayments && $previousTotalPayments)
            ? round((($totalPayments / $previousTotalPayments) - 1) * 100, 2)
            : null;

        $changeInExpenses = ($totalExpenses && $previousTotalExpenses)
            ? round((($totalExpenses / $previousTotalExpenses) - 1) * 100, 2)
            : null;

        $summary = $totalPayments - $totalExpenses;

        $previousSummary = $previousTotalPayments - $previousTotalExpenses;

        $changeInSummary = ($totalPayments && $previousTotalPayments)
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
        ]);
    }
}
