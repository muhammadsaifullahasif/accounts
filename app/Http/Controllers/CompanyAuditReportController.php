<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\AuditReport;
use Illuminate\Http\Request;
use App\Models\CompanyAuditReport;
use Illuminate\Support\Facades\Auth;

class CompanyAuditReportController extends Controller
{
    private function cleanWordHtml($html)
    {
        // 1️⃣ Decode any encoded HTML comments
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 2️⃣ Remove all HTML comments and conditional Word comments
        $html = preg_replace('/<!--[\s\S]*?-->/u', '', $html);
        $html = preg_replace('/<!--\[if.*?\]-->|<!--\[endif\]-->/is', '', $html);

        // 3️⃣ Remove Microsoft Office–specific inline CSS
        $html = preg_replace('/\s*mso-[^:]+:[^;"]+;?/i', '', $html);

        // 4️⃣ Remove unwanted inline styles
        $html = preg_replace('/text-indent:[^;"]*;?/i', '', $html);
        $html = preg_replace('/margin[^:]*:[^;"]*;?/i', '', $html);
        $html = preg_replace('/tab-stops:[^;"]*;?/i', '', $html);
        $html = preg_replace('/line-height:[^;"]*;?/i', '', $html);

        // 5️⃣ Remove all font and spacing styles
        $html = preg_replace('/font-size:[^;"]*;?/i', '', $html);
        $html = preg_replace('/letter-spacing:[^;"]*;?/i', '', $html);
        $html = preg_replace('/font:[^;"]*;?/i', '', $html);

        // 6️⃣ Remove Office-specific tags like <o:p>
        $html = preg_replace('/<\/?o:p[^>]*>/i', '', $html);

        // 7️⃣ Remove class="MsoNormal" and similar
        $html = preg_replace('/\s*class="Mso[^"]*"/i', '', $html);

        // 8️⃣ Remove empty class="" and style="" attributes
        $html = preg_replace('/\s?(class|style)="\s*"/i', '', $html);

        // 9️⃣ Remove empty <span> and <p> tags
        $html = preg_replace('/<span[^>]*>\s*<\/span>/i', '', $html);
        $html = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $html);

        // 🔟 Normalize whitespace
        $html = preg_replace('/\s{2,}/', ' ', $html);

        // 11️⃣ Trim final output
        return trim($html);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        if (Auth::user()->type != 'admin') {
            $company = Company::where('id', $id)->where('user_id', Auth::user()->id)->first();

            $auditReport = CompanyAuditReport::where('company_id', $id)->where('user_id', Auth::user()->id)->first();
        } else {
            $company = Company::where('id', $id)->first();

            $auditReport = CompanyAuditReport::where('company_id', $id)->first();
        }

        if(!$auditReport) {
            $auditReport = $this->create($id);
            // return $auditReport;
        }

        return view('company-audit-report.index', compact('company', 'auditReport'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        $company = Company::where('id', $id)->first();

        if (!$company) {
            return null;
        }

        $auditReport = AuditReport::where('type', $company->report_type)
            ->where('account_type', $company->account_type)
            ->first();

        if ($auditReport) {
            // return 'This';
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
