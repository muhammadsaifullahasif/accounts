<?php

use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
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
    Route::get('/', function () {
        return view('home');
    })->name('dashboard');
    Route::resource('/companies', CompanyController::class);
    Route::resource('/companies/{id}/fixed-assets', FixedAssetController::class);
    Route::resource('/companies/{id}/trail-balance', TrailBalanceController::class);
    
    // Custom note routes - MUST come before resource routes to avoid conflicts
    Route::get('/companies/{id}/notes/regenerate', [NoteController::class, 'notes_regenerate'])->name('notes.regenerate');
    Route::post('/companies/{id}/notes/save', [NoteController::class, 'notes_save'])->name('notes.save');
    Route::delete('/companies/notes/delete', [NoteController::class, 'notes_delete'])->name('notes.delete');
    Route::post('/companies/{id}/notes/accounts-merge', [NoteController::class, 'notes_accounts_merge'])->name('notes.accounts-merge');
    Route::post('/companies/notes/update_child_notes', [NoteController::class, 'child_notes_update'])->name('notes.update_child_notes');
    Route::resource('/companies/{id}/notes', NoteController::class);
    
    Route::get('/companies/{id}/statements/sopl', [StatementController::class, 'sopl'])->name('statements.sopl');
    Route::get('/companies/{id}/statements/sopl/export/pdf', [StatementController::class, 'sopl_export_pdf'])->name('statements.sopl.export.pdf');
    Route::get('/companies/{id}/statements/sopl/export/excel', [StatementController::class, 'sopl_export_excel'])->name('statements.sopl.export.excel');
    Route::get('/companies/{id}/statements/soci', [StatementController::class, 'soci'])->name('statements.soci');
    Route::get('/companies/{id}/statements/soci/export/pdf', [StatementController::class, 'soci_export_pdf'])->name('statements.soci.export.pdf');
    Route::get('/companies/{id}/statements/soci/export/excel', [StatementController::class, 'soci_export_excel'])->name('statements.soci.export.excel');
    Route::get('/companies/{id}/statements/soce', [StatementController::class, 'soce'])->name('statements.soce');
    Route::put('/companies/{id}/statements/soce', [StatementController::class, 'soce_update'])->name('statements.soce.update');
    Route::get('/companies/{id}/statements/soce/export/pdf', [StatementController::class, 'soce_export_pdf'])->name('statements.soce.export.pdf');
    Route::get('/companies/{id}/statements/soce/export/excel', [StatementController::class, 'soce_export_excel'])->name('statements.soce.export.excel');
    Route::get('/companies/{id}/statements/sofp', [StatementController::class, 'sofp'])->name('statements.sofp');
    Route::get('/companies/{id}/statements/sofp/export/pdf', [StatementController::class, 'sofp_export_pdf'])->name('statements.sofp.export.pdf');
    Route::get('/companies/{id}/statements/sofp/export/excel', [StatementController::class, 'sofp_export_excel'])->name('statements.sofp.export.excel');
    Route::get('/companies/{id}/statements/socf', [StatementController::class, 'socf'])->name('statements.socf');
    Route::get('/companies/{id}/statements/socf/export/pdf', [StatementController::class, 'socf_export_pdf'])->name('statements.socf.export.pdf');
    Route::get('/companies/{id}/statements/socf/export/excel', [StatementController::class, 'socf_export_excel'])->name('statements.socf.export.excel');
    
    Route::get('/companies/{id}/company-audit-reports/export/pdf', [CompanyAuditReportController::class, 'export_pdf'])->name('company-audit-reports.export.pdf');
    Route::resource('/companies/{id}/company-audit-reports', CompanyAuditReportController::class);
    
    Route::get('/companies/{id}/company-accounting-policy/import', [CompanyAccountingPolicyController::class, 'import'])->name('company-accounting-policy.import');
    Route::post('/companies/{id}/company-accounting-policy/add-bulk', [CompanyAccountingPolicyController::class, 'policies_add_bulk'])->name('company-accounting-policy.add-bulk');
    Route::get('/companies/{id}/company-accounting-policy/export/pdf', [CompanyAccountingPolicyController::class, 'export_pdf'])->name('company-accounting-policy.export.pdf');
    Route::resource('/companies/{id}/company-accounting-policy', CompanyAccountingPolicyController::class);
    Route::post('/companies/{id}/company-accounting-policy/title', [CompanyAccountingPolicyController::class, 'policies_title'])->name('company-accounting-policy.title');
});

Route::middleware(['auth', AuthAdmin::class])->group(function () {
    // Admin Routes;

    Route::resource('/audit-reports', AuditReportController::class);
    Route::resource('/accounting-policy', AccountingPolicyController::class);

    Route::resource('/users', UserController::class);
});
