<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Home
    Route::get('/pulpit', [DashboardController::class, 'index'])->name('dashboard.index');

   // Clients
   Route::get('/klienci', [ClientController::class, 'index'])->name('clients.index');
   Route::get('/klienci/dodaj', [ClientController::class, 'create'])->name('clients.create');
   Route::get('/klienci/{client}', [ClientController::class, 'show'])->name('clients.show');
   Route::get('/klienci/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
   Route::put('/klienci/{client}', [ClientController::class, 'update'])->name('clients.update');
   Route::post('/klienci', [ClientController::class, 'store'])->name('clients.store');

   //  Projects
    Route::prefix('/klienci/{client}')->group(function () {
        Route::get('/projekty/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/projekty/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    });
   Route::get('/projekty', [ProjectController::class, 'index'])->name('projects.index');
   Route::get('/projekty/dodaj', [ProjectController::class, 'create'])->name('projects.create');


   Route::prefix('/projekty/{project}')->group(function () {
       Route::get('/platnosci/{payment}', [PaymentController::class, 'show'])->name('payments.show');
       Route::get('/platnosci/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
   });


   //  Payments
     Route::get('/platnosci', [PaymentController::class, 'index'])->name('payments.index');


    // Expenses
     Route::get('/koszty', [ExpenseController::class, 'index'])->name('expenses.index');
     Route::get('/koszty/dodaj', [ExpenseController::class, 'create'])->name('expenses.create');
     Route::put('/koszty/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
     Route::post('/koszty', [ExpenseController::class, 'store'])->name('expenses.store');

     // Leads
    Route::get('/leady', [LeadController::class, 'index'])->name('leads.index');
    Route:: get('/leady/dodaj', [LeadController::class, 'create'])->name('leads.create');
    Route::put('/leady/{lead}', [LeadController::class, 'update'])->name('leads.update');
    Route::post('/leady',  [LeadController::class, 'store'])->name('leads.store');
    Route::get('/leady/eksport', [LeadController::class, 'export'])->name('leads.export');

    // Configuration
    Route::get('/konfiguracja', [ConfigurationController::class, 'index'])->name('configurations.index');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
