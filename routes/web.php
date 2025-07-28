<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware(['auth'])->group(function () {
    // Home
    Route::get('/pulpit', [DashboardController::class, 'index'])->name('dashboard');

    // Clients
    Route::get('/klienci', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/klienci', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/klienci/dodaj', [ClientController::class, 'create'])->name('clients.create');
    Route::get('/klienci/eksport', [ClientController::class, 'export'])->name('clients.export');
    Route::get('/klienci/dodaj/{lead?}', [ClientController::class, 'create'])->name('clients.create');
    Route::get('/klienci/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::put('/klienci/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::put('/klienci/{client}/tags', [ClientController::class, 'updateTags'])->name('clients.tags.update');


    //  Projects
    Route::prefix('/klienci/{client}')->group(function () {
        Route::get('/projekty/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::put('/projekty/{project}', [ProjectController::class, 'update'])->name('projects.update');

        Route::prefix('/projekty/{project}')->group(function () {
            Route::get('/platnosci/{payment}', [PaymentController::class, 'show'])->name('payments.show');
            Route::put('/platnosci/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        });

    });
    Route::get('/projekty', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projekty/moje', [ProjectController::class, 'assigned'])->name('projects.assigned');
    Route::get('/projekty/dodaj/{client?}', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projekty', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projekty/eksport', [ProjectController::class, 'export'])->name('projects.export');
    Route::get('/projekty/moje/eksport', [ProjectController::class, 'exportMyProjects'])->name('projects.mine.export');
    Route::put('/projekty/{project}', [ProjectController::class, 'updateTags'])->name('projects.tags.update');


    //  Payments
    Route::get('/platnosci', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/platnosci', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/platnosci/eksport', [PaymentController::class, 'export'])->name('payments.export');
    Route::get('/platnosci/dodaj/{project?}', [PaymentController::class, 'create'])->name('payments.create');


    // Expenses
    Route::get('/koszty', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/koszty/dodaj', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::get('/koszty/eksport', [ExpenseController::class, 'export'])->name('expenses.export');
    Route::get('/koszty/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::put('/koszty/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::post('/koszty', [ExpenseController::class, 'store'])->name('expenses.store');


    // Finances
    Route::get('/finanse', [FinanceController::class, 'index'])->name('finances.index');

    // Leads
    Route::get('/leady', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/leady/dodaj', [LeadController::class, 'create'])->name('leads.create');
    Route::put('/leady/{lead}', [LeadController::class, 'update'])->name('leads.update');
    Route::post('/leady', [LeadController::class, 'store'])->name('leads.store');
    Route::get('/leady/eksport', [LeadController::class, 'export'])->name('leads.export');


    // Configuration
    Route::get('/konfiguracja', [ConfigurationController::class, 'index'])->name('configurations.index');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
