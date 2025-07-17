<?php

namespace App\Http\Controllers;

use App\Exports\ProjectsExport;
use App\Http\Requests\Expenses\StoreExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $expenses = Expense::when($search, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('type', 'like', '%'.$search.'%');
        })
            ->orderBy('payment_date', 'desc')
            ->paginate(10)
            ->withQueryString();;

        return Inertia::render('expenses/Index', [
            'expenses' => $expenses,
            'filters' => [
                'search' => $search,
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
        return Inertia::render('expenses/Show', [
            'expense' => $expense
        ]);
    }

    public function update(StoreExpenseRequest $request, Expense $expense)
    {
        $validated = $request->validated();

        $expense->update($validated);

        return redirect()->route('expenses.show', $expense->id);
    }

    public function export(Request $request)
    {
        $search = $request->input('search');

        return Excel::download(new ProjectsExport($search), 'expenses.xlsx');
    }
}

