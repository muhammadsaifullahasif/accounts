<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\CompanyAccountingPolicy;

class CompanyAccountingPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $company = Company::where('id', $id)->first();

        $policies = CompanyAccountingPolicy::select('*')
            ->where('company_id', $id)
            ->orderByRaw("
                CASE policy_heading
                    WHEN 'COMPANY AND ITS OPERATIONS' THEN 1
                    WHEN 'BASIS OF PREPARATION' THEN 2
                    WHEN 'SUMMARY OF SIGNIFICANT ACCOUNTING POLICIES' THEN 3
                    ELSE 999
                END
            ")
            ->orderBy('company_id')
            ->orderBy('size')
            ->get()
            ->groupBy('policy_heading');

        return view('company-accounting-policies.index', compact('company', 'policies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = Company::where('id', $id)->first();

        return view('company-accounting-policies.new', compact('company'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'policy_heading' => 'required',
            'title' => 'required',
            'content' => 'required',
        ]);

        try {
            $company = Company::where('id', $id)->first();

            $companyPolicy = new CompanyAccountingPolicy();
            $companyPolicy->user_id = auth()->id();
            $companyPolicy->company_id = $id;
            $companyPolicy->size = $company->size;
            $companyPolicy->title = $request->title;
            $companyPolicy->content = $request->content;
            $companyPolicy->policy_heading = $request->policy_heading;
            $companyPolicy->modified_by = auth()->id();
            $companyPolicy->save();

            return redirect()->route('company-accounting-policy.index', $company->id)->with('success', 'Company Policy saved successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving accounting policy: ' . $e->getMessage()
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
        $company = Company::where('id', $id)->first();
        $policy = CompanyAccountingPolicy::where('id', $policy_id)->first();

        return view('company-accounting-policies.edit', compact('company', 'policy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'content' => 'required',
        ]);

        try {
            $company = Company::where('id', $id)->first();
            $policy = CompanyAccountingPolicy::where('id', $policy_id)->first();

            if (!$policy) {
                return redirect()->back()->with('error', 'Policy not found.');
            }

            $policy->content = $request->content;
            $policy->modified_by = auth()->id();
            $policy->updated_at = now();
            $policy->save();

            CompanyAccountingPolicy::where('company_id', $company->id)
                ->update(['modified_by' => auth()->id(), 'updated_at' => now()]);

            return redirect()->route('company-accounting-policy.index', $company->id)
                ->with('success', 'Company Policy updated successfully.');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating accounting policy: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $company = Company::where('id', $id)->first();
            $policy = CompanyAccountingPolicy::where('id', $policy_id)
                ->where('company_id', $company->id)
                ->first();

            if (!$policy) {
                return redirect()->back()->with('error', 'Policy not found.');
            }

            $policy->delete();
            return redirect()->route('company-accounting-policy.index', $company->id)
                ->with('success', 'Company Policy deleted successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error merging note: ' . $e->getMessage()
            ], 500);
        }
    }

    public function policies_add_bulk(string $id, Request $request)
    {
        $request->validate([
            'policy_ids' => 'required|array',
            'policy_ids.*' => 'required|integer',
            'policy_heading' => 'required|string',
        ]);

        try {
            $company = Company::where('id', $id)->first();

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found.'
                ], 404);
            }

            // Get all accounting policies by IDs
            $accountingPolicies = AccountingPolicy::whereIn('id', $request->policy_ids)->get();

            if ($accountingPolicies->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No policies found with the provided IDs.'
                ], 404);
            }

            // Loop through and create company policies
            foreach ($accountingPolicies as $policy) {

                // Replace placeholders in content with actual company data
                $content = $policy->content;
                $content = str_replace('{company_name}', $company->name, $content);

                $companyPolicy = new CompanyAccountingPolicy();
                $companyPolicy->user_id = auth()->id();
                $companyPolicy->company_id = $id;
                $companyPolicy->size = $company->size;
                $companyPolicy->title = $policy->title;
                $companyPolicy->content = $content;
                $companyPolicy->policy_heading = $policy->policy_heading;
                $companyPolicy->modified_by = auth()->id();
                $companyPolicy->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Policies added successfully.',
                'count' => $accountingPolicies->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding policies: ' . $e->getMessage()
            ], 500);
        }
    }
}
