<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Company;
use App\Models\CompanyMeta;
use App\Models\TrailBalance;
use Illuminate\Http\Request;
use Pdf;

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
    private const META_SOCE_APLB = 'soce_aplb';
    private const META_SOCE_SCCI = 'soce_scci';
    private const META_SOCE_SCD = 'soce_scd';

    private $taxation_current_year = 0;

    private $taxation_previous_year = 0;

    private $otherComprehensiveIncome_current_year = 0;

    private $otherComprehensiveIncome_previous_year = 0;

    private function company(string $id)
    {
        return Company::find($id);
    }

    private function lastIndex(string $id)
    {
        $lastNote = Note::select('index')
            ->where('company_id', $id)
            ->whereNull('parent_index')
            ->orderBy('id', 'desc')
            ->first();

        return $lastNote->index;
    }

    private function gpl(string $id)
    {
        $revenue = Note::where('company_id', $id)
            ->where('group_code', 'S-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $revenue = [
            'index' => $revenue->index,
            'current_year' => $revenue->total_current_year,
            'previous_year' => $revenue->total_previous_year,
        ];

        $costOfSales = Note::where('company_id', $id)
            ->where('group_code', 'COS-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $costOfSales = [
            'index' => $costOfSales->index,
            'current_year' => -$costOfSales->total_current_year,
            'previous_year' => -$costOfSales->total_previous_year,
        ];

        return [
            'revenue' => $revenue,
            'costOfSales' => $costOfSales,
        ];
    }

    private function profitLossBeforeTaxation(string $id)
    {
        $adminExpense = Note::where('company_id', $id)
            ->where('group_code', 'EX-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $adminExpense = [
            'index' => $adminExpense->index,
            'current_year' => -$adminExpense->total_current_year,
            'previous_year' => -$adminExpense->total_previous_year,
        ];

        $financialCharges = Note::where('company_id', $id)
            ->where('group_code', 'FC-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $financialCharges = [
            'index' => $financialCharges->index,
            'current_year' => -$financialCharges->total_current_year,
            'previous_year' => -$financialCharges->total_previous_year,
        ];

        $otherIncome = Note::where('company_id', $id)
            ->where('group_code', 'OI-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $otherIncome = [
            'index' => $otherIncome->index,
            'current_year' => $otherIncome->total_current_year,
            'previous_year' => $otherIncome->total_previous_year
        ];

        return [
            'adminExpense' => $adminExpense,
            'financialCharges' => $financialCharges,
            'otherIncome' => $otherIncome,
        ];
    }

    private function taxation(string $id)
    {
        $taxation = Note::where('company_id', $id)
            ->where('group_code', 'T-001')
            ->whereNull('parent_index')
            ->selectRaw('`index`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index')
            ->first();

        $taxation = [
            'index' => $taxation->index,
            'current_year' => -(abs($taxation->total_current_year)),
            'previous_year' => -(abs($taxation->total_previous_year))
        ];

        return [
            'taxation' => $taxation,
        ];
    }

    private function sopl_data(string $id)
    {
        $company = $this->company($id);

        $lastIndex = $this->lastIndex($id);

        $revenue = $this->gpl($id)['revenue'];
        $costOfSales = $this->gpl($id)['costOfSales'];
        $adminExpense = $this->profitLossBeforeTaxation($id)['adminExpense'];
        $financialCharges = $this->profitLossBeforeTaxation($id)['financialCharges'];
        $otherIncome = $this->profitLossBeforeTaxation($id)['otherIncome'];

        $taxation = $this->taxation($id)['taxation'];

        return [
            'company' => $company,
            'lastIndex' => $lastIndex,
            'revenue' => $revenue,
            'costOfSales' => $costOfSales,
            'adminExpense' => $adminExpense,
            'financialCharges' => $financialCharges,
            'otherIncome' => $otherIncome,
            'taxation' => $taxation,
        ];
    }

    public function sopl(string $id)
    {
        $company = $this->sopl_data($id)['company'];
        $lastIndex = $this->sopl_data($id)['lastIndex'];

        $revenue = $this->sopl_data($id)['revenue'];
        $costOfSales = $this->sopl_data($id)['costOfSales'];

        $adminExpense = $this->sopl_data($id)['adminExpense'];
        $financialCharges = $this->sopl_data($id)['financialCharges'];
        $otherIncome = $this->sopl_data($id)['otherIncome'];

        $taxation = $this->sopl_data($id)['taxation'];

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

    public function sopl_export_pdf(string $id)
    {
        $company = $this->sopl_data($id)['company'];
        $lastIndex = $this->sopl_data($id)['lastIndex'];

        $revenue = $this->sopl_data($id)['revenue'];
        $costOfSales = $this->sopl_data($id)['costOfSales'];

        $adminExpense = $this->sopl_data($id)['adminExpense'];
        $financialCharges = $this->sopl_data($id)['financialCharges'];
        $otherIncome = $this->sopl_data($id)['otherIncome'];

        $taxation = $this->sopl_data($id)['taxation'];

        $style = '
        <style>
            h1 {
                font-family: "Calibiri", sans-serif;
                font-size: 14px;
            }
            p, ul, ol, ul li, ol li, a, table, table td, table th, span {
                font-family: "Calibiri", sans-serif;
                font-size: 11px;
                word-break: break-all;
            }
        </style>
        ';

        $pdf = Pdf::loadView('components.export.statements.pdf.sopl', compact(
            'company',
            'lastIndex',
            'revenue',
            'costOfSales',
            'adminExpense',
            'financialCharges',
            'otherIncome',
            'taxation',
            'style'
        ));

        // return view('components.export.statements.pdf.sopl', compact(
        //     'company',
        //     'lastIndex',
        //     'revenue',
        //     'costOfSales',
        //     'adminExpense',
        //     'financialCharges',
        //     'otherIncome',
        //     'taxation',
        //     'style'
        // ));

        return $pdf->download($company->name . ' Statement of Profit or Loss.pdf');
    }

    private function soci_data(string $id)
    {
        $company = $this->company($id);
        $lastIndex = $this->lastIndex($id);

        $revenue = $this->gpl($id)['revenue'];
        $costOfSales = $this->gpl($id)['costOfSales'];

        $adminExpense = $this->profitLossBeforeTaxation($id)['adminExpense'];
        $financialCharges = $this->profitLossBeforeTaxation($id)['financialCharges'];
        $otherIncome = $this->profitLossBeforeTaxation($id)['otherIncome'];

        $taxation = $this->taxation($id)['taxation'];

        $profitLossAfterTaxationCurrentYear = (
            $revenue['current_year'] + 
            $costOfSales['current_year'] + 
            $adminExpense['current_year'] + 
            $financialCharges['current_year'] + 
            $otherIncome['current_year']
        ) + $taxation['current_year'];

        $profitLossAfterTaxationPreviousYear = (
            $revenue['previous_year'] + 
            $costOfSales['previous_year'] + 
            $adminExpense['previous_year'] + 
            $financialCharges['previous_year'] + 
            $otherIncome['previous_year']
        ) + $taxation['previous_year'];

        $profitLossAfterTaxation = [
            'current_year' => $profitLossAfterTaxationCurrentYear,
            'previous_year' => $profitLossAfterTaxationPreviousYear
        ];

        $otherComprehensiveIncome = [
            'current_year' => $this->otherComprehensiveIncome_current_year,
            'previous_year' => $this->otherComprehensiveIncome_previous_year
        ];

        return [
            'company' => $company,
            'lastIndex' => $lastIndex,
            'profitLossAfterTaxation' => $profitLossAfterTaxation,
            'otherComprehensiveIncome' => $otherComprehensiveIncome,
        ];
    }

    public function soci(string $id)
    {
        $company = $this->company($id);

        $lastIndex = $this->lastIndex($id);

        $profitLossAfterTaxation = $this->soci_data($id)['profitLossAfterTaxation'];
        $otherComprehensiveIncome = $this->soci_data($id)['otherComprehensiveIncome'];

        return view('statements.soci', compact(
            'company',
            'lastIndex',
            'profitLossAfterTaxation',
            'otherComprehensiveIncome',
        ));
    }

    public function soci_export_pdf(string $id)
    {
        $company = $this->company($id);

        $lastIndex = $this->lastIndex($id);

        $profitLossAfterTaxation = $this->soci_data($id)['profitLossAfterTaxation'];
        $otherComprehensiveIncome = $this->soci_data($id)['otherComprehensiveIncome'];

        $style = '
        <style>
            h1 {
                font-family: "Calibiri", sans-serif;
                font-size: 14px;
            }
            p, ul, ol, ul li, ol li, a, table, table td, table th, span {
                font-family: "Calibiri", sans-serif;
                font-size: 11px;
                word-break: break-all;
            }
        </style>
        ';

        $pdf = Pdf::loadView('components.export.statements.pdf.soci', compact(
            'company',
            'lastIndex',
            'profitLossAfterTaxation',
            'otherComprehensiveIncome',
            'style'
        ));

        // return view('components.export.statements.pdf.soci', compact(
        //     'company',
        //     'lastIndex',
        //     'profitLossAfterTaxation',
        //     'otherComprehensiveIncome',
        //     'style'
        // ));

        return $pdf->download($company->name . ' Statement of Comprehensive Income.pdf');
    }

    private function paidup_capital(string $id)
    {
        $opening_capital_previous_year = TrailBalance::where('company_id', $id)
            ->where('account_code', self::ACCOUNT_OPENING_CAPITAL)
            ->selectRaw('opening_debit, opening_credit')
            ->first();

        $netPreviousYear = $opening_capital_previous_year->opening_debit - $opening_capital_previous_year->opening_credit;

        if ($netPreviousYear > 0) {
            $opening_capital_previous_year = -(abs($netPreviousYear)) ?? 0;
        } else {
            $opening_capital_previous_year = abs($netPreviousYear) ?? 0;
        }

        $opening_capital = [
            'previous_year' => $opening_capital_previous_year,
            'current_year' => 0
        ];

        $revenue = $this->gpl($id)['revenue'];
        $costOfSales = $this->gpl($id)['costOfSales'];

        $adminExpense = $this->profitLossBeforeTaxation($id)['adminExpense'];
        $financialCharges = $this->profitLossBeforeTaxation($id)['financialCharges'];
        $otherIncome = $this->profitLossBeforeTaxation($id)['otherIncome'];

        $taxation = $this->taxation($id)['taxation'];

        $profitLossAfterTaxationCurrentYear = (
            $revenue['current_year'] + 
            $costOfSales['current_year'] + 
            $adminExpense['current_year'] + 
            $financialCharges['current_year'] + 
            $otherIncome['current_year']
        ) - $taxation['current_year'];

        $profitLossAfterTaxationPreviousYear = (
            $revenue['previous_year'] + 
            $costOfSales['previous_year'] + 
            $adminExpense['previous_year'] + 
            $financialCharges['previous_year'] + 
            $otherIncome['previous_year']
        ) - $taxation['previous_year'];

        $profitLossAfterTaxation = [
            'current_year' => $profitLossAfterTaxationCurrentYear,
            'previous_year' => $profitLossAfterTaxationPreviousYear
        ];

        $otherComprehensiveIncome = [
            'current_year' => $this->otherComprehensiveIncome_current_year,
            'previous_year' => $this->otherComprehensiveIncome_previous_year
        ];

        $totalComprehensiveProfitLoss = [
            'current_year' => ($profitLossAfterTaxation['current_year'] + $otherComprehensiveIncome['current_year']),
            'previous_year' => ($profitLossAfterTaxation['previous_year'] + $otherComprehensiveIncome['previous_year'])
        ];

        $scci_previous_year = TrailBalance::where('company_id', $id)
            ->where('account_code', self::ACCOUNT_CAPITAL_INJECTION)
            ->selectRaw('opening_debit, opening_credit')
            ->first();

        $scci_previous_year = $scci_previous_year->opening_debit - $scci_previous_year->opening_credit;

        // if ($scci_previous_year->opening_debit > 0) {
        //     $scci_previous_year = -$scci_previous_year->opening_debit ?? 0;
        // } else {
        //     $scci_previous_year = $scci_previous_year->opening_credit ?? 0;
        // }

        $scci_current_year = TrailBalance::where('company_id', $id)
            ->where('account_code', self::ACCOUNT_CAPITAL_INJECTION)
            ->selectRaw('movement_debit, movement_credit')
            ->first();

        $scci_current_year = $scci_current_year->movement_debit - $scci_current_year->movement_credit;

        // if ($scci_current_year->opening_debit > 0) {
        //     $scci_current_year = -$scci_current_year->movement_debit ?? 0;
        // } else {
        //     $scci_current_year = $scci_current_year->movement_credit ?? 0;
        // }

        $capital_injection = [
            'previous_year' => $scci_previous_year,
            'current_year' => $scci_current_year,
        ];

        $drawings_previous_year = TrailBalance::where('company_id', $id)
            ->where('account_code', self::ACCOUNT_DRAWINGS)
            ->selectRaw('opening_debit, opening_credit')
            ->first();

        $net_drawings_previous_year = $drawings_previous_year->opening_debit - $drawings_previous_year->opening_credit;

        if ($drawings_previous_year->opening_debit > 0) {
            $drawings_previous_year = -(abs($net_drawings_previous_year)) ?? 0;
        } else {
            $drawings_previous_year = abs($net_drawings_previous_year) ?? 0;
        }

        $drawings_current_year = TrailBalance::where('company_id', $id)
            ->where('account_code', self::ACCOUNT_DRAWINGS)
            ->selectRaw('movement_debit, movement_credit')
            ->first();

        $net_drawings_current_year = $drawings_current_year->movement_debit - $drawings_current_year->movement_credit;

        if ($drawings_current_year->movement_debit > 0) {
            $drawings_current_year = -(abs($net_drawings_current_year)) ?? 0;
        } else {
            $drawings_current_year = abs($net_drawings_current_year) ?? 0;
        }

        $drawings = [
            'previous_year' => $drawings_previous_year,
            'current_year' => $drawings_current_year,
        ];

        $paidupCapitalPreviousYear = $opening_capital['previous_year'] + 
            $capital_injection['previous_year'] + 
            $drawings['previous_year'];

        $paidupCapitalCurrentYear = $paidupCapitalPreviousYear + 
            $capital_injection['current_year'] + 
            $drawings['current_year'];

        return [
            'paidupCapitalPreviousYear' => $paidupCapitalPreviousYear,
            'paidupCapitalCurrentYear' => $paidupCapitalCurrentYear,
        ];
    }

    private function accumulated_profit_loss(string $id)
    {
        $aplb_previous_year = CompanyMeta::where('company_id', $id)
            ->where('meta_key', self::META_SOCE_APLB)
            ->value('meta_value');

        $aplb_previous_year = $aplb_previous_year;

        $opening_capital_previous_year = TrailBalance::where('company_id', $id)
            ->where('account_code', self::ACCOUNT_OPENING_CAPITAL)
            ->selectRaw('opening_debit, opening_credit')
            ->first();

        $opening_capital_previous_year = $opening_capital_previous_year->opening_debit - $opening_capital_previous_year->opening_credit;

        // if ($opening_capital_previous_year->opening_debit > 0) {
        //     $opening_capital_previous_year = $opening_capital_previous_year->opening_debit ?? 0;
        // } else {
        //     $opening_capital_previous_year = $opening_capital_previous_year->opening_credit ?? 0;
        // }

        $opening_capital = [
            'previous_year' => $opening_capital_previous_year,
            'current_year' => 0
        ];

        $revenue = $this->gpl($id)['revenue'];
        $costOfSales = $this->gpl($id)['costOfSales'];

        $adminExpense = $this->profitLossBeforeTaxation($id)['adminExpense'];
        $financialCharges = $this->profitLossBeforeTaxation($id)['financialCharges'];
        $otherIncome = $this->profitLossBeforeTaxation($id)['otherIncome'];

        $taxation = $this->taxation($id)['taxation'];

        $profitLossAfterTaxationCurrentYear = (
            $revenue['current_year'] + 
            $costOfSales['current_year'] + 
            $adminExpense['current_year'] + 
            $financialCharges['current_year'] + 
            $otherIncome['current_year']
        ) + $taxation['current_year'];

        $profitLossAfterTaxationPreviousYear = (
            $revenue['previous_year'] + 
            $costOfSales['previous_year'] + 
            $adminExpense['previous_year'] + 
            $financialCharges['previous_year'] + 
            $otherIncome['previous_year']
        ) + $taxation['previous_year'];

        $profitLossAfterTaxation = [
            'current_year' => $profitLossAfterTaxationCurrentYear,
            'previous_year' => $profitLossAfterTaxationPreviousYear
        ];

        $otherComprehensiveIncome = [
            'current_year' => $this->otherComprehensiveIncome_current_year,
            'previous_year' => $this->otherComprehensiveIncome_previous_year
        ];

        $totalComprehensiveProfitLoss = [
            'current_year' => ($profitLossAfterTaxation['current_year'] + $otherComprehensiveIncome['current_year']),
            'previous_year' => ($profitLossAfterTaxation['previous_year'] + $otherComprehensiveIncome['previous_year'])
        ];

        $accumulatedProfitLossPreviousYear = $aplb_previous_year + 
            $totalComprehensiveProfitLoss['previous_year'];

        $accumulatedProfitLossCurrentYear = $accumulatedProfitLossPreviousYear + 
            $totalComprehensiveProfitLoss['current_year'];

        return [
            'accumulatedProfitLossPreviousYear' => $accumulatedProfitLossPreviousYear,
            'accumulatedProfitLossCurrentYear' => $accumulatedProfitLossCurrentYear,
        ];
    }

    private function soce_data(string $id)
    {
        $company = $this->company($id);
        $lastIndex = $this->lastIndex($id);

        $opening_capital_previous_year = TrailBalance::where('company_id', $id)
            ->where('account_code', self::ACCOUNT_OPENING_CAPITAL)
            ->selectRaw('opening_debit, opening_credit')
            ->first();

        $net_opening_capital_previous_year = $opening_capital_previous_year->opening_debit - $opening_capital_previous_year->opening_credit;

        if ($opening_capital_previous_year->opening_debit > 0) {
            $opening_capital_previous_year = -(abs($net_opening_capital_previous_year)) ?? 0;
        } else {
            $opening_capital_previous_year = abs($net_opening_capital_previous_year) ?? 0;
        }

        $opening_capital = [
            'previous_year' => $opening_capital_previous_year,
            'current_year' => 0
        ];

        $revenue = $this->gpl($id)['revenue'];
        $costOfSales = $this->gpl($id)['costOfSales'];

        $adminExpense = $this->profitLossBeforeTaxation($id)['adminExpense'];
        $financialCharges = $this->profitLossBeforeTaxation($id)['financialCharges'];
        $otherIncome = $this->profitLossBeforeTaxation($id)['otherIncome'];

        $taxation = $this->taxation($id)['taxation'];

        $profitLossAfterTaxationCurrentYear = (
            $revenue['current_year'] + 
            $costOfSales['current_year'] + 
            $adminExpense['current_year'] + 
            $financialCharges['current_year'] + 
            $otherIncome['current_year']
        ) + $taxation['current_year'];

        $profitLossAfterTaxationPreviousYear = (
            $revenue['previous_year'] + 
            $costOfSales['previous_year'] + 
            $adminExpense['previous_year'] + 
            $financialCharges['previous_year'] + 
            $otherIncome['previous_year']
        ) + $taxation['previous_year'];

        $profitLossAfterTaxation = [
            'current_year' => $profitLossAfterTaxationCurrentYear,
            'previous_year' => $profitLossAfterTaxationPreviousYear
        ];

        $otherComprehensiveIncome = [
            'current_year' => $this->otherComprehensiveIncome_current_year,
            'previous_year' => $this->otherComprehensiveIncome_previous_year
        ];

        $totalComprehensiveProfitLoss = [
            'current_year' => ($profitLossAfterTaxation['current_year'] + $otherComprehensiveIncome['current_year']),
            'previous_year' => ($profitLossAfterTaxation['previous_year'] + $otherComprehensiveIncome['previous_year'])
        ];

        $scci_previous_year = TrailBalance::where('company_id', $id)
            ->where('account_code', self::ACCOUNT_CAPITAL_INJECTION)
            ->selectRaw('opening_debit, opening_credit')
            ->first();

        $scci_previous_year = $scci_previous_year->opening_debit - $scci_previous_year->opening_credit;

        $scci_current_year = TrailBalance::where('company_id', $id)
            ->where('account_code', self::ACCOUNT_CAPITAL_INJECTION)
            ->selectRaw('movement_debit, movement_credit')
            ->first();

        $scci_current_year = $scci_current_year->movement_debit - $scci_current_year->movement_credit;

        $capital_injection = [
            'previous_year' => $scci_previous_year,
            'current_year' => $scci_current_year,
        ];

        $drawings_previous_year = TrailBalance::where('company_id', $id)
            ->where('account_code', self::ACCOUNT_DRAWINGS)
            ->selectRaw('opening_debit, opening_credit')
            ->first();

        $net_drawings_previous_year = $drawings_previous_year->opening_debit - $drawings_previous_year->opening_credit;

        if ($drawings_previous_year->opening_debit > 0) {
            $drawings_previous_year = -(abs($net_drawings_previous_year)) ?? 0;
        } else {
            $drawings_previous_year = abs($net_drawings_previous_year) ?? 0;
        }

        $drawings_current_year = TrailBalance::where('company_id', $id)
            ->where('account_code', self::ACCOUNT_DRAWINGS)
            ->selectRaw('movement_debit, movement_credit')
            ->first();
        
        $net_drawings_current_year = $drawings_current_year->movement_debit - $drawings_current_year->movement_credit;

        if ($drawings_current_year->movement_debit > 0) {
            $drawings_current_year = -(abs($net_drawings_current_year)) ?? 0;
        } else {
            $drawings_current_year = abs($net_drawings_current_year) ?? 0;
        }

        $drawings = [
            'previous_year' => $drawings_previous_year,
            'current_year' => $drawings_current_year,
        ];

        return [
            'company' => $company,
            'lastIndex' => $lastIndex,
            'opening_capital' => $opening_capital,
            'totalComprehensiveProfitLoss' => $totalComprehensiveProfitLoss,
            'capital_injection' => $capital_injection,
            'drawings' => $drawings,
        ];
    }

    public function soce(string $id)
    {
        $company = $this->soce_data($id)['company'];
        $lastIndex = $this->soce_data($id)['lastIndex'];

        $opening_capital = $this->soce_data($id)['opening_capital'];
        $totalComprehensiveProfitLoss = $this->soce_data($id)['totalComprehensiveProfitLoss'];
        $capital_injection = $this->soce_data($id)['capital_injection'];
        $drawings = $this->soce_data($id)['drawings'];

        return view('statements.soce', compact(
            'company',
            'lastIndex',
            'opening_capital',
            'totalComprehensiveProfitLoss',
            'capital_injection',
            'drawings',
        ));
    }

    public function soce_export_pdf(string $id)
    {
        $company = $this->soce_data($id)['company'];
        $lastIndex = $this->soce_data($id)['lastIndex'];

        $opening_capital = $this->soce_data($id)['opening_capital'];
        $totalComprehensiveProfitLoss = $this->soce_data($id)['totalComprehensiveProfitLoss'];
        $capital_injection = $this->soce_data($id)['capital_injection'];
        $drawings = $this->soce_data($id)['drawings'];

        $style = '
        <style>
            h1 {
                font-family: "Calibiri", sans-serif;
                font-size: 14px;
            }
            p, ul, ol, ul li, ol li, a, table, table td, table th, span {
                font-family: "Calibiri", sans-serif;
                font-size: 11px;
                word-break: break-all;
            }
        </style>
        ';

        $pdf = Pdf::loadView('components.export.statements.pdf.soce', compact(
            'company',
            'lastIndex',
            'opening_capital',
            'totalComprehensiveProfitLoss',
            'capital_injection',
            'drawings',
            'style'
        ));

        // return view('components.export.statements.pdf.soce', compact(
        //     'company',
        //     'lastIndex',
        //     'opening_capital',
        //     'totalComprehensiveProfitLoss',
        //     'capital_injection',
        //     'drawings',
        //     'style'
        // ));

        return $pdf->download($company->name . ' Statement of Changes in Equity.pdf');
    }

    private function sofp_data(string $id)
    {
        $company = $this->company($id);
        $lastIndex = $this->lastIndex($id);
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

        $non_current_liabilities = $figures['non_current_liabilities']->map(function ($item) {
            return [
                'index' => $item->index,
                'group_name' => $item->group_name,
                'total_current_year' => $item->total_current_year,
                'total_previous_year' => $item->total_previous_year,
            ];
        })->toArray();

        $paidup_capital_previous_year = $this->paidup_capital($id)['paidupCapitalPreviousYear'];
        $paidup_capital_current_year = $this->paidup_capital($id)['paidupCapitalCurrentYear'];

        $paidup_capital = array(
            'current_year' => $paidup_capital_current_year,
            'previous_year' => $paidup_capital_previous_year,
        );

        $accumulated_profit_loss = $this->accumulated_profit_loss($id);
        $apl = [
            'current_year' => $accumulated_profit_loss['accumulatedProfitLossCurrentYear'],
            'previous_year' => $accumulated_profit_loss['accumulatedProfitLossPreviousYear'],
        ];

        return [
            'company' => $company,
            'lastIndex' => $lastIndex,
            'non_current_assets' => $non_current_assets,
            'current_assets' => $current_assets,
            'current_liabilities' => $current_liabilities,
            'non_current_liabilities' => $non_current_liabilities,
            'paidup_capital' => $paidup_capital,
            'apl' => $apl,
        ];
    }

    public function sofp(string $id)
    {
        $company = $this->sofp_data($id)['company'];
        $lastIndex = $this->sofp_data($id)['lastIndex'];
        $non_current_assets = $this->sofp_data($id)['non_current_assets'];
        $current_assets = $this->sofp_data($id)['current_assets'];
        $non_current_liabilities = $this->sofp_data($id)['non_current_liabilities'];
        $current_liabilities = $this->sofp_data($id)['current_liabilities'];
        $paidup_capital = $this->sofp_data($id)['paidup_capital'];
        $apl = $this->sofp_data($id)['apl'];

        // $figures = $this->index($id);

        // $non_current_assets = array(
        //     'group_name' => $figures['non_current_assets']['group_name'],
        //     'index' => $figures['non_current_assets']['index'],
        //     'total_current_year' => $figures['non_current_assets']['current_year'],
        //     'total_previous_year' => $figures['non_current_assets']['previous_year'],
        // );

        // $current_assets = $figures['current_assets']->map(function ($item) {
        //     return [
        //         'index' => $item->index,
        //         'group_name' => $item->group_name,
        //         'total_current_year' => $item->total_current_year,
        //         'total_previous_year' => $item->total_previous_year,
        //     ];
        // })->toArray();

        // $current_liabilities = $figures['current_liabilities']->map(function ($item) {
        //     return [
        //         'index' => $item->index,
        //         'group_name' => $item->group_name,
        //         'total_current_year' => $item->total_current_year,
        //         'total_previous_year' => $item->total_previous_year,
        //     ];
        // })->toArray();

        // $non_current_liabilities = $figures['non_current_liabilities']->map(function ($item) {
        //     return [
        //         'index' => $item->index,
        //         'group_name' => $item->group_name,
        //         'total_current_year' => $item->total_current_year,
        //         'total_previous_year' => $item->total_previous_year,
        //     ];
        // })->toArray();

        // $scci_previous_year = CompanyMeta::where('company_id', $id)
        //     ->select('meta_value')
        //     ->where('meta_key', 'soce_scci')
        //     ->first();

        // $scd_previous_year = CompanyMeta::where('company_id', $id)
        //     ->select('meta_value')
        //     ->where('meta_key', 'soce_scd')
        //     ->first();

        // $opening_capital = array(
        //     'account_code' => $figures['opening_capital']->account_code,
        //     'account_name' => $figures['opening_capital']->account_name,
        //     'closing_debit' => $figures['opening_capital']->closing_debit,
        //     'closing_credit' => $figures['opening_capital']->closing_credit,
        // );

        // $capital_injection = array(
        //     'account_code' => $figures['capital_injection']->account_code,
        //     'account_name' => $figures['capital_injection']->account_name,
        //     'closing_debit' => $figures['capital_injection']->closing_debit,
        //     'closing_credit' => $figures['capital_injection']->closing_credit,
        // );

        // $drawings = array(
        //     'account_code' => $figures['drawings']->account_code,
        //     'account_name' => $figures['drawings']->account_name,
        //     'closing_debit' => $figures['drawings']->closing_debit,
        //     'closing_credit' => $figures['drawings']->closing_credit,
        // );

        // $sctc_previous_year = 0;
        // $sctc_current_year = 0;

        // if ($opening_capital['closing_debit'] > $opening_capital['closing_credit']) {
        //     $scb_previous_year = $opening_capital['closing_debit'] - $opening_capital['closing_credit'];
        // } else {
        //     $scb_previous_year = $opening_capital['closing_credit'] - $opening_capital['closing_debit'];
        // }

        // if ($capital_injection['closing_debit'] > $capital_injection['closing_credit']) {
        //     $scci_current_year = $capital_injection['closing_debit'];
        // } else {
        //     $scci_current_year = $capital_injection['closing_credit'];
        // }

        // if ($drawings['closing_debit'] > $drawings['closing_credit']) {
        //     $scd_current_year = $drawings['closing_debit'];
        // } else {
        //     $scd_current_year = $drawings['closing_credit'];
        // }

        // $paidup_capital_previous_year = $this->paidup_capital($id)['paidupCapitalPreviousYear'];
        // $paidup_capital_current_year = $this->paidup_capital($id)['paidupCapitalCurrentYear'];

        // $paidup_capital = array(
        //     'current_year' => $paidup_capital_current_year,
        //     'previous_year' => $paidup_capital_previous_year,
        // );

        // $accumulated_profit_loss = $this->accumulated_profit_loss($id);
        // $apl = [
        //     'current_year' => $accumulated_profit_loss['accumulatedProfitLossCurrentYear'],
        //     'previous_year' => $accumulated_profit_loss['accumulatedProfitLossPreviousYear'],
        // ];

        return view(
            'statements.sofp', 
            compact(
                'company',
                'lastIndex',
                'non_current_assets',
                'current_assets',
                'current_liabilities',
                'non_current_liabilities',
                'paidup_capital',
                'apl'
            )
        );
    }

    public function sofp_export_pdf(string $id)
    {
        $company = $this->sofp_data($id)['company'];
        $lastIndex = $this->sofp_data($id)['lastIndex'];
        $non_current_assets = $this->sofp_data($id)['non_current_assets'];
        $current_assets = $this->sofp_data($id)['current_assets'];
        $non_current_liabilities = $this->sofp_data($id)['non_current_liabilities'];
        $current_liabilities = $this->sofp_data($id)['current_liabilities'];
        $paidup_capital = $this->sofp_data($id)['paidup_capital'];
        $apl = $this->sofp_data($id)['apl'];

        $style = '
        <style>
            h1 {
                font-family: "Calibiri", sans-serif;
                font-size: 14px;
            }
            p, ul, ol, ul li, ol li, a, table, table td, table th, span {
                font-family: "Calibiri", sans-serif;
                font-size: 11px;
                word-break: break-all;
            }
        </style>
        ';

        $pdf = Pdf::loadView(
            'components.export.statements.pdf.sofp', 
            compact(
                'company',
                'lastIndex',
                'non_current_assets',
                'current_assets',
                'non_current_liabilities',
                'current_liabilities',
                'paidup_capital',
                'apl',
                'style'
            )
        );

        // return view(
        //     'components.export.statements.pdf.sofp', 
        //     compact(
        //         'company',
        //         'lastIndex',
        //         'non_current_assets',
        //         'current_assets',
        //         'current_liabilities',
        //         'paidup_capital',
        //         'apl',
        //         'style'
        //     )
        // );

        return $pdf->download($company->name . ' Statement of Financial Position.pdf');
    }

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

        $non_current_liabilities = Note::where('company_id', $id)
            ->where('group_code', 'Like', 'NCL%')
            ->whereNull('parent_index')
            ->selectRaw('`index`, `group_name`, SUM(current_year) as total_current_year, SUM(previous_year) as total_previous_year')
            ->groupBy('index', 'group_name')
            ->get();

        // Get trail balances using helper method
        $opening_capital = $this->getTrailBalance($id, self::ACCOUNT_OPENING_CAPITAL);
        $capital_injection = $this->getTrailBalance($id, self::ACCOUNT_CAPITAL_INJECTION);
        $drawings = $this->getTrailBalance($id, self::ACCOUNT_DRAWINGS);

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
            'non_current_liabilities' => $non_current_liabilities,
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

        $previous_year = $transactions->total_opening_debit - $transactions->total_opening_credit;

        // if ($transactions->total_opening_debit > 0) {
        //     $previous_year = $transactions->total_opening_debit;
        // }

        // if ($transactions->total_opening_credit > 0) {
        //     $previous_year = $transactions->total_opening_credit;
        // }
        
        $index = 4;
        $nonCurrentAssets = [
            'group_name' => $transactions->group_name,
            'index' => $index,
            'current_year' => $current_year,
            'previous_year' => $previous_year,
        ];

        return $nonCurrentAssets;
    }

    /*public function sopl(string $id)
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
    }*/

    /*public function sopl_export_pdf(string $id)
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

        $style = '
        <style>
            h1 {
                font-family: "Calibiri", sans-serif;
                font-size: 14px;
            }
            p, ul, ol, ul li, ol li, a, table, table td, table th {
                font-family: "Calibiri", sans-serif;
                font-size: 11px;
            }
        </style>
        ';

        $pdf = Pdf::loadView(
            'components.export.statements.pdf.sopl',
            compact(
                'company',
                'lastIndex',
                'revenue',
                'costOfSales',
                'adminExpense',
                'financialCharges',
                'otherIncome',
                'taxation',
                'style'
            )
        );

        return $pdf->download($company->name . ' Statement of Profit or Loss.pdf');
    }*/

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

    /*public function soci(string $id)
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

        return view(
            'statements.soci',
            compact(
                'company', 
                'lastIndex', 
                'plAfterTax', 
                'otherComprehensiveIncome'
            )
        );
    }*/

    /*public function soci_export_pdf(string $id)
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

        $style = '
        <style>
            h1 {
                font-family: "Calibiri", sans-serif;
                font-size: 14px;
            }
            p, ul, ol, ul li, ol li, a, table, table td, table th {
                font-family: "Calibiri", sans-serif;
                font-size: 11px;
            }
        </style>
        ';

        $pdf = Pdf::loadView(
            'components.export.statements.pdf.soci',
            compact(
                'company', 
                'lastIndex', 
                'plAfterTax', 
                'otherComprehensiveIncome',
                'style'
            )
        );

        return $pdf->download($company->name . ' Statement of Comprehensive Income.pdf');
    }*/

    /*public function soce(string $id)
    {
        $company = Company::find($id);
        $figures = $this->index($id);

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

        $revenue = $figures['revenue'];
        $costOfSales = $figures['costOfSales'];
        $adminExpense = $figures['adminExpense'];
        $financialCharges = $figures['financialCharges'];
        $otherIncome = $figures['otherIncome'];
        $taxation = [
            'current_year' => $this->taxation_current_year,
            'previous_year' => $this->taxation_previous_year,
        ];

        $profitLossAfterTaxationCurrentYear = $revenue['total_current_year'] + 
            $costOfSales['total_current_year'] + 
            $adminExpense['total_current_year'] + 
            $financialCharges['total_current_year'] + 
            $otherIncome['total_current_year'];

        $profitLossAfterTaxationPreviousYear = $revenue['total_previous_year'] + 
            $costOfSales['total_previous_year'] + 
            $adminExpense['total_previous_year'] + 
            $financialCharges['total_previous_year'] + 
            $otherIncome['total_previous_year'];

        $profitLossAfterTaxation = [
            'current_year' => $profitLossAfterTaxationCurrentYear,
            'previous_year' => $profitLossAfterTaxationPreviousYear
        ];

        // return $profitLossAfterTaxation;

        // return $opening_capital_previous_year;

        return view('statements.soce', compact(
            'company',
            'lastIndex',
            'opening_capital',
            'capital_injection',
            'drawings',
            'otherComprehensiveIncome',
            'scci_previous_year',
        ));
    }*/

    /*public function soce_export_pdf(string $id)
    {
        $company = Company::find($id);
        $figures = $this->index($id);

        // Get company meta values
        $aplb_previous_year = $this->getCompanyMeta($id, self::META_SOCE_APLB);
        $scci_previous_year = $this->getCompanyMeta($id, self::META_SOCE_SCCI);
        $scd_previous_year = $this->getCompanyMeta($id, self::META_SOCE_SCD);

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

        $style = '
        <style>
            h1 {
                font-family: "Calibiri", sans-serif;
                font-size: 14px;
            }
            p, ul, ol, ul li, ol li, a, table, table td, table th {
                font-family: "Calibiri", sans-serif;
                font-size: 11px;
            }
        </style>
        ';

        $pdf = Pdf::loadView(
            'components.export.statements.pdf.soce', 
            compact(
                'company',
                'lastIndex',
                'opening_capital',
                'capital_injection',
                'drawings',
                'otherComprehensiveIncome',
                'aplb_previous_year',
                'scci_previous_year',
                'scd_previous_year',
                'style'
            )
        );

        return $pdf->download($company->name . ' Statement of Changes in Equity.pdf');
    }*/

    /*public function sofp_export_pdf(string $id)
    {
        $company = Company::find($id);

        $figures = $this->index($id);

        $non_current_assets = array(
            'group_name' => $figures['non_current_assets']['group_name'],
            'index' => $figures['non_current_assets']['index'],
            'total_current_year' => $figures['non_current_assets']['current_year'],
            'total_previous_year' => $figures['non_current_assets']['previous_year'],
        );

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

        $style = '
        <style>
            h1 {
                font-family: "Calibiri", sans-serif;
                font-size: 14px;
            }
            p, ul, ol, ul li, ol li, a, table, table td, table th {
                font-family: "Calibiri", sans-serif;
                font-size: 11px;
            }
        </style>
        ';

        $pdf = Pdf::loadView(
            'components.export.statements.pdf.sofp', 
            compact(
                'company',
                'lastIndex',
                'non_current_assets',
                'current_assets',
                'current_liabilities',
                'paidup_capital',
                'apl', 
                'style'
            )
        );

        return $pdf->download($company->name . ' Statement of Financial Position.pdf');
    }*/

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
                    ['meta_key' => self::META_SOCE_APLB, 'meta_value' => $request->soce_aplb],
                    ['meta_key' => self::META_SOCE_SCCI, 'meta_value' => $request->soce_scci],
                    ['meta_key' => self::META_SOCE_SCD, 'meta_value' => $request->soce_scd]
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
        $aplb_previous_year = $this->getCompanyMeta($id, self::META_SOCE_APLB);

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

        $previous_year = $aplb_previous_year + $otherComprehensiveIncome['previous_year'] + $capital_injection + (-$drawings);
        $current_year = $previous_year + $otherComprehensiveIncome['current_year'] + $capital_injection + (-$drawings);

        return [
            'current_year' => $current_year,
            'previous_year' => $previous_year,
        ];
    }

}
