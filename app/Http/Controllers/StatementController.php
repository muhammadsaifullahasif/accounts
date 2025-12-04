<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Company;
use App\Models\CompanyMeta;
use App\Models\TrailBalance;
use Illuminate\Http\Request;

class StatementController extends Controller
{
    // Group Code Constants
    private const GROUP_REVENUE = 'S-001';
    private const GROUP_COST_OF_SALES = 'COS-001';
    private const GROUP_ADMIN_EXPENSE = 'EX-001';
    private const GROUP_FINANCIAL_CHARGES = 'FC-001';
    private const GROUP_OTHER_INCOME = 'OI-001';
    private const GROUP_PROPERTY_EQUIPMENT = 'NCA-001';
    private const GROUP_CAPITAL = 'EQ-001';
    private const GROUP_TRADE_RECEIVABLE = 'CL-001';
    private const GROUP_ADVANCE_DEPOSIT = 'CA-001';
    private const GROUP_CASH_EQUIVALENT = 'CA-002';
    private const GROUP_TAXATION = 'T-001';

    // Account Code Constants
    private const ACCOUNT_OPENING_CAPITAL = 'CAP-001';
    private const ACCOUNT_CAPITAL_INJECTION = 'CAP-003';
    private const ACCOUNT_DRAWINGS = 'CAP-004';

    // Meta Key Constants
    private const META_SOCE_APLB_PREVIOUS_YEAR = 'soce_aplb_previous_year';
    private const META_SOCE_SCCI_PREVIOUS_YEAR = 'soce_scci_previous_year';
    private const META_SOCE_SCD_PREVIOUS_YEAR = 'soce_scd_previous_year';

    private $taxation_current_year = 0;

    private $taxation_previous_year = 0;

    private $otherComprehensiveIncome_current_year = 0;

    private $otherComprehensiveIncome_previous_year = 0;

    /**
     * Helper: Get note totals by group code
     */
    private function getNoteTotal(string $companyId, string $groupCode): ?array
    {
        $note = Note::where('company_id', $companyId)
            ->where('group_code', $groupCode)
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        if (!$note) {
            return null;
        }

        return [
            'index' => $note->index,
            'total_current_year' => $note->total_current_year,
            'total_previous_year' => $note->total_previous_year,
        ];
    }

    /**
     * Helper: Get trail balance by account code
     */
    private function getTrailBalance(string $companyId, string $accountCode)
    {
        return TrailBalance::where('company_id', $companyId)
            ->where('account_code', $accountCode)
            ->first();
    }

    /**
     * Helper: Get company meta value
     */
    private function getCompanyMeta(string $companyId, string $metaKey)
    {
        return CompanyMeta::where('company_id', $companyId)
            ->where('meta_key', $metaKey)
            ->value('meta_value');
    }

    /**
     * Helper: Calculate GPL (Gross Profit/Loss)
     */
    private function calculateGPL(array $revenue, array $costOfSales): array
    {
        return [
            'current_year' => $revenue['total_current_year'] - $costOfSales['total_current_year'],
            'previous_year' => $revenue['total_previous_year'] - $costOfSales['total_previous_year'],
        ];
    }

    /**
     * Helper: Calculate PL Before Tax
     */
    private function calculatePLBeforeTax(array $gpl, array $adminExpense, array $financialCharges, array $otherIncome): array
    {
        return [
            'current_year' => $gpl['current_year'] + (-$adminExpense['total_current_year']) + (-$financialCharges['total_current_year']) + $otherIncome['total_current_year'],
            'previous_year' => $gpl['previous_year'] + (-$adminExpense['total_previous_year']) + (-$financialCharges['total_previous_year']) + $otherIncome['total_previous_year'],
        ];
    }

    /**
     * Helper: Calculate PL After Tax
     */
    private function calculatePLAfterTax(array $plBeforeTax): array
    {
        return [
            'current_year' => $plBeforeTax['current_year'] + $this->taxation_current_year,
            'previous_year' => $plBeforeTax['previous_year'] + $this->taxation_previous_year,
        ];
    }

    private function index(string $id)
    {
        $lastNote = Note::select('index')
            ->where('company_id', $id)
            // ->where('group_code', self::GROUP_REVENUE)
            ->whereNull('parent_index')
            ->orderBy('id', 'desc')
            ->first();

        // Get note totals using helper method
        $revenue = $this->getNoteTotal($id, self::GROUP_REVENUE);
        $costOfSales = $this->getNoteTotal($id, self::GROUP_COST_OF_SALES);
        $adminExpense = $this->getNoteTotal($id, self::GROUP_ADMIN_EXPENSE);
        $financialCharges = $this->getNoteTotal($id, self::GROUP_FINANCIAL_CHARGES);
        $otherIncome = $this->getNoteTotal($id, self::GROUP_OTHER_INCOME);
        $taxation = $this->getNoteTotal($id, self::GROUP_TAXATION);

        // Get non-current assets
        $non_current_assets = $this->getNonCurrentAssets($id);

        // Get current assets
        $current_assets = Note::where('company_id', $id)
            ->where('group_code', 'Like', 'CA%')
            ->whereNull('parent_index')
            ->selectRaw('`index`, `group_name`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index', 'group_name')
            ->get();

        // Get current liabilities
        $current_liabilities = Note::where('company_id', $id)
            ->where('group_code', 'Like', 'CL%')
            ->whereNull('parent_index')
            ->selectRaw('`index`, `group_name`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index', 'group_name')
            ->get();

        // Get trail balances using helper method
        $opening_capital = $this->getTrailBalance($id, self::ACCOUNT_OPENING_CAPITAL);
        $capital_injection = $this->getTrailBalance($id, self::ACCOUNT_CAPITAL_INJECTION);
        $drawings = $this->getTrailBalance($id, self::ACCOUNT_DRAWINGS);

        // $taxation = [
        //     'current_year' => $this->taxation_current_year,
        //     'previous_year' => $this->taxation_previous_year
        // ];

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
            'capital_injection' => $capital_injection,
            'drawings' => $drawings,
            'taxation' => $taxation
        ];
    }

    private function getNonCurrentAssets(string $id)
    {
        $company = Company::where('id', $id)->first();

        $transactions = TrailBalance::where('company_id', $id)
            ->where('group_code', 'LIKE', 'NCA%')
            ->selectRaw('`group_name`, SUM(opening_debit) as total_opening_debit, SUM(opening_credit) as total_opening_credit, SUM(closing_debit) as total_closing_debit, SUM(closing_credit) as total_closing_credit')
            ->groupBy('group_name')
            ->first();
            
        $current_year = $transactions->total_closing_debit - $transactions->total_closing_credit;

        /*if ($transactions->total_closing_debit > 0) {
            $current_year = $transactions->total_closing_debit;
        }

        if ($transactions->total_closing_credit > 0) {
            $current_year = $transactions->total_closing_credit;
        }*/

        if ($transactions->total_opening_debit > 0) {
            $previous_year = $transactions->total_opening_debit;
        }

        if ($transactions->total_opening_credit > 0) {
            $previous_year = $transactions->total_opening_credit;
        }
        
        $index = 4;
        $nonCurrentAssets = [
            'group_name' => $transactions->group_name,
            'index' => $index,
            'current_year' => $current_year,
            'previous_year' => $previous_year,
        ];

        return $nonCurrentAssets;
    }

    public function sopl(string $id)
    {
        $company = Company::find($id);
        $figures = $this->index($id);

        // Negate expense values
        $costOfSales = $this->negateValues($figures['costOfSales']);
        $adminExpense = $this->negateValues($figures['adminExpense']);
        $financialCharges = $this->negateValues($figures['financialCharges']);

        $lastIndex = $figures['lastIndex'];
        $revenue = $figures['revenue'];
        $otherIncome = $figures['otherIncome'];
        $taxation = $figures['taxation'];
        // return $taxation;

        return view('statements.sopl', compact(
            'company',
            'lastIndex',
            'revenue',
            'costOfSales',
            'adminExpense',
            'financialCharges',
            'otherIncome',
            'taxation'
        ));
    }

    /**
     * Helper: Negate current_year and previous_year values
     */
    private function negateValues(array $data): array
    {
        return [
            'index' => $data['index'],
            'total_current_year' => -$data['total_current_year'],
            'total_previous_year' => -$data['total_previous_year'],
        ];
    }

    public function soci(string $id)
    {
        $company = Company::find($id);
        $figures = $this->index($id);

        // Negate expense values
        $costOfSales = $this->negateValues($figures['costOfSales']);
        $adminExpense = $this->negateValues($figures['adminExpense']);
        $financialCharges = $this->negateValues($figures['financialCharges']);

        // Calculate GPL
        $gpl = [
            'current_year' => $figures['revenue']['total_current_year'] + $costOfSales['total_current_year'],
            'previous_year' => $figures['revenue']['total_previous_year'] + $costOfSales['total_previous_year']
        ];

        // Calculate PL Before Tax
        $plBeforeTax = [
            'current_year' => $gpl['current_year'] + $adminExpense['total_current_year'] + $financialCharges['total_current_year'] + $figures['otherIncome']['total_current_year'],
            'previous_year' => $gpl['previous_year'] + $adminExpense['total_previous_year'] + $financialCharges['total_previous_year'] + $figures['otherIncome']['total_previous_year']
        ];

        // Calculate PL After Tax
        $plAfterTax = $this->calculatePLAfterTax($plBeforeTax);

        $otherComprehensiveIncome = [
            'current_year' => $this->otherComprehensiveIncome_current_year,
            'previous_year' => $this->otherComprehensiveIncome_previous_year
        ];

        $lastIndex = $figures['lastIndex'];

        return view('statements.soci', compact('company', 'lastIndex', 'plAfterTax', 'otherComprehensiveIncome'));
    }

    public function soce(string $id)
    {
        $company = Company::find($id);
        $figures = $this->index($id);

        // Get company meta values
        $aplb_previous_year = $this->getCompanyMeta($id, self::META_SOCE_APLB_PREVIOUS_YEAR);
        $scci_previous_year = $this->getCompanyMeta($id, self::META_SOCE_SCCI_PREVIOUS_YEAR);
        $scd_previous_year = $this->getCompanyMeta($id, self::META_SOCE_SCD_PREVIOUS_YEAR);

        // Calculate GPL using helper
        $gpl = $this->calculateGPL($figures['revenue'], $figures['costOfSales']);

        // Calculate PL Before Tax using helper
        $plBeforeTax = $this->calculatePLBeforeTax($gpl, $figures['adminExpense'], $figures['financialCharges'], $figures['otherIncome']);

        // Calculate PL After Tax using helper
        $plAfterTax = $this->calculatePLAfterTax($plBeforeTax);

        $otherComprehensiveIncome = [
            'current_year' => $this->otherComprehensiveIncome_current_year + $plAfterTax['current_year'],
            'previous_year' => $this->otherComprehensiveIncome_previous_year + $plAfterTax['previous_year']
        ];

        $lastIndex = $figures['lastIndex'];
        $opening_capital = $figures['opening_capital'];
        $capital_injection = $figures['capital_injection'];
        $drawings = $figures['drawings'];

        return view('statements.soce', compact(
            'company',
            'lastIndex',
            'opening_capital',
            'capital_injection',
            'drawings',
            'otherComprehensiveIncome',
            'aplb_previous_year',
            'scci_previous_year',
            'scd_previous_year'
        ));
    }

    public function sofp(string $id)
    {
        $company = Company::find($id);

        $figures = $this->index($id);

        $non_current_assets = array(
            'group_name' => $figures['non_current_assets']['group_name'],
            'index' => $figures['non_current_assets']['index'],
            'total_current_year' => $figures['non_current_assets']['current_year'],
            'total_previous_year' => $figures['non_current_assets']['previous_year'],
        );

        // $non_current_assets = $figures['non_current_assets']->map(function ($item) {
        //     return [
        //         'index' => $item->index,
        //         'group_name' => $item->group_name,
        //         'total_current_year' => $item->total_current_year,
        //         'total_previous_year' => $item->total_previous_year,
        //     ];
        // })->toArray();

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
                    ['meta_key' => self::META_SOCE_APLB_PREVIOUS_YEAR, 'meta_value' => $request->soce_aplb_previous_year],
                    ['meta_key' => self::META_SOCE_SCCI_PREVIOUS_YEAR, 'meta_value' => $request->soce_scci_previous_year],
                    ['meta_key' => self::META_SOCE_SCD_PREVIOUS_YEAR, 'meta_value' => $request->soce_scd_previous_year]
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

        // Get company meta value
        $aplb_previous_year = $this->getCompanyMeta($id, self::META_SOCE_APLB_PREVIOUS_YEAR);

        // Calculate GPL using helper
        $gpl = $this->calculateGPL($figures['revenue'], $figures['costOfSales']);

        // Calculate PL Before Tax using helper
        $plBeforeTax = $this->calculatePLBeforeTax($gpl, $figures['adminExpense'], $figures['financialCharges'], $figures['otherIncome']);

        // Calculate PL After Tax using helper
        $plAfterTax = $this->calculatePLAfterTax($plBeforeTax);

        $otherComprehensiveIncome = [
            'current_year' => $this->otherComprehensiveIncome_current_year + $plAfterTax['current_year'],
            'previous_year' => $this->otherComprehensiveIncome_previous_year + $plAfterTax['previous_year']
        ];

        $capital_injection = 0;
        $drawings = 0;

        $previous_year = $aplb_previous_year + $otherComprehensiveIncome['previous_year'] + $capital_injection + $drawings;
        $current_year = $previous_year + $otherComprehensiveIncome['current_year'] + $capital_injection + $drawings;

        return [
            'current_year' => $current_year,
            'previous_year' => $previous_year,
        ];
    }

}
