<?php

use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\FixedAssetController;
use App\Http\Controllers\AuditReportController;
use App\Http\Controllers\TrailBalanceController;
use App\Http\Controllers\AccountingPolicyController;
use App\Http\Controllers\CompanyAuditReportController;
use App\Http\Controllers\CompanyAccountingPolicyController;

// Route::get('/', function () {
//     return view('home');
// })->name('dashboard');

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    // Auth Routes;
});

// Route::get('/password', function() {
//     return Hash::make('12345678');
// });

Route::middleware(['auth', AuthAdmin::class])->group(function () {
    // Admin Routes;
    Route::get('/', function () {
        return view('home');
    })->name('dashboard');
    Route::resource('/companies', CompanyController::class);

    Route::resource('/companies/{id}/trail-balance', TrailBalanceController::class);

    Route::resource('/companies/{id}/fixed-assets', FixedAssetController::class);

    // Custom note routes - MUST come before resource routes to avoid conflicts
    Route::get('/companies/{id}/notes/regenerate', [NoteController::class, 'notes_regenerate'])->name('notes.regenerate');
    Route::post('/companies/notes/save', [NoteController::class, 'notes_save'])->name('notes.save');
    Route::delete('/companies/notes/delete', [NoteController::class, 'notes_delete'])->name('notes.delete');
    Route::post('/companies/{id}/notes/accounts-merge', [NoteController::class, 'notes_accounts_merge'])->name('notes.accounts-merge');
    Route::post('/companies/notes/update_child_notes', [NoteController::class, 'child_notes_update'])->name('notes.update_child_notes');

    Route::resource('/companies/{id}/notes', NoteController::class);
    
    Route::get('/companies/{id}/statements/sopl', [StatementController::class, 'sopl'])->name('statements.sopl');
    Route::get('/companies/{id}/statements/soci', [StatementController::class, 'soci'])->name('statements.soci');
    Route::get('/companies/{id}/statements/soce', [StatementController::class, 'soce'])->name('statements.soce');
    Route::put('/companies/{id}/statements/soce', [StatementController::class, 'soce_update'])->name('statements.soce.update');
    Route::get('/companies/{id}/statements/sofp', [StatementController::class, 'sofp'])->name('statements.sofp');
    Route::get('/companies/{id}/statements/socf', [StatementController::class, 'socf'])->name('statements.socf');

    Route::resource('/audit-reports', AuditReportController::class);
    Route::resource('/companies/{id}/company-audit-reports', CompanyAuditReportController::class);
    Route::resource('/accounting-policy', AccountingPolicyController::class);
    Route::resource('/companies/{id}/company-accounting-policy', CompanyAccountingPolicyController::class);
    Route::post('/companies/{id}/company-accounting-policy/title', [CompanyAccountingPolicyController::class, 'policies_title'])->name('company-accounting-policy.title');
});
