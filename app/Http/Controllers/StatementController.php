<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Company;
use App\Models\CompanyMeta;
use App\Models\TrailBalance;
use Illuminate\Http\Request;

class StatementController extends Controller
{

    private $taxation_current_year = 0;

    private $taxation_previous_year = 0;

    private $otherComprehensiveIncome_current_year = 0;
    
    private $otherComprehensiveIncome_previous_year = 0;

    private function index(string $id)
    {
        $lastNote = Note::where('company_id', $id)
            ->where('group_code', 'S-001')
            ->whereNull('parent_index')
            ->orderBy('index', 'desc')
            ->first();

        $revenue = Note::where('company_id', $id)
            ->where('group_code', 'S-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $costOfSales = Note::where('company_id', $id)
            ->where('group_code', 'COS-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $adminExpense = Note::where('company_id', $id)
            ->where('group_code', 'EX-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $financialCharges = Note::where('company_id', $id)
            ->where('group_code', 'FC-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $otherIncome = Note::where('company_id', $id)
            ->where('group_code', 'OI-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $propertyEquipment = Note::where('company_id', $id)
            ->where('group_code', 'NCA-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $capital = Note::where('company_id', $id)
            ->where('group_code', 'EQ-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $tradeReceivable = Note::where('company_id', $id)
            ->where('group_code', 'CL-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $advanceDepositPrepayment = Note::where('company_id', $id)
            ->where('group_code', 'CA-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $cashEquivalent = Note::where('company_id', $id)
            ->where('group_code', 'CA-002')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $revenue = array(
            'index' => $revenue->index,
            'total_current_year' => $revenue->total_current_year,
            'total_previous_year' => $revenue->total_previous_year,
        );

        $costOfSales = array(
            'index' => $costOfSales->index,
            'total_current_year' => $costOfSales->total_current_year,
            'total_previous_year' => $costOfSales->total_previous_year,
        );

        $adminExpense = array(
            'index' => $adminExpense->index,
            'total_current_year' => $adminExpense->total_current_year,
            'total_previous_year' => $adminExpense->total_previous_year,
        );

        $financialCharges = array(
            'index' => $financialCharges->index,
            'total_current_year' => $financialCharges->total_current_year,
            'total_previous_year' => $financialCharges->total_previous_year,
        );

        $otherIncome = array(
            'index' => $otherIncome->index,
            'total_current_year' => $otherIncome->total_current_year,
            'total_previous_year' => $otherIncome->total_previous_year,
        );

        $non_current_assets = Note::where('company_id', $id)
            ->where('group_code', 'Like', 'NCA%')
            ->whereNull('parent_index')
            ->selectRaw('`index`, `group_name`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index', 'group_name')
            ->get();

        $current_assets = Note::where('company_id', $id)
            ->where('group_code', 'Like', 'CA%')
            ->whereNull('parent_index')
            ->selectRaw('`index`, `group_name`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index', 'group_name')
            ->get();

        $current_liabilities = Note::where('company_id', $id)
            ->where('group_code', 'Like', 'CL%')
            ->whereNull('parent_index')
            ->selectRaw('`index`, `group_name`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index', 'group_name')
            ->get();

        $opening_capital = TrailBalance::where('company_id', $id)
            ->where('account_code', 'CAP-001')
            ->first();
        
        $Capital_injection = TrailBalance::where('company_id', $id)
            ->where('account_code', 'CAP-003')
            ->first();

        $drawings = TrailBalance::where('company_id', $id)
            ->where('account_code', 'CAP-004')
            ->first();

        $taxation = array(
            'current_year' => $this->taxation_current_year,
            'previous_year' => $this->taxation_previous_year
        );

        return [
            'lastIndex' => $lastNote->index,
            'revenue' => $revenue,
            'costOfSales' => $costOfSales,
            'adminExpense' => $adminExpense,
            'financialCharges' => $financialCharges,
            'otherIncome' => $otherIncome,
            'non_current_assets' => $non_current_assets,
            'current_assets' => $current_assets,
            'current_liabilities' => $current_liabilities,
            'opening_capital' => $opening_capital,
            'capital_injection' => $Capital_injection,
            'drawings' => $drawings,
            'taxation' => $taxation
        ];
    }

    public function sopl(string $id)
    {
        $company = Company::find($id);

        $figures = $this->index($id);

        $revenue = array(
            'index' => $figures['revenue']['index'],
            'total_current_year' => $figures['revenue']['total_current_year'],
            'total_previous_year' => $figures['revenue']['total_previous_year'],
        );

        $costOfSales = array(
            'index' => $figures['costOfSales']['index'],
            'total_current_year' => -$figures['costOfSales']['total_current_year'],
            'total_previous_year' => -$figures['costOfSales']['total_previous_year'],
        );

        $adminExpense = array(
            'index' => $figures['adminExpense']['index'],
            'total_current_year' => -$figures['adminExpense']['total_current_year'],
            'total_previous_year' => -$figures['adminExpense']['total_previous_year'],
        );

        $financialCharges = array(
            'index' => $figures['financialCharges']['index'],
            'total_current_year' => -$figures['financialCharges']['total_current_year'],
            'total_previous_year' => -$figures['financialCharges']['total_previous_year'],
        );

        $otherIncome = array(
            'index' => $figures['otherIncome']['index'],
            'total_current_year' => $figures['otherIncome']['total_current_year'],
            'total_previous_year' => $figures['otherIncome']['total_previous_year'],
        );

        $taxation = array(
            'current_year' => $this->taxation_current_year,
            'previous_year' => $this->taxation_previous_year
        );

        $lastIndex = $figures['lastIndex'];

        return view('statements.sopl', compact('company', 'lastIndex', 'revenue', 'costOfSales', 'adminExpense', 'financialCharges', 'otherIncome', 'taxation'));
    }

    public function soci(string $id)
    {
        $company = Company::find($id);

        $figures = $this->index($id);

        $revenue = array(
            'index' => $figures['revenue']['index'],
            'total_current_year' => $figures['revenue']['total_current_year'],
            'total_previous_year' => $figures['revenue']['total_previous_year'],
        );

        $costOfSales = array(
            'index' => $figures['costOfSales']['index'],
            'total_current_year' => -$figures['costOfSales']['total_current_year'],
            'total_previous_year' => -$figures['costOfSales']['total_previous_year'],
        );

        $adminExpense = array(
            'index' => $figures['adminExpense']['index'],
            'total_current_year' => -$figures['adminExpense']['total_current_year'],
            'total_previous_year' => -$figures['adminExpense']['total_previous_year'],
        );

        $financialCharges = array(
            'index' => $figures['financialCharges']['index'],
            'total_current_year' => -$figures['financialCharges']['total_current_year'],
            'total_previous_year' => -$figures['financialCharges']['total_previous_year'],
        );

        $otherIncome = array(
            'index' => $figures['otherIncome']['index'],
            'total_current_year' => $figures['otherIncome']['total_current_year'],
            'total_previous_year' => $figures['otherIncome']['total_previous_year'],
        );

        $gpl_current_year = $revenue['total_current_year'] + $costOfSales['total_current_year'];
        $gpl_previous_year = $revenue['total_previous_year'] + $costOfSales['total_previous_year'];

        $plBeforeTax_current_year = $gpl_current_year + $adminExpense['total_current_year'] + $financialCharges['total_current_year'] + $otherIncome['total_current_year'];
        $plBeforeTax_previous_year = $gpl_previous_year + $adminExpense['total_previous_year'] + $financialCharges['total_previous_year'] + $otherIncome['total_previous_year'];

        $plAfterTax_current_year = $plBeforeTax_current_year + $this->taxation_current_year;
        $plAfterTax_previous_year = $plBeforeTax_previous_year + $this->taxation_previous_year;

        $plAfterTax = array(
            'current_year' => $plAfterTax_current_year,
            'previous_year' => $plAfterTax_previous_year
        );

        $otherComprehensiveIncome = array(
            'current_year' => $this->otherComprehensiveIncome_current_year,
            'previous_year' => $this->otherComprehensiveIncome_previous_year
        );

        $lastIndex = $figures['lastIndex'];

        return view('statements.soci', compact('company', 'lastIndex', 'plAfterTax', 'otherComprehensiveIncome'));
    }

    public function soce(string $id)
    {
        $company = Company::find($id);

        $figures = $this->index($id);

        $opening_capital = array(
            'account_code' => $figures['opening_capital']->account_code,
            'account_name' => $figures['opening_capital']->account_name,
            'closing_debit' => $figures['opening_capital']->closing_debit,
            'closing_credit' => $figures['opening_capital']->closing_credit,
        );

        $capital_injection = array(
            'account_code' => $figures['capital_injection']->account_code,
            'account_name' => $figures['capital_injection']->account_name,
            'closing_debit' => $figures['capital_injection']->closing_debit,
            'closing_credit' => $figures['capital_injection']->closing_credit,
        );

        $drawings = array(
            'account_code' => $figures['drawings']->account_code,
            'account_name' => $figures['drawings']->account_name,
            'closing_debit' => $figures['drawings']->closing_debit,
            'closing_credit' => $figures['drawings']->closing_credit,
        );

        $aplb_previous_year = CompanyMeta::where('company_id', $id)
            ->select('meta_value')
            ->where('meta_key', 'soce_aplb_previous_year')
            ->first();

        $scci_previous_year = CompanyMeta::where('company_id', $id)
            ->select('meta_value')
            ->where('meta_key', 'soce_scci_previous_year')
            ->first();

        $scd_previous_year = CompanyMeta::where('company_id', $id)
            ->select('meta_value')
            ->where('meta_key', 'soce_scd_previous_year')
            ->first();

        $gpl_current_year = $figures['revenue']['total_current_year'] - $figures['costOfSales']['total_current_year'];
        $gpl_previous_year = $figures['revenue']['total_previous_year'] - $figures['costOfSales']['total_previous_year'];

        $plBeforeTax_current_year = $gpl_current_year + (-$figures['adminExpense']['total_current_year']) + (-$figures['financialCharges']['total_current_year']) + $figures['otherIncome']['total_current_year'];
        $plBeforeTax_previous_year = $gpl_previous_year + (-$figures['adminExpense']['total_previous_year']) + (-$figures['financialCharges']['total_previous_year']) + $figures['otherIncome']['total_previous_year'];

        $plAfterTax_current_year = $plBeforeTax_current_year + $this->taxation_current_year;
        $plAfterTax_previous_year = $plBeforeTax_previous_year + $this->taxation_previous_year;

        $plAfterTax = array(
            'current_year' => $plAfterTax_current_year,
            'previous_year' => $plAfterTax_previous_year
        );

        $otherComprehensiveIncome = array(
            'current_year' => ($this->otherComprehensiveIncome_current_year + $plAfterTax_current_year),
            'previous_year' => ($this->otherComprehensiveIncome_previous_year + $plAfterTax_previous_year)
        );

        $lastIndex = $figures['lastIndex'];

        // return $opening_capital;

        return view(
            'statements.soce',
            compact(
                'company', 
                'lastIndex', 
                'opening_capital',
                'capital_injection',
                'drawings',
                'otherComprehensiveIncome',
                'aplb_previous_year',
                'scci_previous_year',
                'scd_previous_year'
            )
        );
    }

    public function sofp(string $id)
    {
        $company = Company::find($id);

        $figures = $this->index($id);

        $non_current_assets = $figures['non_current_assets']->map(function ($item) {
            return [
                'index' => $item->index,
                'group_name' => $item->group_name,
                'total_current_year' => $item->total_current_year,
                'total_previous_year' => $item->total_previous_year,
            ];
        })->toArray();

        $current_assets = $figures['current_assets']->map(function ($item) {
            return [
                'index' => $item->index,
                'group_name' => $item->group_name,
                'total_current_year' => $item->total_current_year,
                'total_previous_year' => $item->total_previous_year,
            ];
        })->toArray();

        $current_liabilities = $figures['current_liabilities']->map(function ($item) {
            return [
                'index' => $item->index,
                'group_name' => $item->group_name,
                'total_current_year' => $item->total_current_year,
                'total_previous_year' => $item->total_previous_year,
            ];
        })->toArray();

        $scci_previous_year = CompanyMeta::where('company_id', $id)
            ->select('meta_value')
            ->where('meta_key', 'soce_scci_previous_year')
            ->first();

        $scd_previous_year = CompanyMeta::where('company_id', $id)
            ->select('meta_value')
            ->where('meta_key', 'soce_scd_previous_year')
            ->first();

        $opening_capital = array(
            'account_code' => $figures['opening_capital']->account_code,
            'account_name' => $figures['opening_capital']->account_name,
            'closing_debit' => $figures['opening_capital']->closing_debit,
            'closing_credit' => $figures['opening_capital']->closing_credit,
        );

        $capital_injection = array(
            'account_code' => $figures['capital_injection']->account_code,
            'account_name' => $figures['capital_injection']->account_name,
            'closing_debit' => $figures['capital_injection']->closing_debit,
            'closing_credit' => $figures['capital_injection']->closing_credit,
        );

        $drawings = array(
            'account_code' => $figures['drawings']->account_code,
            'account_name' => $figures['drawings']->account_name,
            'closing_debit' => $figures['drawings']->closing_debit,
            'closing_credit' => $figures['drawings']->closing_credit,
        );

        $sctc_previous_year = 0;
        $sctc_current_year = 0;

        if ($opening_capital['closing_debit'] > $opening_capital['closing_credit']) {
            $scb_previous_year = $opening_capital['closing_debit'] - $opening_capital['closing_credit'];
        } else {
            $scb_previous_year = $opening_capital['closing_credit'] - $opening_capital['closing_debit'];
        }

        if ($capital_injection['closing_debit'] > $capital_injection['closing_credit']) {
            $scci_current_year = $capital_injection['closing_debit'];
        } else {
            $scci_current_year = $capital_injection['closing_credit'];
        }

        if ($drawings['closing_debit'] > $drawings['closing_credit']) {
            $scd_current_year = $drawings['closing_debit'];
        } else {
            $scd_current_year = $drawings['closing_credit'];
        }

        $paidup_capital_previous_year = ($scb_previous_year + $sctc_previous_year + $scci_previous_year->meta_value + $scd_previous_year->meta_value);
        $paidup_capital_current_year = $paidup_capital_previous_year + $sctc_current_year + $scci_current_year + $scd_current_year;

        $paidup_capital = array(
            'current_year' => $paidup_capital_current_year,
            'previous_year' => $paidup_capital_previous_year,
        );

        $sofp_apl = $this->sofp_apl($id);

        $apl = array(
            'current_year' => $sofp_apl['current_year'],
            'previous_year' => $sofp_apl['previous_year'],
        );

        $lastIndex = $figures['lastIndex'];

        return view(
            'statements.sofp', 
            compact(
                'company',
                'lastIndex',
                'non_current_assets',
                'current_assets',
                'current_liabilities',
                'paidup_capital',
                'apl'
            )
        );
    }

    public function socf(string $id)
    {
        $company = Company::find($id);

        $figures = $this->index($id);

        $lastIndex = $figures['lastIndex'];

        return view('statements.socf', compact('company', 'lastIndex'));
    }

    public function soce_update(Request $request, string $id)
    {
        try {
            $company = Company::find($id);

            $company->company_meta()->upsert(
                [
                    ['meta_key' => 'soce_aplb_previous_year', 'meta_value' => $request->soce_aplb_previous_year],
                    ['meta_key' => 'soce_scci_previous_year', 'meta_value' => $request->soce_scci_previous_year],
                    ['meta_key' => 'soce_scd_previous_year', 'meta_value' => $request->soce_scd_previous_year]
                ],
                ['company_id', 'meta_key'],
                ['meta_value']
            );

            return response()->json([
                    'success' => true,
                    'message' => 'Statement saved successfully.',
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving statement: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sofp_apl(string $id)
    {
        $figures = $this->index($id);

        $aplb_previous_year = CompanyMeta::where('company_id', $id)
            ->select('meta_value')
            ->where('meta_key', 'soce_aplb_previous_year')
            ->first();

        $gpl_current_year = $figures['revenue']['total_current_year'] - $figures['costOfSales']['total_current_year'];
        $gpl_previous_year = $figures['revenue']['total_previous_year'] - $figures['costOfSales']['total_previous_year'];

        $plBeforeTax_current_year = $gpl_current_year + (-$figures['adminExpense']['total_current_year']) + (-$figures['financialCharges']['total_current_year']) + $figures['otherIncome']['total_current_year'];
        $plBeforeTax_previous_year = $gpl_previous_year + (-$figures['adminExpense']['total_previous_year']) + (-$figures['financialCharges']['total_previous_year']) + $figures['otherIncome']['total_previous_year'];

        $plAfterTax_current_year = $plBeforeTax_current_year + $this->taxation_current_year;
        $plAfterTax_previous_year = $plBeforeTax_previous_year + $this->taxation_previous_year;

        $plAfterTax = array(
            'current_year' => $plAfterTax_current_year,
            'previous_year' => $plAfterTax_previous_year
        );

        $otherComprehensiveIncome = array(
            'current_year' => ($this->otherComprehensiveIncome_current_year + $plAfterTax_current_year),
            'previous_year' => ($this->otherComprehensiveIncome_previous_year + $plAfterTax_previous_year)
        );

        $capital_injection = 0;
        $drawings = 0;

        $previous_year = $aplb_previous_year->meta_value + $otherComprehensiveIncome['previous_year'] + $capital_injection + $drawings;
        $current_year = $previous_year + $otherComprehensiveIncome['current_year'] + $capital_injection + $drawings;

        return array(
            'current_year' => $current_year,
            'previous_year' => $previous_year,
        );
    }

}
