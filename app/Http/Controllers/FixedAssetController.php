<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\TrailBalance;
use Illuminate\Http\Request;
use App\Models\FixedAssetsSchedual;

class FixedAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $company = Company::findOrFail($id);

        $fixedAssets = FixedAssetsSchedual::where('company_id', $company->id)->get();
        return view('fixed-assets.index', compact('company', 'fixedAssets'));
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
            'entries.*opening' => 'required',
            'entries.*.addition' => 'required',
            'entries.*.deletion' => 'required',
            'entries.*.closing' => 'required',
            'entries.*.rate' => 'required',
            'entries.*.depreciationAccountCode' => 'required',
            'entries.*.depreciationAccountHead' => 'required',
            'entries.*depreciationOpening' => 'required',
            'entries.*.depreciationAddition' => 'required',
            'entries.*.depreciationDeletion' => 'required',
            'entries.*.depreciationClosing' => 'required',
            'entries.*.wdv' => 'required'
        ]);

        try {
            $company = Company::findOrFail($id);

            // Get all account codes from the request
            $requestAccountCodes = array_column($request->entries, 'accountCode');

            // Get existing fixed assets for this company
            $existingFixedAssets = FixedAssetsSchedual::where('company_id', $company->id)->get();

            // Delete records that exist in database but not in request
            foreach ($existingFixedAssets as $existingAsset) {
                if (!in_array($existingAsset->account_code, $requestAccountCodes)) {
                    $existingAsset->delete();
                }
            }

            // Collect all account codes (asset + depreciation)
            $requestTBAccounts = [];
            foreach ($request->entries as $entry) {
                $requestTBAccounts[] = $entry['accountCode'];
                $requestTBAccounts[] = $entry['depreciationAccountCode'];
            }

            // Get all existing TB records for this company in NCA-001
            $existingTBRecords = TrailBalance::where('company_id', $company->id)
                ->where('group_code', 'NCA-001')
                ->get();

            // Delete TB records not found in request
            foreach ($existingTBRecords as $tb) {
                if (!in_array($tb->account_code, $requestTBAccounts)) {
                    $tb->delete();
                }
            }

            // Create or update entries
            foreach ($request->entries as $entry) {
                FixedAssetsSchedual::updateOrCreate(
                    [
                        'company_id' => $company->id,
                        'account_code' => $entry['accountCode'],
                        'account_head' => $entry['accountHead'],
                        'depreciation_account_code' => $entry['depreciationAccountCode'],
                        'depreciation_account_head' => $entry['depreciationAccountHead']
                    ],
                    [
                        'user_id' => auth()->id(),
                        'opening' => $entry['opening'],
                        'addition' => $entry['addition'],
                        'addition_no_of_days' => $entry['additionNoOfDaysValue'],
                        'deletion' => $entry['deletion'],
                        'deletion_no_of_days' => $entry['deletionNoOfDaysValue'],
                        'closing' => $entry['closing'],
                        'rate' => $entry['rate'],
                        'depreciation_opening' => $entry['depreciationOpening'],
                        'depreciation_addition' => $entry['depreciationAddition'],
                        'depreciation_deletion' => $entry['depreciationDeletion'],
                        'depreciation_closing' => $entry['depreciationClosing'],
                        'wdv' => $entry['wdv'],
                        'modified_by' => auth()->id()
                    ]
                );
                $additionNetBalance = $entry['opening'] + $entry['addition'] - 0 - $entry['deletion'];
                if ($additionNetBalance > 0) {
                    $additionClosingDebit = $additionNetBalance;
                    $additionClosingCredit = 0;
                } else if ($additionNetBalance < 0) {
                    $additionClosingDebit = 0;
                    $additionClosingCredit = $additionNetBalance;
                } else {
                    $additionClosingDebit = 0;
                    $additionClosingCredit = 0;
                }
                TrailBalance::updateOrCreate(
                    [
                        'company_id' => $company->id,
                        'account_code' => $entry['accountCode'],
                        'account_head' => $entry['accountHead'],
                        'group_code' => 'NCA-001',
                        'group_name' => "Property, Plant and Equipment's"
                    ],
                    [
                        'user_id' => auth()->id(),
                        'opening_debit' => $entry['opening'],
                        'movement_debit' => $entry['addition'],
                        'movement_credit' => $entry['deletion'],
                        'closing_debit' => $additionClosingDebit,
                        'closing_credit' => $additionClosingCredit,
                        'modified_by' => auth()->id()
                    ]
                );
                $depreciationNetBalance = 0 + $entry['depreciationDeletion'] - $entry['depreciationOpening'] - $entry['depreciationAddition'];
                if ($depreciationNetBalance > 0) {
                    $depreciationClosingDebit = $depreciationNetBalance;
                    $depreciationClosingCredit = 0;
                } else if ($depreciationNetBalance < 0) {
                    $depreciationClosingDebit = 0;
                    $depreciationClosingCredit = abs($depreciationNetBalance);
                } else {
                    $depreciationClosingDebit = 0;
                    $depreciationClosingCredit = 0;
                }
                TrailBalance::updateOrCreate(
                    [
                        'company_id' => $company->id,
                        'account_code' => $entry['depreciationAccountCode'],
                        'account_head' => $entry['depreciationAccountHead'],
                        'group_code' => 'NCA-001',
                        'group_name' => "Property, Plant and Equipment's"
                    ],
                    [
                        'user_id' => auth()->id(),
                        'opening_credit' => $entry['depreciationOpening'],
                        'movement_debit' => $entry['depreciationDeletion'],
                        'movement_credit' => $entry['depreciationAddition'],
                        'closing_debit' => $depreciationClosingDebit,
                        'closing_credit' => $depreciationClosingCredit,
                        // 'closing_credit' => $entry['depreciationClosing'],
                        'modified_by' => auth()->id()
                    ]
                );
            }

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Fixed assets schedule saved successfully.'
            // ]);
            return redirect()->route('trail-balance.index', $id)->with('success', 'Fixed assets schedule saved successfully.');
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
