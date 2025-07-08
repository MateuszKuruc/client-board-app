<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $payments = Payment::with('project', 'project.client')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('project', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('project.client', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
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
}
