<?php

namespace App\Http\Controllers;

use App\Http\Requests\Expenses\StoreExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
}

