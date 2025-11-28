<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountingPolicy;

class AccountingPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $policies = AccountingPolicy::select('*')
            ->orderByRaw("
                CASE policy_heading
                    WHEN 'COMPANY AND ITS OPERATIONS' THEN 1
                    WHEN 'BASIS OF PREPARATION' THEN 2
                    WHEN 'SUMMARY OF SIGNIFICANT ACCOUNTING POLICIES' THEN 3
                    ELSE 999
                END
            ")
            ->orderBy('size')
            ->orderBy('id')
            ->get()
            ->groupBy('policy_heading');
        return view('accounting-policies.index', compact('policies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('accounting-policies.new');
    }

    public function policy_index(Request $request)
    {
        $request->validate([
            'industry_id' => 'required',
            'policy_heading' => 'required'
        ]);

        // For edit mode, get the current policy details
        $currentPolicy = null;
        if ($request->action == 'edit' && $request->policy_id) {
            $currentPolicy = AccountingPolicy::where('id', $request->policy_id)->first();
        }

        // Check if editing and if industry or policy_heading changed
        if ($currentPolicy) {
            // If both industry and policy_heading are the same, keep original index
            if ($currentPolicy->industry_id == $request->industry_id && $currentPolicy->policy_heading === $request->policy_heading) {
                $newStartIndex = $currentPolicy->index;
            } else {
                // Industry or policy_heading changed - calculate new index
                $lastPolicy = AccountingPolicy::where('industry_id', $request->industry_id)
                    ->where('policy_heading', $request->policy_heading)
                    ->orderBy('index', 'desc')
                    ->first();

                if ($lastPolicy) {
                    $newStartIndex = round(floatval($lastPolicy->index) + 0.1, 1);
                } else {
                    if($request->policy_heading === 'COMPANY AND ITS OPERATIONS') {
                        $newStartIndex = 1.1;
                    } else if ($request->policy_heading === 'BASIS OF PREPARATION') {
                        $newStartIndex = 2.1;
                    } else if ($request->policy_heading === 'SUMMARY OF SIGNIFICANT ACCOUNTING POLICIES') {
                        $newStartIndex = 3.1;
                    }
                }
            }
        } else {
            // Create mode - calculate new index
            $lastPolicy = AccountingPolicy::where('industry_id', $request->industry_id)
                ->where('policy_heading', $request->policy_heading)
                ->orderBy('index', 'desc')
                ->first();

            if ($lastPolicy) {
                $newStartIndex = round(floatval($lastPolicy->index) + 0.1, 1);
            } else {
                if($request->policy_heading === 'COMPANY AND ITS OPERATIONS') {
                    $newStartIndex = 1.1;
                } else if($request->policy_heading === 'BASIS OF PREPARATION') {
                    $newStartIndex = 2.1;
                } else if($request->policy_heading === 'SUMMARY OF SIGNIFICANT ACCOUNTING POLICIES') {
                    $newStartIndex = 3.1;
                }
            }
        }

        return response()->json([
                'success' => true,
                'index' => $newStartIndex,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'policy_heading' => 'required',
            // 'index' => 'required',
            'title' => 'required'
        ]);

        try {
            $policy = new AccountingPolicy();
            $policy->user_id = auth()->user()->id;
            // $policy->index = $request->index;
            $policy->title = $request->title;
            $policy->content = $request->content;
            $policy->policy_heading = $request->policy_heading;
            $policy->modified_by = auth()->user()->id;
            $policy->save();

            return redirect()->back()->with('success', 'Accounting Policy saved successfully.');
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
        $policy = AccountingPolicy::find($id);
        return view('accounting-policies.edit', compact('policy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required'
        ]);

        try {
            $policy = AccountingPolicy::find($id);
            if (!$policy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accounting Policy not found.'
                ], 404);
            }

            $policy->content = $request->content;
            $policy->modified_by = auth()->user()->id;
            $policy->updated_at = now();
            $policy->save();

            return redirect()->back()->with('success', 'Accounting Policy updated successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving accounting policy: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $policy = AccountingPolicy::find($id);
            if (!$policy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accounting Policy not found.'
                ], 404);
            }

            $policy->delete();
            return redirect()->back()->with('success', 'Accounting Policy deleted successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving accounting policy: ' . $e->getMessage()
            ], 500);
        }
    }
}
