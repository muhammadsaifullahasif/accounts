<?php

namespace App\Http\Controllers;

use App\Models\AuditReport;
use Illuminate\Http\Request;

class AuditReportController extends Controller
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
    public function index()
    {
        $auditReports = AuditReport::orderBy('id', 'DESC')->get();
        return view('audit-report.index', compact('auditReports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('audit-report.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'size' => 'required',
            'content' => 'required',
        ]);

        try {
            $auditReport = new AuditReport();
            $auditReport->user_id = auth()->user()->id;
            $auditReport->type = $request->type;
            $auditReport->size = $request->size;
            $auditReport->content = $this->cleanWordHtml($request->content);
            $auditReport->modified_by = auth()->user()->id;
            $auditReport->save();

            return redirect()->route('audit-reports.index')->with('success', 'Audit Report created successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error merging note: ' . $e->getMessage()
            ], 500);
        }
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
        $auditReport = AuditReport::find($id);

        return view('audit-report.edit', compact('auditReport'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'type' => 'required',
            'size' => 'required',
            'content' => 'required',
        ]);

        try {
            $auditReport = AuditReport::find($id);
            $auditReport->type = $request->type;
            $auditReport->size = $request->size;
            $auditReport->content = $this->cleanWordHtml($request->content);
            $auditReport->save();

            return redirect()->route('audit-reports.index')->with('success', 'Audit Report updated successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error merging note: ' . $e->getMessage()
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
