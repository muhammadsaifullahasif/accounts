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
                        'user_id' => '1',
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
                        'modified_by' => '1'
                    ]
                );
                TrailBalance::updateOrCreate(
                    [
                        'company_id' => $company->id,
                        'account_code' => $entry['accountCode'],
                        'account_head' => $entry['accountHead'],
                        'group_code' => 'NCA-001',
                        'group_name' => "Property, Plant and Equipment's"
                    ],
                    [
                        'user_id' => '1',
                        'opening_debit' => $entry['opening'],
                        'movement_debit' => $entry['addition'],
                        'movement_credit' => $entry['deletion'],
                        'modified_by' => '1'
                    ]
                );
                TrailBalance::updateOrCreate(
                    [
                        'company_id' => $company->id,
                        'account_code' => $entry['depreciationAccountCode'],
                        'account_head' => $entry['depreciationAccountHead'],
                        'group_code' => 'NCA-001',
                        'group_name' => "Property, Plant and Equipment's"
                    ],
                    [
                        'user_id' => '1',
                        'opening_debit' => $entry['depreciationOpening'],
                        'movement_debit' => $entry['depreciationDeletion'],
                        'movement_credit' => $entry['depreciationAddition'],
                        'closing_credit' => $entry['depreciationClosing'],
                        'modified_by' => '1'
                    ]
                );
                // $fixedAsset = new FixedAssetsSchedual();
                // $fixedAsset->user_id = 1;
                // $fixedAsset->company_id = $company->id;
                // $fixedAsset->account_code = $entry['accountCode'];
                // $fixedAsset->account_head = $entry['accountHead'];
                // $fixedAsset->opening = $entry['opening'];
                // $fixedAsset->addition = $entry['addition'];
                // $fixedAsset->addition_no_of_days = $entry['additionNoOfDaysValue'];
                // $fixedAsset->deletion = $entry['deletion'];
                // $fixedAsset->deletion_no_of_days = $entry['deletionNoOfDaysValue'];
                // $fixedAsset->closing = $entry['closing'];
                // $fixedAsset->rate = $entry['rate'];
                // $fixedAsset->depreciation_account_code = $entry['depreciationAccountCode'];
                // $fixedAsset->depreciation_account_head = $entry['depreciationAccountHead'];
                // $fixedAsset->depreciation_opening = $entry['depreciationOpening'];
                // $fixedAsset->depreciation_addition = $entry['depreciationAddition'];
                // $fixedAsset->depreciation_deletion = $entry['depreciationDeletion'];
                // $fixedAsset->depreciation_closing = $entry['depreciationClosing'];
                // $fixedAsset->wdv = $entry['wdv'];
                // $fixedAsset->modified_by = 1;
                // $fixedAsset->save();
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
