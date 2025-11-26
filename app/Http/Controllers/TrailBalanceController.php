<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\TrailBalance;
use Illuminate\Http\Request;

class TrailBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $company = Company::where('id', $id)->first();
        $PPEtrailBalances = TrailBalance::where('company_id', $id)
            ->where('group_code', 'NCA-001')
            ->select('group_code', 'group_name', 'account_code', 'account_head', 'opening_debit', 'opening_credit', 'movement_debit', 'movement_credit', 'closing_debit', 'closing_credit')
            ->orderBy('group_code')
            ->get()
            ->groupBy('group_code');

        $trailBalances = TrailBalance::where('company_id', $id)
            ->where('group_code', '!=', 'NCA-001')
            ->select('group_code', 'group_name', 'account_code', 'account_head', 'opening_debit', 'opening_credit', 'movement_debit', 'movement_credit', 'closing_debit', 'closing_credit')
            ->orderBy('group_code')
            ->orderBy('account_code')
            ->get()
            ->groupBy('group_code')
            ->map(function ($groupItems) {
                return $groupItems->groupBy('account_code')->map(function ($accounts) {
                    return $accounts->first(); // Get first (and only) element from array
                });
            });

        /*
        $allOtherTrailBalances = TrailBalance::where('company_id', $id)
            ->where('group_code', '!=', 'NCA-001')
            ->select('group_code', 'group_name', 'account_code', 'account_head', 'opening_debit', 'opening_credit', 'movement_debit', 'movement_credit', 'closing_debit', 'closing_credit')
            ->orderBy('group_code')
            ->get()
            ->groupBy('group_code');

        $masterRows = TrailBalance::where('company_id', $id)
            ->select('account_code', 'account_head', 'group_code', 'group_name')
            ->distinct()
            ->where('group_code', '!=', 'NCA-001')
            ->get();

        $trailBalances = $masterRows->map(function ($row) use ($allOtherTrailBalances) {
            $existing = $allOtherTrailBalances->firstWhere('account_code', $row->account_code);

            return [
                'account_code' => $row->account_code,
                'account_head' => $row->account_head,
                'group_code' => $row->group_code,
                'group_name' => $row->group_name,

                'opening_debit' => $existing->opening_debit ?? 0,
                'opening_credit' => $existing->opening_credit ?? 0,
                'movement_debit' => $existing->movement_debit ?? 0,
                'movement_credit' => $existing->movement_credit ?? 0,
                'closing_debit' => $existing->closing_debit ?? 0,
                'closing_credit' => $existing->closing_credit ?? 0,
            ];
        });
        */

        // return $PPEtrailBalances;
        // return $trailBalances;
        return view('trail-balance.index', compact('company', 'PPEtrailBalances', 'trailBalances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'entries' => 'required|array',
            'entries.*.accountCode' => 'required',
            'entries.*.accountHead' => 'required',
            'entries.*.groupCode' => 'required',
            'entries.*.groupName' => 'required',
            'entries.*.openingDebit' => 'required',
            'entries.*.openingCredit' => 'required',
            'entries.*.movementDebit' => 'required',
            'entries.*.movementCredit' => 'required',
            'entries.*.closingDebit' => 'required',
            'entries.*.closingCredit' => 'required',
        ]);

        try {
            $company = Company::findOrFail($id);

            // Get all account codes from request
            $requestAccountCodes = collect($request->entries)->pluck('accountCode')->toArray();

            // Delete entries that are not in the request (excluding NCA-001 group)
            TrailBalance::where('company_id', $company->id)
                ->whereNotIn('account_code', $requestAccountCodes)
                ->delete();

            // Create or update entries
            foreach ($request->entries as $entry) {
                TrailBalance::updateOrCreate(
                    [
                        'company_id' => $company->id,
                        'account_code' => $entry['accountCode'],
                        'account_head' => $entry['accountHead'],
                        'group_code' => $entry['groupCode'],
                        'group_name' => $entry['groupName']
                    ],
                    [
                        'user_id' => '1',
                        'opening_debit' => $entry['openingDebit'],
                        'opening_credit' => $entry['openingCredit'],
                        'movement_debit' => $entry['movementDebit'],
                        'movement_credit' => $entry['movementCredit'],
                        'closing_debit' => $entry['closingDebit'],
                        'closing_credit' => $entry['closingCredit'],
                        'modified_by' => '1'
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Fixed assets schedule saved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving fixed assets schedule: ' . $e->getMessage()
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
