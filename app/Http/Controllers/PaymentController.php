<?php

namespace App\Http\Controllers;

use App\Exports\PaymentsExport;
use App\Http\Requests\Payments\StorePaymentRequest;
use App\Http\Requests\Payments\UpdatePaymentRequest;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{


    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $allowedSorts = ['id', 'status', 'amount', 'payment_date', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        $payments = Payment::with('project', 'project.client')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('project', function ($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%');
                    })->orWhereHas('project.client', function ($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%');
                    });
                });
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('payments/Index', [
            'payments' => $payments,
            'filters' => [
                'search' => $search,
                'sort_by' => $sortBy,
                'sort_dir' => $sortDir,
            ]
        ]);
    }

    public function create(Project $project = null)
    {
        $projects = Project::with('client')->latest()->get();

        if ($project !== null) {
            $project->load('client');
        }

        return Inertia::render('payments/Create', [
            'projects' => $projects,
            'project' => $project,
        ]);
    }

    public function store(StorePaymentRequest $request)
    {
        $validated = $request->validated();

        Payment::create($validated);

        return redirect()->route('payments.index');
    }

    public function show(Request $request, Client $client, Project $project, Payment $payment)
    {
        $payment->load('project.client', 'project.payments');
        $latestPayments = Payment::where('status', 'paid')->orderBy('payment_date', 'desc')->take(3)->get();

        $notes = $payment->notes;

        return Inertia::render('payments/Show', [
            'payment' => $payment,
            'latestPayments' => $latestPayments,
            'notes' => $notes,
        ]);
    }

    public function update(UpdatePaymentRequest $request, Client $client, Project $project, Payment $payment)
    {
        $validated = $request->validated();

        $payment->update($validated);

        return redirect()->route('payments.show',
            ['client' => $client->slug, 'project' => $project->id, 'payment' => $payment->id]);
    }

    public function export(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $allowedSorts = ['id', 'status', 'amount', 'payment_date', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        return Excel::download(new PaymentsExport($search, $sortBy, $sortDir), 'payments.xlsx');
    }
}
