<?php

namespace App\Http\Controllers;

use App\Exports\ExpensesExport;
use App\Exports\ProjectsExport;
use App\Http\Requests\Expenses\StoreExpenseRequest;
use App\Http\Requests\Expenses\UpdateExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $allowedSorts = ['name', 'amount', 'type', 'is_paid', 'payment_date', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        $expenses = Expense::when($search, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('type', 'like', '%'.$search.'%');
        })
            ->orderBy($sortBy, $sortDir)
            ->paginate(10)
            ->withQueryString();;

        return Inertia::render('expenses/Index', [
            'expenses' => $expenses,
            'filters' => [
                'search' => $search,
                'sort_by' => $sortBy,
                'sort_dir' => $sortDir,
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('expenses/Create');
    }

    public function store(StoreExpenseRequest $request)
    {
        $validated = $request->validated();

        Expense::create($validated);

        return redirect()->route('expenses.index');
    }

    public function show(Expense $expense)
    {
        $latestExpenses = Expense::latest()->take(3)->get();
        $biggestExpenses = Expense::where('is_paid', 1)->orderBy('amount', 'desc')->take(3)->get();

        return Inertia::render('expenses/Show', [
            'expense' => $expense,
            'latestExpenses' => $latestExpenses,
            'biggestExpenses' => $biggestExpenses,
        ]);
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $validated = $request->validated();

        $expense->update($validated);

        return redirect()->route('expenses.show', $expense->id);
    }

    public function export(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $allowed = ['id', 'name', 'email', 'phone', 'source', 'created_at'];
        if (!in_array($sortBy, $allowed)) {
            $sortBy = 'created_at';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        return Excel::download(new ExpensesExport($search, $sortBy, $sortDir), 'expenses.xlsx');
    }
}

