<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Company;
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

        $propertyEquipment = array(
            'index' => $propertyEquipment->index,
            'total_current_year' => $propertyEquipment->total_current_year,
            'total_previous_year' => $propertyEquipment->total_previous_year,
        );

        $capital = array(
            'index' => $capital->index,
            'total_current_year' => $capital->total_current_year,
            'total_previous_year' => $capital->total_previous_year,
        );

        $tradeReceivable = array(
            'index' => $tradeReceivable->index,
            'total_current_year' => $tradeReceivable->total_current_year,
            'total_previous_year' => $tradeReceivable->total_previous_year,
        );

        $advanceDepositPrepayment = array(
            'index' => $advanceDepositPrepayment->index,
            'total_current_year' => $advanceDepositPrepayment->total_current_year,
            'total_previous_year' => $advanceDepositPrepayment->total_previous_year,
        );

        $cashEquivalent = array(
            'index' => $cashEquivalent->index,
            'total_current_year' => $cashEquivalent->total_current_year,
            'total_previous_year' => $cashEquivalent->total_previous_year,
        );

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
            'propertyEquipment' => $propertyEquipment,
            'capital' => $capital,
            'tradeReceivable' => $tradeReceivable,
            'advanceDepositPrepayment' => $advanceDepositPrepayment,
            'cashEquivalent' => $cashEquivalent,
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
            'total_current_year' => $figures['costOfSales']['total_current_year'],
            'total_previous_year' => $figures['costOfSales']['total_previous_year'],
        );

        $adminExpense = array(
            'index' => $figures['adminExpense']['index'],
            'total_current_year' => $figures['adminExpense']['total_current_year'],
            'total_previous_year' => $figures['adminExpense']['total_previous_year'],
        );

        $financialCharges = array(
            'index' => $figures['financialCharges']['index'],
            'total_current_year' => $figures['financialCharges']['total_current_year'],
            'total_previous_year' => $figures['financialCharges']['total_previous_year'],
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

        $lastIndex = $figures['lastIndex'];

        return view('statements.soce', compact('company', 'lastIndex'));
    }

    public function sofp(string $id)
    {
        $company = Company::find($id);

        $figures = $this->index($id);

        $propertyEquipment = array(
            'index' => $figures['propertyEquipment']['index'],
            'total_current_year' => $figures['propertyEquipment']['total_current_year'],
            'total_previous_year' => $figures['propertyEquipment']['total_previous_year'],
        );

        $tnca_current_year = $propertyEquipment['total_current_year'];
        $tnca_previous_year = $propertyEquipment['total_previous_year'];

        $capital = array(
            'index' => $figures['capital']['index'],
            'total_current_year' => $figures['capital']['total_current_year'],
            'total_previous_year' => $figures['capital']['total_previous_year'],
        );

        $tradeReceivable = array(
            'index' => $figures['tradeReceivable']['index'],
            'total_current_year' => $figures['tradeReceivable']['total_current_year'],
            'total_previous_year' => $figures['tradeReceivable']['total_previous_year'],
        );

        $advanceDepositPrepayment = array(
            'index' => $figures['advanceDepositPrepayment']['index'],
            'total_current_year' => $figures['advanceDepositPrepayment']['total_current_year'],
            'total_previous_year' => $figures['advanceDepositPrepayment']['total_previous_year'],
        );

        $cashEquivalent = array(
            'index' => $figures['cashEquivalent']['index'],
            'total_current_year' => $figures['cashEquivalent']['total_current_year'],
            'total_previous_year' => $figures['cashEquivalent']['total_previous_year'],
        );

        $tca_current_year = $capital['total_current_year'] + $tradeReceivable['total_current_year'] + $advanceDepositPrepayment['total_current_year'] + $cashEquivalent['total_current_year'];
        $tca_previous_year = $capital['total_previous_year'] + $tradeReceivable['total_previous_year'] + $advanceDepositPrepayment['total_previous_year'] + $cashEquivalent['total_previous_year'];

        $lastIndex = $figures['lastIndex'];

        return view(
            'statements.sofp', 
            compact(
                'company',
                'lastIndex',
                'propertyEquipment',
                'tnca_current_year',
                'tnca_previous_year',
                'capital',
                'tradeReceivable',
                'advanceDepositPrepayment',
                'cashEquivalent',
                'tca_current_year',
                'tca_previous_year'
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
}
