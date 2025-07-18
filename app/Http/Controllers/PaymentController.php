<?php

namespace App\Http\Controllers;

use App\Exports\PaymentsExport;
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
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('payments/Index', [
            'payments' => $payments,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }

    public function show(Request $request, Client $client, Project $project, Payment $payment)
    {
        $payment->load('project.client');

        return Inertia::render('payments/Show', [
            'payment' => $payment,
        ]);
    }

    public function update(Request $request, Client $client, Project $project, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'max:999999.99'],
            'status' => ['required', 'string'],
            'payment_date' => ['required', 'date'],
        ]);

        $payment->update($validated);


        return redirect()->route('payments.show',
            ['client' => $client->slug, 'project' => $project->id, 'payment' => $payment->id]);
    }

    public function export(Request $request)
    {
        $search = $request->input('search');

        return Excel::download(new PaymentsExport($search), 'payments.xlsx');
    }
}
