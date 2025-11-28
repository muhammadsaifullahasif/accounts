<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\AuditReport;
use Illuminate\Http\Request;
use App\Models\CompanyAuditReport;

class CompanyAuditReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = Company::where('id', $id)->first();
        
        $auditReport = CompanyAuditReport::where('company_id', $id)->first();

        if(!$auditReport) {
            $auditReport = $this->create($id);
        }

        return view('company-audit-report.index', compact('company', 'auditReport'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = Company::where('id', $id)->first();

        $auditReport = AuditReport::where('type', 'ISA 800')->where('size', $company->size)->first();

        if ($auditReport) {

            $content = $auditReport->content;
            $content = str_replace(
                ['{company_name}', '{audit_year}'],
                [
                    $company->name,
                    Carbon::parse($company->end_date)->format('M d, Y'),
                ],
                $auditReport->content
            );
            $content = $this->cleanWordHtml($content);

            $companyAuditReport = new CompanyAuditReport();
            $companyAuditReport->user_id = auth()->id();
            $companyAuditReport->company_id = $id;
            $companyAuditReport->content = $content;
            $companyAuditReport->modified_by = auth()->id();
            $companyAuditReport->save();
        }

        return CompanyAuditReport::where('company_id', $id)->first();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::where('id', $id)->first();

        $auditReport = CompanyAuditReport::where('company_id', $id)->first();

        return view('company-audit-report.edit', compact('company', 'auditReport'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $company = Company::where('id', $id)->first();

        try {
            $auditReport = CompanyAuditReport::where('company_id', $id)->first();
            $auditReport->content = $request->content;
            $auditReport->modified_by = auth()->id();
            $auditReport->updated_at = now();
            $auditReport->save();

            return redirect()->route('company-audit-reports.index', $company->id)
                ->with('success', 'Audit Report updated successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding policies: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
