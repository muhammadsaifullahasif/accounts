<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Company;
use App\Models\TrailBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        if (Auth::user()->type != 'admin') {
            $company = Company::where('user_id', Auth::user()->id)->findOrFail($id);
        } else {
            $company = Company::findOrFail($id);
        }

        $notes_exists = Note::where('company_id', $id)->whereNull('parent_index')->get();

        if ($notes_exists->count() > 0) {
            $notes = Note::where('company_id', $id)
                ->select('id', 'company_id', 'index', 'group_code', 'group_name', 'account_code', 'account_head', 'current_year', 'previous_year', 'parent_index', 'merge_id')
                // ->orderBy('group_code')
                ->orderBy('index')
                ->get()
                ->groupBy('index')
                ->sortKeys();
            
            // return $notes;
            return view('notes.index', compact('company', 'notes'));
        }

        $notes = $this->notes_create($id);
        
        return $notes;
        return view('notes.index', compact('company', 'notes'));
        
        // return view('notes.index', compact('company'));
        //
    }

    public function notes_create(string $id)
    {
        $company = Company::where('id', $id)->first();

        $transactions = TrailBalance::where('company_id', $id)
            ->select('group_code', 'group_name', 'account_code', 'account_head', 'opening_debit', 'opening_credit', 'movement_debit', 'movement_credit', 'closing_debit', 'closing_credit')
            ->whereNotLike('group_code', 'NCA%')
            // ->orderBy('group_code')
            ->get()
            ->groupBy('group_code');

        // Check if any group has "Property" in the group_name
        // $hasProperty = TrailBalance::where('company_id', $id)
        //     ->where('group_name', 'like', '%Property%')
        //     ->exists();

        // Separate OI-001 and T-001 groups to process them at the end
        $otherIncomeGroup = $transactions->pull('OI-001');
        $taxationGroup = $transactions->pull('T-001');

        // Add OI-001 second to last if it exists
        if ($otherIncomeGroup) {
            $transactions->put('OI-001', $otherIncomeGroup);
        }

        // Add T-001 last if it exists
        if ($taxationGroup) {
            $transactions->put('T-001', $taxationGroup);
        }

        $assets = ['NCA-001', 'CA-001', 'CA-002', 'CA-003'];
        $equity = ['EQ-001'];
        $liability = ['NCL-001', 'CL-001', 'CL-002'];
        $income = ['S-001', 'OI-001'];
        $expense = ['COS-001', 'EX-001', 'FC-001', 'T-001'];

        $index = 5;

        foreach ($transactions as $groupName => $accounts) {
            $noteSaved = false; // Add this flag at the start of each group iteration

            // Special handling for COS-001 group
            if ($groupName == 'COS-001') {
                // Track if we've processed the PR accounts
                $prAccountsProcessed = false;
                $pr001Data = null;
                $pr002Data = null;
                $pr003Data = null;

                foreach ($accounts as $account) {
                    // Collect PR-001, PR-002, PR-003 data
                    if ($account->account_code == 'PR-001') {
                        $pr001Data = $account;
                    } elseif ($account->account_code == 'PR-002') {
                        $pr002Data = $account;
                    } elseif ($account->account_code == 'PR-003') {
                        $pr003Data = $account;
                    }
                }

                // Process all accounts in COS-001
                foreach ($accounts as $account) {
                    // Skip PR-002 and PR-003 as they'll be combined with PR-001
                    if (in_array($account->account_code, ['PR-002', 'PR-003'])) {
                        continue;
                    }

                    // For PR-001, combine with PR-002 and subtract PR-003
                    if ($account->account_code == 'PR-001' && $pr001Data) {
                        $opening_debit = ($pr001Data->opening_debit ?? 0) + ($pr002Data->opening_debit ?? 0) - ($pr003Data->opening_debit ?? 0);
                        $opening_credit = ($pr001Data->opening_credit ?? 0) + ($pr002Data->opening_credit ?? 0) - ($pr003Data->opening_credit ?? 0);
                        $movement_debit = ($pr001Data->movement_debit ?? 0) + ($pr002Data->movement_debit ?? 0) - ($pr003Data->movement_debit ?? 0);
                        $movement_credit = ($pr001Data->movement_credit ?? 0) + ($pr002Data->movement_credit ?? 0) - ($pr003Data->movement_credit ?? 0);
                        $closing_debit = ($pr001Data->closing_debit ?? 0) + ($pr002Data->closing_debit ?? 0) - ($pr003Data->closing_debit ?? 0);
                        $closing_credit = ($pr001Data->closing_credit ?? 0) + ($pr002Data->closing_credit ?? 0) - ($pr003Data->closing_credit ?? 0);

                        // Determine current_year and previous_year from combined values
                        $current_year = 0;
                        $previous_year = 0;

                        if ($closing_debit > 0) {
                            $current_year = -$closing_debit;
                        }
                        if ($closing_credit > 0) {
                            $current_year = $closing_credit;
                        }
                        if ($opening_debit > 0) {
                            $previous_year = -$opening_debit;
                        }
                        if ($opening_credit > 0) {
                            $previous_year = $opening_credit;
                        }

                        if ($current_year == 0 && $previous_year == 0) {
                            continue;
                        }

                        $note = new Note();
                        $note->user_id = 1;
                        $note->company_id = $id;
                        $note->index = $index;
                        $note->group_code = 'COS-001';
                        $note->group_name = $account->group_name;
                        $note->account_code = 'PR-001';
                        $note->account_head = 'Purchases';
                        $note->current_year = round($current_year);
                        $note->previous_year = round($previous_year);
                        $note->modified_by = 1;
                        $note->save();
                        $noteSaved = true; // Mark that a note was saved
                    } else {
                        // Process other COS-001 accounts normally
                        $current_year = 0;
                        $previous_year = 0;

                        if ($account->closing_debit > 0) {
                            $current_year = -$account->closing_debit;
                        }
                        if ($account->closing_credit > 0) {
                            $current_year = $account->closing_credit;
                        }
                        if ($account->opening_debit > 0) {
                            $previous_year = -$account->opening_debit;
                        }
                        if ($account->opening_credit > 0) {
                            $previous_year = $account->opening_credit;
                        }

                        if ($current_year == 0 && $previous_year == 0) {
                            continue;
                        }

                        $note = new Note();
                        $note->user_id = 1;
                        $note->company_id = $id;
                        $note->index = $index;
                        $note->group_code = $account->group_code;
                        $note->group_name = $account->group_name;
                        $note->account_code = $account->account_code;
                        $note->account_head = $account->account_head;
                        $note->current_year = round($current_year);
                        $note->previous_year = round($previous_year);
                        $note->modified_by = 1;
                        $note->save();
                        $noteSaved = true; // Mark that a note was saved
                    }
                }
            } else {
                // Process all other groups normally
                foreach ($accounts as $account) {
                    $current_year = 0;
                    $previous_year = 0;

                    if ($account->closing_debit > 0) {
                        if (in_array($account->group_code, $equity) || in_array($account->group_code, $liability) || in_array($account->group_code, $income)) {
                            $current_year = -$account->closing_debit;
                        } else {
                            $current_year = $account->closing_debit;
                        }
                    }
                    if ($account->closing_credit > 0) {
                        $current_year = $account->closing_credit;
                    }
                    if ($account->opening_debit > 0) {
                        if (in_array($account->group_code, $equity) || in_array($account->group_code, $liability) || in_array($account->group_code, $income)) {
                            $previous_year = -$account->opening_debit;
                        } else {
                            $previous_year = $account->opening_debit;
                        }
                    }
                    if ($account->opening_credit > 0) {
                        $previous_year = $account->opening_credit;
                    }

                    if ($current_year == 0 && $previous_year == 0) {
                        continue;
                    }

                    $note = new Note();
                    $note->user_id = 1;
                    $note->company_id = $id;
                    $note->index = $index;
                    $note->group_code = $account->group_code;
                    $note->group_name = $account->group_name;
                    $note->account_code = $account->account_code;
                    $note->account_head = $account->account_head;
                    $note->current_year = round($current_year);
                    $note->previous_year = round($previous_year);
                    $note->modified_by = 1;
                    $note->save();
                    $noteSaved = true; // Mark that a note was saved
                }
            }

            // Only increment index if at least one note was saved for this group
            if ($noteSaved) {
                $index++;
            }
        }

        return Note::where('company_id', $id)
            ->select('id', 'company_id', 'index', 'group_code', 'group_name', 'account_code', 'account_head', 'current_year', 'previous_year', 'parent_index', 'merge_id')
            // ->orderBy('group_code')
            ->orderBy('index')
            ->get()
            ->groupBy('index')
            ->sortKeys();
    }

    public function getNonCurrentAssets(string $id)
    {
        // Get all non-current assets from TrailBalance (group_code starting with 'NCA')
        $nonCurrentAssets = TrailBalance::where('company_id', $id)
            ->where('group_code', 'like', 'NCA%')
            ->select('group_code', 'group_name', 'account_code', 'account_head', 'opening_debit', 'opening_credit', 'movement_debit', 'movement_credit', 'closing_debit', 'closing_credit')
            ->get();

        $index = 4;
        $assets = [];
        $totalCurrentYear = 0;
        $totalPreviousYear = 0;

        foreach ($nonCurrentAssets as $asset) {
            // Calculate current_year from closing values
            $current_year = 0;
            if ($asset->closing_debit > 0) {
                $current_year = $asset->closing_debit;
            }
            if ($asset->closing_credit > 0) {
                $current_year = $asset->closing_credit;
            }

            // Calculate previous_year from opening values
            $previous_year = 0;
            if ($asset->opening_debit > 0) {
                $previous_year = $asset->opening_debit;
            }
            if ($asset->opening_credit > 0) {
                $previous_year = $asset->opening_credit;
            }

            // Skip entries where both current_year and previous_year are 0
            if ($current_year == 0 && $previous_year == 0) {
                continue;
            }

            // Add to totals
            $totalCurrentYear += $current_year;
            $totalPreviousYear += $previous_year;

            $assets[] = [
                'index' => $index,
                'group_code' => $asset->group_code,
                'group_name' => $asset->group_name,
                'account_code' => $asset->account_code,
                'account_head' => $asset->account_head,
                'current_year' => $current_year,
                'previous_year' => $previous_year
            ];
        }

        // Add totals as the last item in the array
        $assets[] = [
            'index' => $index,
            'group_code' => null,
            'group_name' => 'Total',
            'account_code' => null,
            'account_head' => 'Total Non-Current Assets',
            'current_year' => $totalCurrentYear,
            'previous_year' => $totalPreviousYear
        ];

        return $assets;
    }

    public function notes_update(string $id)
    {
        $company = Company::where('id', $id)->first();
        $transactions = TrailBalance::where('company_id', $id)
            ->select('group_code', 'group_name', 'account_code', 'account_head', 'opening_debit', 'opening_credit', 'closing_debit', 'closing_credit')
            ->orderBy('group_code')
            ->get()
            ->groupBy('group_name');

        $index = 4;
        $i = 1;

        foreach ($transactions as $groupName => $accounts) {
            if (stripos($groupName, 'Property') !== false && $i == 1) {
                $index = 5;
            }
            foreach ($accounts as $account) {
                $current_year = 0;
                $previous_year = 0;
                if ($account->closing_debit > 0) {
                    $current_year = $account->closing_debit;
                }

                if ($account->closing_credit > 0) {
                    $current_year = $account->closing_credit;
                }

                if ($account->opening_debit > 0) {
                    $previous_year = $account->opening_debit;
                }
                if ($account->opening_credit > 0) {
                    $previous_year = $account->opening_credit;
                }

                $note = Note::where('company_id', $id)
                    ->where('index', $index)
                    ->where('group_code', $account['group_code'])
                    ->where('group_name', $account['group_name'])
                    ->where('account_code', $account['account_code'])
                    ->where('account_head', $account['account_head'])
                    ->update(['current_year' => $current_year, 'previous_year' => $previous_year]);

                $note = Note::where('company_id', $id)
                    ->update(['modified_by' => 1, 'updated_at' => now()]);
            }
            $index++;
            $i++;
        }

        return Note::where('company_id', $id)
            ->select('id', 'index', 'group_code', 'group_name', 'account_code', 'account_head', 'current_year', 'previous_year', 'parent_index', 'merge_id')
            ->orderBy('group_code')
            ->get()
            ->groupBy('group_name');
    }

    public function notes_save(Request $request, string $id)
    {
        $company = Company::findOrFail($id);
        // dd($request->all());
        $validation_rule = [
            'index' => 'required|integer',
            'account_code' => 'required|string',
            'account_head' => 'required',
            'current_year' => 'required',
        ];
        if ($company->company_meta['comparative_accounts'] == 'Yes') {
            $validation_rule['previous_year'] = 'required';
        }
        $request->validate($validation_rule);
        
        try {
            // Get the original note to find company_id and group information
            $originalNote = Note::where('account_code', $request->account_code)
                ->where('company_id', $id)
                ->first();
            
            if (!$originalNote) {
                return response()->json([
                    'success' => false,
                    'message' => 'Original note not found.'
                ], 404);
            }

            // $companyId = $originalNote->company_id;
            $groupCode = $originalNote->group_code;
            $groupName = $originalNote->group_name;

            // Find the last index with parent_index equal to $request->index
            $lastNote = Note::where('company_id', $id)
                ->where('parent_index', $request->index)
                ->orderBy('index', 'desc')
                ->first();

            // Calculate the starting index for new entries
            if ($lastNote) {
                // The the last index (e.g., 5.2) and add 0.1 to it
                $lastIndex = floatval($lastNote->index);
                $newStartIndex = $lastIndex + 0.1;
            } else {
                // No existing records, start with parent_index + 0.1 (e.g., 5 -> 5.1)
                $newStartIndex = floatval($request->index) + 0.1;
            }

            // Check if account_head is an array (detail note) or string (descriptive note)
            if (is_array($request->account_head)) {
                // Detail Note: Create multiple note entries
                $accountHeads = $request->account_head;
                $currentYears = is_array($request->current_year) ? $request->current_year : [];
                if ($company->company_meta['comparative_accounts'] == 'Yes') {
                    $previousYears = is_array($request->previous_year) ? $request->previous_year : [];
                    // Validate arrays have same length
                    if (count($accountHeads) !== count($currentYears) || count($accountHeads) !== count($previousYears)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Mismatched array lengths for account_head, current_year, and previous_year.'
                        ], 422);
                    }
                }

                // Create notes for each detail entry
                foreach ($accountHeads as $key => $accountHead) {
                    $note = new Note();
                    $note->user_id = 1;
                    $note->company_id = $id;
                    $note->index = $newStartIndex;
                    $note->group_code = $groupCode;
                    $note->group_name = $groupName;
                    $note->account_code = $request->account_code;
                    $note->account_head = $accountHead;
                    $note->current_year = $currentYears[$key] ?? 0;
                    $note->previous_year = $previousYears[$key] ?? 0;
                    $note->parent_index = $request->index;
                    $note->modified_by = 1;
                    $note->save();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Detail note saved successfully.',
                    'data' => [
                        'parent_index' => $request->index,
                        'count' => count($accountHeads)
                    ]
                ]);
            } else {
                // Descriptive Note: Create single note entry
                $note = new Note();
                $note->user_id = 1;
                $note->company_id = $id;
                $note->index = $newStartIndex;
                $note->group_code = $groupCode;
                $note->group_name = $groupName;
                $note->account_code = $request->account_code;
                $note->account_head = $request->account_head;
                $note->current_year = NULL;
                $note->previous_year = NULL;
                $note->parent_index = $request->index;
                $note->modified_by = 1;
                $note->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Descriptive note saved successfully.',
                    'data' => [
                        'note_id' => $note->id
                    ]
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving note: ' . $e->getMessage()
            ], 500);
        }
    }

    public function child_notes_update(Request $request)
    {
        $request->validate([
            'account_id' => 'required|array',
            'company_id' => 'required',
            'index' => 'required',
            'note_type' => 'required',
            'account_code' => 'required|string',
            'account_head' => 'required',
            'current_year' => 'required',
            'previous_year' => 'required',
        ]);
        
        try {
            if ($request->note_type === 'detail') {
                Note::where('company_id', $request->company_id)
                    ->where('index', $request->index)
                    ->where('group_code', $request->group_code)
                    ->where('group_name', $request->group_name)
                    ->where('account_code', $request->account_code)
                    ->where('parent_index', $request->parent_index)
                    ->whereNotIn('id', $request->account_id)
                    ->delete();

                // Create notes for each detail entry
                foreach ($request->account_head as $key => $accountHead) {
                    if (isset($request->account_id[$key])) {
                        Note::where('id', $request->account_id[$key])
                            ->where('company_id', $request->company_id)
                            ->where('index', $request->index)
                            ->where('group_code', $request->group_code)
                            ->where('group_name', $request->group_name)
                            ->where('account_code', $request->account_code)
                            ->update(['account_head' => $accountHead, 'current_year' => $request->current_year[$key], 'previous_year' => $request->previous_year[$key]]);
                    } else {
                        $note = new Note();
                        $note->user_id = 1;
                        $note->company_id = $request->company_id;
                        $note->index = $request->index;
                        $note->group_code = $request->group_code;
                        $note->group_name = $request->group_name;
                        $note->account_code = $request->account_code;
                        $note->account_head = $accountHead;
                        $note->current_year = $request->current_year[$key] ?? 0;
                        $note->previous_year = $request->previous_year[$key] ?? 0;
                        $note->parent_index = $request->parent_index;
                        $note->modified_by = 1;
                        $note->save();
                    }
                }

                Note::where('company_id', $request->company_id)
                    ->update(['modified_by' => 1, 'updated_at' => now()]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Note updated successfully.',
                ]);
            } else if ($request->note_type === 'descriptive') {
                Note::where('id', $request->account_id)
                    ->where('company_id', $request->company_id)
                    ->where('index', $request->index)
                    ->where('group_code', $request->group_code)
                    ->where('group_name', $request->group_name)
                    ->where('account_code', $request->account_code)
                    ->update(['account_head' => $request->account_head]);

                Note::where('company_id', $request->company_id)
                    ->update(['modified_by' => 1, 'updated_at' => now()]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Note updated successfully.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving note: ' . $e->getMessage()
            ], 500);
        }
    }

    public function notes_delete(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'index' => 'required',
            'parent_index' => 'required'
        ]);

        try{
            $note = Note::where('company_id', $request->company_id)
                ->where('index', $request->index)
                ->where('parent_index', $request->parent_index)
                ->delete();
            
            // $note->delete();

            return response()->json([
                'success' => true,
                'message' => 'Note deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting note: ' . $e->getMessage()
            ], 500);
        }
    }

    public function notes_accounts_merge(string $id, Request $request)
    {
        $request->validate([
            'main_account_code' => 'required',
        ]);

        try{
            if ($request->account_codes) {
                // Remove merge_id from accounts NOT in the account_codes array
                Note::where('company_id', $id)
                    ->whereNull('parent_index')
                    ->where('merge_id', $request->main_account_code)
                    ->whereNotIn('account_code', $request->account_codes)
                    ->update(['merge_id' => null]);
                // Set merge_id for accounts in the account_codes array
                Note::where('company_id', $id)
                    ->whereNull('parent_index')
                    ->whereIn('account_code', $request->account_codes)
                    ->update(['merge_id' => $request->main_account_code]);

                // Remove merge_id from accounts NOT in the account_codes array
                // Note::where('company_id', $id)
                //     ->whereNull('parent_index')
                //     ->whereNotIn('account_code', $request->account_codes)
                //     ->update(['merge_id' => null]);
            } else {
                Note::where('company_id', $id)
                    ->whereNull('parent_index')
                    ->where('merge_id', $request->main_account_code)
                    ->update(['merge_id' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Accounts merged successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error merging note: ' . $e->getMessage()
            ], 500);
        }

    }

    public function notes_regenerate(string $id)
    {
        try {
            // Delete all parent notes for this company
            Note::where('company_id', $id)->whereNull('parent_index')->delete();

            // Regenerate notes
            $this->notes_create($id);

            return redirect()->route('notes.index', $id)->with('success', 'Notes regenerated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error regenerating notes: ' . $e->getMessage());
        }
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
