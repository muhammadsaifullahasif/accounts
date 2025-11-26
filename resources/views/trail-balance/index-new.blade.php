@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Trail Balance</h1>
                    {{-- <a href="{{ route('companies.create') }}" class="btn btn-outline-primary btn-sm mb-3">Add Company</a> --}}
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
                        <li class="breadcrumb-item active">Trail Balance</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
@endsection

@section('content')
    @if (Session::has('success'))
        <div class="mb-3">
            <div class="alert alert-success alert-dismissible fade show">
                <strong>{{ Session::get('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        </div>
    @endif
    <div class="table-responsive trail-balance" style="height: 80vh; overflow-y: auto;">
        <table class="table table-bordered table-hover table-sm" id="trail-balance-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 2%;"></th>
                    <th rowspan="2" style="width: 25%;">A/C Head As Per Return</th>
                    <th rowspan="2" style="width: 23%;">Notes Classification</th>
                    <th rowspan="2" style="width: 5%;" class="text-center">Codes</th>
                    <th colspan="2" style="width: 15%;" class="text-center">Opening</th>
                    <th colspan="2" style="width: 15%;" class="text-center">Movement</th>
                    <th colspan="2" style="width: 15%;" class="text-center">Closing</th>
                </tr>
                <tr>
                    <th style="width: 7.5%;" class="text-center">Debit</th>
                    <th style="width: 7.5%;" class="text-center">Credit</th>
                    <th style="width: 7.5%;" class="text-center">Debit</th>
                    <th style="width: 7.5%;" class="text-center">Credit</th>
                    <th style="width: 7.5%;" class="text-center">Debit</th>
                    <th style="width: 7.5%;" class="text-center">Credit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($PPEtrailBalances as $groupCode => $accounts)
                    @php
                        $totalOpeningDebit = 0;
                        $totalOpeningCredit = 0;
                        $totalMovementDebit = 0;
                        $totalMovementCredit = 0;
                        $totalClosingDebit = 0;
                        $totalClosingCredit = 0;
                    @endphp
                    @if (count($accounts) > 0)
                        <tr class="sub-table-row" style="display: none;" data-group-id="{{ $groupCode }}">
                            <td colspan="10" class="sub-table-cell p-0">
                                <div class="sub-table-wrapper table-responsive">
                                    <table class="table table-striped table-bordered table-hover table-sm mb-0">
                                        <tbody>
                                            @foreach ($accounts as $account)
                                                @php
                                                    $totalOpeningDebit += $account->opening_debit;
                                                    $totalOpeningCredit += $account->opening_credit;
                                                    $totalMovementDebit += $account->movement_debit;
                                                    $totalMovementCredit += $account->movement_credit;
                                                    $totalClosingDebit += $account->closing_debit;
                                                    $totalClosingCredit += $account->closing_credit;
                                                @endphp
                                                <tr>
                                                    <td style="width: 2.45%;"></td>
                                                    <td style="width: 24.85%;">
                                                        <input type="hidden" name="accountCode" value="{{ $account->account_code }}">
                                                        <input type="hidden" name="accountHead" value="{{ $account->account_head }}">
                                                        <input type="hidden" name="groupCode" value="{{ $groupCode }}">
                                                        <input type="hidden" name="groupName" value="{{ $account->group_name }}">
                                                    </td>
                                                    <td style="width: 22.85%;">{{ $account->account_head }}</td>
                                                    <td style="width: 5%;" class="text-center">{{ $account->account_code }}</td>
                                                    <td style="width: 7.5%;" class="text-center"><input type="text" readonly name="{{ $account->account_code }}-opening-debit" id="{{ $account->account_code }}-opening-debit" value="{{ $account->opening_debit ?? 0 }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                                    <td style="width: 7.5%;" class="text-center"><input type="text" readonly name="{{ $account->account_code }}-opening-credit" id="{{ $account->account_code }}-opening-credit" value="{{ $account->opening_credit ?? 0 }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                                    <td style="width: 7.5%;" class="text-center"><input type="text" readonly name="{{ $account->account_code }}-movement-debit" id="{{ $account->account_code }}-movement-debit" value="{{ $account->movement_debit ?? 0 }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                                    <td style="width: 7.5%;" class="text-center"><input type="text" readonly name="{{ $account->account_code }}-movement-credit" id="{{ $account->account_code }}-movement-credit" value="{{ $account->movement_credit ?? 0 }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                                    <td style="width: 7.5%;" class="text-center">{{ $account->closing_debit ?? 0 }}</td>
                                                    <td style="width: 7.5%;" class="text-center">{{ $account->closing_credit ?? 0 }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @endif
                    <tr data-group-code="{{ $groupCode }}">
                        <td>
                            <a href="#" class="btn btn-sm toggle-arrow" data-group-id="{{ $groupCode }}">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                        <td><strong>Property, Plant and Equipments</strong></td>
                        <td><strong>{{ $accounts[0]->group_name }}</strong></td>
                        <td class="text-center">{{ $groupCode }}</td>
                        <td class="text-center"><input type="text" readonly name="{{ $groupCode }}-opening-debit" id="{{ $groupCode }}-opening-debit" value="{{ $totalOpeningDebit }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" readonly name="{{ $groupCode }}-opening-credit" id="{{ $groupCode }}-opening-credit" value="{{ $totalOpeningCredit }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" readonly name="{{ $groupCode }}-movement-debit" id="{{ $groupCode }}-movement-debit" value="{{ $totalMovementDebit }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" readonly name="{{ $groupCode }}-movement-credit" id="{{ $groupCode }}-movement-credit" value="{{ $totalMovementCredit }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">{{ $totalClosingDebit }}</td>
                        <td class="text-center">{{ $totalClosingCredit }}</td>
                    </tr>
                @endforeach
                @forelse ($trailBalances as $groupCode => $accounts)
                    @php
                        $totalOpeningDebit = 0;
                        $totalOpeningCredit = 0;
                        $totalMovementDebit = 0;
                        $totalMovementCredit = 0;
                        $totalClosingDebit = 0;
                        $totalClosingCredit = 0;
                    @endphp
                    @if (count($accounts) > 1)
                        <tr class="sub-table-row" style="display: none;" data-group-id="{{ $groupCode }}">
                            <td colspan="10" class="sub-table-cell p-0">
                                <div class="sub-table-wrapper table-responsive">
                                    <table class="table table-striped table-bordered table-hover table-sm mb-0">
                                        <tbody>
                                            @foreach ($accounts as $account)
                                                @php
                                                    $totalOpeningDebit += $account->opening_debit;
                                                    $totalOpeningCredit += $account->opening_credit;
                                                    $totalMovementDebit += $account->movement_debit;
                                                    $totalMovementCredit += $account->movement_credit;
                                                    $totalClosingDebit += $account->closing_debit;
                                                    $totalClosingCredit += $account->closing_credit;
                                                @endphp
                                                <tr>
                                                    <td style="width: 2.45%;"></td>
                                                    <td style="width: 24.85%;">
                                                        <input type="hidden" name="accountCode" value="{{ $account->account_code }}">
                                                        <input type="hidden" name="accountHead" value="{{ $account->account_head }}">
                                                        <input type="hidden" name="groupCode" value="{{ $groupCode }}">
                                                        <input type="hidden" name="groupName" value="{{ $account->group_name }}">
                                                    </td>
                                                    <td style="width: 22.85%;">{{ $account->account_head }}</td>
                                                    <td style="width: 5%;" class="text-center">{{ $account->account_code }}</td>
                                                    <td style="width: 7.5%;" class="text-center"><input type="text" name="{{ $account->account_code }}-opening-debit" id="{{ $account->account_code }}-opening-debit" value="{{ $account->opening_debit ?? 0 }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                                    <td style="width: 7.5%;" class="text-center"><input type="text" name="{{ $account->account_code }}-opening-credit" id="{{ $account->account_code }}-opening-credit" value="{{ $account->opening_credit ?? 0 }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                                    <td style="width: 7.5%;" class="text-center"><input type="text" name="{{ $account->account_code }}-movement-debit" id="{{ $account->account_code }}-movement-debit" value="{{ $account->movement_debit ?? 0 }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                                    <td style="width: 7.5%;" class="text-center"><input type="text" name="{{ $account->account_code }}-movement-credit" id="{{ $account->account_code }}-movement-credit" value="{{ $account->movement_credit ?? 0 }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                                    <td style="width: 7.5%;" class="text-center">{{ $account->closing_debit ?? 0 }}</td>
                                                    <td style="width: 7.5%;" class="text-center">{{ $account->closing_credit ?? 0 }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @endif
                    <tr data-group-code="{{ $groupCode }}" class="parent">
                        <td>
                            @if (count($accounts) > 1)
                            <a href="#" class="btn btn-sm toggle-arrow" data-group-id="{{ $groupCode }}">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $accounts[0]->group_name }}</strong>
                            <input type="hidden" name="accountCode" value="{{ $accounts[0]['account_code'] }}">
                            <input type="hidden" name="accountHead" value="{{ $accounts[0]['account_head'] }}">
                            <input type="hidden" name="groupCode" value="{{ $accounts[0]['group_code'] }}">
                            <input type="hidden" name="groupName" value="{{ $accounts[0]['group_name'] }}">
                        </td>
                        <td></td>
                        <td class="text-center">{{ $groupCode }}</td>
                        <td class="text-center"><input type="text" name="{{ $groupCode }}-opening-debit" id="{{ $groupCode }}-opening-debit" value="{{ (count($accounts) > 1) ? $totalOpeningDebit : $accounts[0]['opening_debit'] }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="{{ $groupCode }}-opening-credit" id="{{ $groupCode }}-opening-credit" value="{{ (count($accounts) > 1) ? $totalOpeningCredit : $accounts[0]['opening_credit'] }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="{{ $groupCode }}-movement-debit" id="{{ $groupCode }}-movement-debit" value="{{ (count($accounts) > 1) ? $totalMovementDebit : $accounts[0]['movement_debit'] }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="{{ $groupCode }}-movement-credit" id="{{ $groupCode }}-movement-credit" value="{{ (count($accounts) > 1) ? $totalMovementCredit : $accounts[0]['movement_credit'] }}" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">{{ (count($accounts) > 1) ? $totalClosingDebit : $accounts[0]['closing_debit'] }}</td>
                        <td class="text-center">{{ (count($accounts) > 1) ? $totalClosingCredit : $accounts[0]['closing_credit'] }}</td>
                    </tr>
                @empty
                    <tr class="sub-table-row" style="display: none;" data-group-id="CA-001">
                        <td colspan="10" class="sub-table-cell p-0">
                            <div class="sub-table-wrapper table-responsive" style="">
                                <table class="table table-striped table-bordered table-hover table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td style="max-width: 2.45%; width: 100%;"></td>
                                            <td style="max-width: 24.85%; width: 100%;">
                                                <input type="hidden" name="accountCode" value="ADP-001">
                                                <input type="hidden" name="accountHead" value="Advance Tax">
                                                <input type="hidden" name="groupCode" value="CA-001">
                                                <input type="hidden" name="groupName" value="Advances / Deposits / Prepayments">
                                            </td>
                                            <td style="max-width: 22.85%; width: 100%;">Advance Tax</td>
                                            <td style="max-width: 5%; width: 100%;" class="text-center">ADP-001</td>
                                            <td style="max-width: 7.5%; width: 100%;" class="text-center"><input type="text" name="ADP-001-opening-debit" id="ADP-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="max-width: 7.5%; width: 100%;" class="text-center"><input type="text" name="ADP-001-opening-credit" id="ADP-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="max-width: 7.5%; width: 100%;" class="text-center"><input type="text" name="ADP-001-movement-debit" id="ADP-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="max-width: 7.5%; width: 100%;" class="text-center"><input type="text" name="ADP-001-movement-credit" id="ADP-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="max-width: 7.5%; width: 100%;" class="text-center closingDebit">0</td>
                                            <td style="max-width: 7.5%; width: 100%;" class="text-center closingCredit">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="ADP-002">
                                                <input type="hidden" name="accountHead" value="Sales Tax Refundable">
                                                <input type="hidden" name="groupCode" value="CA-001">
                                                <input type="hidden" name="groupName" value="Advances / Deposits / Prepayments">
                                            </td>
                                            <td>Sales Tax Refundable</td>
                                            <td class="text-center">ADP-002</td>
                                            <td class="text-center"><input type="text" name="ADP-002-opening-debit" id="ADP-002-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="ADP-002-opening-credit" id="ADP-002-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="ADP-002-movement-debit" id="ADP-002-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="ADP-002-movement-credit" id="ADP-002-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="ADP-003">
                                                <input type="hidden" name="accountHead" value="Advances To Employees">
                                                <input type="hidden" name="groupCode" value="CA-001">
                                                <input type="hidden" name="groupName" value="Advances / Deposits / Prepayments">
                                            </td>
                                            <td>Advances To Employees</td>
                                            <td class="text-center">ADP-003</td>
                                            <td class="text-center"><input type="text" name="ADP-003-opening-debit" id="ADP-003-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="ADP-003-opening-credit" id="ADP-003-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="ADP-003-movement-debit" id="ADP-003-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="ADP-003-movement-credit" id="ADP-003-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="ADP-004">
                                                <input type="hidden" name="accountHead" value="Advance To Vendors">
                                                <input type="hidden" name="groupCode" value="CA-001">
                                                <input type="hidden" name="groupName" value="Advances / Deposits / Prepayments">
                                            </td>
                                            <td>Advance To Vendors</td>
                                            <td class="text-center">ADP-004</td>
                                            <td class="text-center"><input type="text" name="ADP-004-opening-debit" id="ADP-004-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="ADP-004-opening-credit" id="ADP-004-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="ADP-004-movement-debit" id="ADP-004-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="ADP-004-movement-credit" id="ADP-004-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="ADP-005">
                                                <input type="hidden" name="accountHead" value="Trade Debtors">
                                                <input type="hidden" name="groupCode" value="CA-001">
                                                <input type="hidden" name="groupName" value="Advances / Deposits / Prepayments">
                                            </td>
                                            <td>Trade Debtors</td>
                                            <td class="text-center">ADP-005</td>
                                            <td class="text-center"><input type="text" name="ADP-005-opening-debit" id="ADP-005-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="ADP-005-opening-credit" id="ADP-005-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="ADP-005-movement-debit" id="ADP-005-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="ADP-005-movement-credit" id="ADP-005-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr data-group-code="CA-001" class="parent">
                        <td>
                            <a href="#" class="btn btn-sm toggle-arrow" data-group-id="CA-001">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                        <td>
                            <strong>Advances / Deposits / Prepayments</strong>
                        </td>
                        <td>
                            <strong>Advances / Deposits / Prepayments</strong>
                            <input type="hidden" name="groupCode" value="CA-001">
                        </td>
                        <td class="text-center">CA-001</td>
                        <td class="text-center"><input type="text" name="CA-001-opening-debit" id="CA-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CA-001-opening-credit" id="CA-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CA-001-movement-debit" id="CA-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CA-001-movement-credit" id="CA-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                    </tr>
                    <tr class="sub-table-row" style="display: none;" data-group-id="CA-002">
                        <td colspan="10" class="sub-table-cell p-0">
                            <div class="sub-table-wrapper table-responsive" style="">
                                <table class="table table-striped table-bordered table-hover table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td style="width: 2.45%;"></td>
                                            <td style="width: 24.85%;">
                                                <input type="hidden" name="accountCode" value="CCE-001">
                                                <input type="hidden" name="accountHead" value="Cash in Hand">
                                                <input type="hidden" name="groupCode" value="CA-002">
                                                <input type="hidden" name="groupName" value="Cash / Cash Equivalents">
                                            </td>
                                            <td style="width: 22.85%;">Cash in Hand</td>
                                            <td style="width: 5%;" class="text-center">CCE-001</td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="CCE-001-opening-debit" id="CCE-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="CCE-001-opening-credit" id="CCE-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="CCE-001-movement-debit" id="CCE-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="CCE-001-movement-credit" id="CCE-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="CCE-002">
                                                <input type="hidden" name="accountHead" value="Cash at Bank - Current">
                                                <input type="hidden" name="groupCode" value="CA-002">
                                                <input type="hidden" name="groupName" value="Cash / Cash Equivalents">
                                            </td>
                                            <td>Cash at Bank - Current</td>
                                            <td class="text-center">CCE-002</td>
                                            <td class="text-center"><input type="text" name="CCE-002-opening-debit" id="CCE-002-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CCE-002-opening-credit" id="CCE-002-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CCE-002-movement-debit" id="CCE-002-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CCE-002-movement-credit" id="CCE-002-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="CCE-003">
                                                <input type="hidden" name="accountHead" value="Cash at Bank - Savings">
                                                <input type="hidden" name="groupCode" value="CA-002">
                                                <input type="hidden" name="groupName" value="Cash / Cash Equivalents">
                                            </td>
                                            <td>Cast at Bank - Savings</td>
                                            <td class="text-center">CCE-003</td>
                                            <td class="text-center"><input type="text" name="CCE-003-opening-debit" id="CCE-003-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CCE-003-opening-credit" id="CCE-003-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CCE-003-movement-debit" id="CCE-003-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CCE-003-movement-credit" id="CCE-003-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr data-group-code="CA-002" class="parent">
                        <td>
                            <a href="#" class="btn btn-sm toggle-arrow" data-group-id="CA-002">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                        <td>
                            <strong>Cash / Cash Equivalents</strong>
                        </td>
                        <td>
                            <strong>Cash / Cash Equivalents</strong>
                            <input type="hidden" name="groupCode" value="CA-002">
                        </td>
                        <td></td>
                        <td class="text-center">CA-002</td>
                        <td class="text-center"><input type="text" name="CA-002-opening-debit" id="CA-002-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CA-002-opening-credit" id="CA-002-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CA-002-movement-debit" id="CA-002-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CA-002-movement-credit" id="CA-002-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                    </tr>
                    <tr class="sub-table-row" style="display: none;" data-group-id="CA-003">
                        <td colspan="10" class="sub-table-cell p-0">
                            <div class="sub-table-wrapper table-responsive" style="">
                                <table class="table table-striped table-bordered table-hover table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td style="width: 2.45%;"></td>
                                            <td style="width: 24.85%;">
                                                <input type="hidden" name="accountCode" value="OA-001">
                                                <input type="hidden" name="accountHead" value="Investments in Other Businesses">
                                                <input type="hidden" name="groupCode" value="CA-003">
                                                <input type="hidden" name="groupName" value="Other Assets">
                                            </td>
                                            <td style="width: 22.85%;">Investments in Other Businesses</td>
                                            <td style="width: 5%;" class="text-center">OA-001</td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OA-001-opening-debit" id="OA-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OA-001-opening-credit" id="OA-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OA-001-movement-debit" id="OA-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OA-001-movement-credit" id="OA-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="OA-002">
                                                <input type="hidden" name="accountHead" value="Security Deposits">
                                                <input type="hidden" name="groupCode" value="CA-003">
                                                <input type="hidden" name="groupName" value="Other Assets">
                                            </td>
                                            <td>Security Deposits</td>
                                            <td class="text-center">OA-002</td>
                                            <td class="text-center"><input type="text" name="OA-002-opening-debit" id="OA-002-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OA-002-opening-credit" id="OA-002-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OA-002-movement-debit" id="OA-002-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OA-002-movement-credit" id="OA-002-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr data-group-code="CA-003" class="parent">
                        <td>
                            <a href="#" class="btn btn-sm toggle-arrow" data-group-id="CA-003">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                        <td><strong>Other Assets</strong></td>
                        <td>
                            <strong>Other Assets</strong>
                            <input type="hidden" name="groupCode" value="CA-003">
                        </td>
                        <td></td>
                        <td class="text-center">CA-003</td>
                        <td class="text-center"><input type="text" name="CA-003-opening-debit" id="CA-003-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CA-003-opening-credit" id="CA-003-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CA-003-movement-debit" id="CA-003-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CA-003-movement-credit" id="CA-003-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                    </tr>
                    <tr class="sub-table-row" style="display: none;" data-group-id="EQ-001">
                        <td colspan="10" class="sub-table-cell p-0">
                            <div class="sub-table-wrapper table-responsive" style="">
                                <table class="table table-striped table-bordered table-hover table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td style="width: 2.45%;"></td>
                                            <td style="width: 24.85%;">
                                                <input type="hidden" name="accountCode" value="CAP-001">
                                                <input type="hidden" name="accountHead" value="Opening Capital">
                                                <input type="hidden" name="groupCode" value="EQ-001">
                                                <input type="hidden" name="groupName" value="Capital">
                                            </td>
                                            <td style="width: 22.85%;">Opening Capital</td>
                                            <td style="width: 5%;" class="text-center">CAP-001</td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="CAP-001-opening-debit" id="CAP-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="CAP-001-opening-credit" id="CAP-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="CAP-001-movement-debit" id="CAP-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="CAP-001-movement-credit" id="CAP-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="CAP-002">
                                                <input type="hidden" name="accountHead" value="Profit for the Year">
                                                <input type="hidden" name="groupCode" value="EQ-001">
                                                <input type="hidden" name="groupName" value="Capital">
                                            </td>
                                            <td>Profit for the Year</td>
                                            <td class="text-center">CAP-002</td>
                                            <td class="text-center"><input type="text" name="CAP-002-opening-debit" id="CAP-002-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CAP-002-opening-credit" id="CAP-002-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CAP-002-movement-debit" id="CAP-002-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CAP-002-movement-credit" id="CAP-002-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="CAP-003">
                                                <input type="hidden" name="accountHead" value="Capital Injection">
                                                <input type="hidden" name="groupCode" value="EQ-001">
                                                <input type="hidden" name="groupName" value="Capital">
                                            </td>
                                            <td>Capital Injection</td>
                                            <td class="text-center">CAP-003</td>
                                            <td class="text-center"><input type="text" name="CAP-003-opening-debit" id="CAP-003-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CAP-003-opening-credit" id="CAP-003-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CAP-003-movement-debit" id="CAP-003-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CAP-003-movement-credit" id="CAP-003-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="CAP-004">
                                                <input type="hidden" name="accountHead" value="Drawings">
                                                <input type="hidden" name="groupCode" value="EQ-001">
                                                <input type="hidden" name="groupName" value="Capital">
                                            </td>
                                            <td>Drawings</td>
                                            <td class="text-center">CAP-004</td>
                                            <td class="text-center"><input type="text" name="CAP-004-opening-debit" id="CAP-004-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CAP-004-opening-credit" id="CAP-004-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CAP-004-movement-debit" id="CAP-004-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="CAP-004-movement-credit" id="CAP-004-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr data-group-code="EQ-001" class="parent">
                        <td>
                            <a href="#" class="btn btn-sm toggle-arrow" data-group-id="EQ-001">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                        <td><strong>Capital</strong></td>
                        <td>
                            <strong>Capital</strong>
                            <input type="hidden" name="groupCode" value="EQ-001">
                        </td>
                        <td></td>
                        <td class="text-center">EQ-001</td>
                        <td class="text-center"><input type="text" name="EQ-001-opening-debit" id="EQ-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="EQ-001-opening-credit" id="EQ-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="EQ-001-movement-debit" id="EQ-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="EQ-001-movement-credit" id="EQ-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                    </tr>
                    <tr class="sub-table-row" style="display: none;" data-group-id="NCL-001">
                        <td colspan="10" class="sub-table-cell p-0">
                            <div class="sub-table-wrapper table-responsive" style="">
                                <table class="table table-striped table-bordered table-hover table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td style="width: 2.45%;"></td>
                                            <td style="width: 24.85%;">
                                                <input type="hidden" name="accountCode" value="LTBDL-001">
                                                <input type="hidden" name="accountHead" value="Loan from Directors">
                                                <input type="hidden" name="groupCode" value="NCL-001">
                                                <input type="hidden" name="groupName" value="Non Current Liabilities">
                                            </td>
                                            <td style="width: 22.85%;">Loan from Directors</td>
                                            <td style="width: 5%;" class="text-center">LTBDL-001</td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="LTBDL-001-opening-debit" id="LTBDL-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="LTBDL-001-opening-credit" id="LTBDL-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="LTBDL-001-movement-debit" id="LTBDL-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="LTBDL-001-movement-credit" id="LTBDL-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="LTBDL-002">
                                                <input type="hidden" name="accountHead" value="Loan from Bank - Non Current Portion">
                                                <input type="hidden" name="groupCode" value="NCL-001">
                                                <input type="hidden" name="groupName" value="Non Current Liabilities">
                                            </td>
                                            <td>Loan from Bank - Non Current Portion</td>
                                            <td class="text-center">LTBDL-002</td>
                                            <td class="text-center"><input type="text" name="LTBDL-002-opening-debit" id="LTBDL-002-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="LTBDL-002-opening-credit" id="LTBDL-002-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="LTBDL-002-movement-debit" id="LTBDL-002-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="LTBDL-002-movement-credit" id="LTBDL-002-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="LTBDL-003">
                                                <input type="hidden" name="accountHead" value="Lease Liability - Non Current Portion">
                                                <input type="hidden" name="groupCode" value="NCL-001">
                                                <input type="hidden" name="groupName" value="Non Current Liabilities">
                                            </td>
                                            <td>Lease Liability - Non Current Portion</td>
                                            <td class="text-center">LTBDL-003</td>
                                            <td class="text-center"><input type="text" name="LTBDL-003-opening-debit" id="LTBDL-003-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="LTBDL-003-opening-credit" id="LTBDL-003-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="LTBDL-003-movement-debit" id="LTBDL-003-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="LTBDL-003-movement-credit" id="LTBDL-003-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr data-group-code="NCL-001" class="parent">
                        <td>
                            <a href="#" class="btn btn-sm toggle-arrow" data-group-id="NCL-001">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                        <td><strong>Long Term Borrowings / Debt / Loan</strong></td>
                        <td>
                            <strong>Non Current Liabilities</strong>
                            <input type="hidden" name="groupCode" value="NCL-001">
                        </td>
                        <td></td>
                        <td class="text-center">NCL-001</td>
                        <td class="text-center"><input type="text" name="NCL-001-opening-debit" id="NCL-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="NCL-001-opening-credit" id="NCL-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="NCL-001-movement-debit" id="NCL-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="NCL-001-movement-credit" id="NCL-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                    </tr>
                    <tr class="sub-table-row" style="display: none;" data-group-id="CL-001">
                        <td colspan="10" class="sub-table-cell p-0">
                            <div class="sub-table-wrapper table-responsive" style="">
                                <table class="table table-striped table-bordered table-hover table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td style="width: 2.45%"></td>
                                            <td style="width: 24.85%">
                                                <input type="hidden" name="accountCode" value="TCP-001">
                                                <input type="hidden" name="accountHead" value="Trade Creditors">
                                                <input type="hidden" name="groupCode" value="CL-001">
                                                <input type="hidden" name="groupName" value="Trade Creditors and other Payables -Current Liabilities">
                                            </td>
                                            <td style="width: 22.85%">Trade Creditors</td>
                                            <td style="width: 5%" class="text-center">TCP-001</td>
                                            <td style="width: 7.5%" class="text-center"><input type="text" name="TCP-001-opening-debit" id="TCP-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%" class="text-center"><input type="text" name="TCP-001-opening-credit" id="TCP-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%" class="text-center"><input type="text" name="TCP-001-movement-debit" id="TCP-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%" class="text-center"><input type="text" name="TCP-001-movement-credit" id="TCP-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%" class="text-center">0</td>
                                            <td style="width: 7.5%" class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="TCP-002">
                                                <input type="hidden" name="accountHead" value="Accrued Liabilities">
                                                <input type="hidden" name="groupCode" value="CL-001">
                                                <input type="hidden" name="groupName" value="Trade Creditors and other Payables -Current Liabilities">
                                            </td>
                                            <td>Accrued Liabilities</td>
                                            <td class="text-center">TCP-002</td>
                                            <td class="text-center"><input type="text" name="TCP-002-opening-debit" id="TCP-002-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="TCP-002-opening-credit" id="TCP-002-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="TCP-002-movement-debit" id="TCP-002-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="TCP-002-movement-credit" id="TCP-002-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="TCP-003">
                                                <input type="hidden" name="accountHead" value="Advances from Customers">
                                                <input type="hidden" name="groupCode" value="CL-001">
                                                <input type="hidden" name="groupName" value="Trade Creditors and other Payables -Current Liabilities">
                                            </td>
                                            <td>Advances from Customers</td>
                                            <td class="text-center">TCP-003</td>
                                            <td class="text-center"><input type="text" name="TCP-003-opening-debit" id="TCP-003-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="TCP-003-opening-credit" id="TCP-003-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="TCP-003-movement-debit" id="TCP-003-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="TCP-003-movement-credit" id="TCP-003-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="TCP-004">
                                                <input type="hidden" name="accountHead" value="Sales Tax Payable">
                                                <input type="hidden" name="groupCode" value="CL-001">
                                                <input type="hidden" name="groupName" value="Trade Creditors and other Payables -Current Liabilities">
                                            </td>
                                            <td>Sales Tax Payable</td>
                                            <td class="text-center">TCP-004</td>
                                            <td class="text-center"><input type="text" name="TCP-004-opening-debit" id="TCP-004-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="TCP-004-opening-credit" id="TCP-004-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="TCP-004-movement-debit" id="TCP-004-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="TCP-004-movement-credit" id="TCP-004-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="TCP-005">
                                                <input type="hidden" name="accountHead" value="Provision for Tax">
                                                <input type="hidden" name="groupCode" value="CL-001">
                                                <input type="hidden" name="groupName" value="Trade Creditors and other Payables -Current Liabilities">
                                            </td>
                                            <td>Provision for Tax</td>
                                            <td class="text-center">TCP-005</td>
                                            <td class="text-center"><input type="text" name="TCP-005-opening-debit" id="TCP-005-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="TCP-005-opening-credit" id="TCP-005-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="TCP-005-movement-debit" id="TCP-005-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="TCP-005-movement-credit" id="TCP-005-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr data-group-code="CL-001" class="parent">
                        <td>
                            <a href="#" class="btn btn-sm toggle-arrow" data-group-id="CL-001">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                        <td>
                            <strong>Trade Creditors / Payables</strong>
                            <input type="hidden" name="groupCode" value="CL-001">
                        </td>
                        <td><strong>Trade Creditors and other Payables -Current Liabilities</strong></td>
                        <td class="text-center">CL-001</td>
                        <td class="text-center"><input type="text" name="CL-001-opening-debit" id="CL-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CL-001-opening-credit" id="CL-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CL-001-movement-debit" id="CL-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CL-001-movement-credit" id="CL-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                    </tr>
                    <tr class="sub-table-row" style="display: none;" data-group-id="CL-002">
                        <td colspan="10" class="sub-table-cell p-0">
                            <div class="sub-table-wrapper table-responsive" style="">
                                <table class="table table-striped table-bordered table-hover table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td style="width: 2.45%;"></td>
                                            <td style="width: 24.85%;">
                                                <input type="hidden" name="accountCode" value="OL-001">
                                                <input type="hidden" name="accountHead" value="Loan from Directors">
                                                <input type="hidden" name="groupCode" value="CL-002">
                                                <input type="hidden" name="groupName" value="Other Liabilities">
                                            </td>
                                            <td style="width: 22.85%;">Loan from Directors</td>
                                            <td style="width: 5%;" class="text-center">OL-001</td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OL-001-opening-debit" id="OL-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OL-001-opening-credit" id="OL-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OL-001-movement-debit" id="OL-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OL-001-movement-credit" id="OL-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="OL-002">
                                                <input type="hidden" name="accountHead" value="Loan from Bank - Current Portion">
                                                <input type="hidden" name="groupCode" value="CL-002">
                                                <input type="hidden" name="groupName" value="Other Liabilities">
                                            </td>
                                            <td>Loan from Bank - Current Portion</td>
                                            <td class="text-center">OL-002</td>
                                            <td class="text-center"><input type="text" name="OL-002-opening-debit" id="OL-002-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OL-002-opening-credit" id="OL-002-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OL-002-movement-debit" id="OL-002-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OL-002-movement-credit" id="OL-002-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="OL-003">
                                                <input type="hidden" name="accountHead" value="Lease Liability - Current Portion">
                                                <input type="hidden" name="groupCode" value="CL-002">
                                                <input type="hidden" name="groupName" value="Other Liabilities">
                                            </td>
                                            <td>Lease Liability - Current Portion</td>
                                            <td class="text-center">OL-003</td>
                                            <td class="text-center"><input type="text" name="OL-003-opening-debit" id="OL-003-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OL-003-opening-credit" id="OL-003-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OL-003-movement-debit" id="OL-003-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OL-003-movement-credit" id="OL-003-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr data-group-code="CL-002" class="parent">
                        <td>
                            <a href="#" class="btn btn-sm toggle-arrow" data-group-id="CL-002">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                        <td>
                            <strong>Other Liabilities</strong>
                            <input type="hidden" name="groupCode" value="CL-002">
                        </td>
                        <td><strong>Other Liabilities</strong></td>
                        <td class="text-center">CL-002</td>
                        <td class="text-center"><input type="text" name="CL-002-opening-debit" id="CL-002-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CL-002-opening-credit" id="CL-002-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CL-002-movement-debit" id="CL-002-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="CL-002-movement-credit" id="CL-002-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                    </tr>
                    <tr class="sub-table-row" style="display: none;" data-group-id="S-001">
                        <td colspan="10" class="sub-table-cell p-0">
                            <div class="sub-table-wrapper table-responsive" style="">
                                <table class="table table-striped table-bordered table-hover table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td style="width: 2.45%;"></td>
                                            <td style="width: 24.85%;">
                                                Selling Expenses (Freight Outward, Brokerage, Commission, Discount, Etc.)
                                                <input type="hidden" name="accountCode" value="GR-001">
                                                <input type="hidden" name="accountHead" value="Selling Expenss (Freight Outward, Brokerage, Commission, Discount, Etc.)">
                                                <input type="hidden" name="groupCode" value="S-001">
                                                <input type="hidden" name="groupName" value="Revenue">
                                            </td>
                                            <td style="width: 22.85%;">Selling Expenses</td>
                                            <td style="width: 5%;" class="text-center">GR-001</td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="GR-001-opening-debit" id="GR-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="GR-001-opening-credit" id="GR-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="GR-001-movement-debit" id="GR-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="GR-001-movement-credit" id="GR-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr data-group-code="S-001" class="parent">
                        <td>
                            <a href="#" class="btn btn-sm toggle-arrow" data-group-id="S-001">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                        <td>
                            <strong>Gross Revenue (Excluding Sales Tax, Federal Excise)</strong>
                            <input type="hidden" name="groupCode" value="S-001">
                        </td>
                        <td><strong>Revenue</strong></td>
                        <td class="text-center">S-001</td>
                        <td class="text-center"><input type="text" name="S-001-opening-debit" id="S-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="S-001-opening-credit" id="S-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="S-001-movement-debit" id="S-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="S-001-movement-credit" id="S-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                    </tr>
                    <tr class="sub-table-row" style="display: none;" data-group-id="COS-001">
                        <td colspan="10" class="sub-table-cell p-0">
                            <div class="sub-table-wrapper table-responsive" style="">
                                <table class="table table-striped table-bordered table-hover table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td style="width: 2.45%;"></td>
                                            <td style="width: 24.85%;">
                                                <input type="hidden" name="accountCode" value="PR-001">
                                                <input type="hidden" name="accountHead" value="Purchases">
                                                <input type="hidden" name="groupCode" value="COS-001">
                                                <input type="hidden" name="groupName" value="Purchases">
                                            </td>
                                            <td style="width: 22.85%;">Purchases</td>
                                            <td style="width: 5%;" class="text-center">PR-001</td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="PR-001-opening-debit" id="PR-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="PR-001-opening-credit" id="PR-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="PR-001-movement-debit" id="PR-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="PR-001-movement-credit" id="PR-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 2.45%;"></td>
                                            <td style="width: 24.85%;">
                                                <input type="hidden" name="accountCode" value="PR-002">
                                                <input type="hidden" name="accountHead" value="Opening Stock">
                                                <input type="hidden" name="groupCode" value="COS-001">
                                                <input type="hidden" name="groupName" value="Purchases">
                                            </td>
                                            <td style="width: 22.85%;">Opening Stock</td>
                                            <td style="width: 5%;" class="text-center">PR-002</td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="PR-002-opening-debit" id="PR-002-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="PR-002-opening-credit" id="PR-002-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="PR-002-movement-debit" id="PR-002-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="PR-002-movement-credit" id="PR-002-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="accountCode" value="PR-003">
                                                <input type="hidden" name="accountHead" value="Closing Stock">
                                                <input type="hidden" name="groupCode" value="COS-001">
                                                <input type="hidden" name="groupName" value="Purchases">
                                            </td>
                                            <td>Closing Stock</td>
                                            <td class="text-center">PR-003</td>
                                            <td class="text-center"><input type="text" name="PR-003-opening-debit" id="PR-003-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="PR-003-opening-credit" id="PR-003-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="PR-003-movement-debit" id="PR-003-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="PR-003-movement-credit" id="PR-003-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>

                                        <tr>
                                            <td style="width: 2.45%;"></td>
                                            <td style="width: 24.85%;">
                                                Salaries / Wages
                                                <input type="hidden" name="accountCode" value="OE-001">
                                                <input type="hidden" name="accountHead" value="Salaries, Wages and Benefits">
                                                <input type="hidden" name="groupCode" value="COS-001">
                                                <input type="hidden" name="groupName" value="Cost of Sales">
                                            </td>
                                            <td style="width: 22.85%;">Salaries, Wages and Benefits</td>
                                            <td style="width: 5%;" class="text-center">OE-001</td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OE-001-opening-debit" id="OE-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OE-001-opening-credit" id="OE-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OE-001-movement-debit" id="OE-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OE-001-movement-credit" id="OE-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Fuel
                                                <input type="hidden" name="accountCode" value="OE-002">
                                                <input type="hidden" name="accountHead" value="Fuel Expenses">
                                                <input type="hidden" name="groupCode" value="COS-001">
                                                <input type="hidden" name="groupName" value="Cost of Sales">
                                            </td>
                                            <td>Fuel Expenses</td>
                                            <td class="text-center">OE-002</td>
                                            <td class="text-center"><input type="text" name="OE-002-opening-debit" id="OE-002-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-002-opening-credit" id="OE-002-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-002-movement-debit" id="OE-002-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-002-movement-credit" id="OE-002-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Power
                                                <input type="hidden" name="accountCode" value="OE-003">
                                                <input type="hidden" name="accountHead" value="Electricity Expenses">
                                                <input type="hidden" name="groupCode" value="COS-001">
                                                <input type="hidden" name="groupName" value="Cost of Sales">
                                            </td>
                                            <td>Electricity Expenses</td>
                                            <td class="text-center">OE-003</td>
                                            <td class="text-center"><input type="text" name="OE-003-opening-debit" id="OE-003-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-003-opening-credit" id="OE-003-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-003-movement-debit" id="OE-003-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-003-movement-credit" id="OE-003-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Gas
                                                <input type="hidden" name="accountCode" value="OE-004">
                                                <input type="hidden" name="accountHead" value="Gas Expenses">
                                                <input type="hidden" name="groupCode" value="COS-001">
                                                <input type="hidden" name="groupName" value="Cost of Sales">
                                            </td>
                                            <td>Gas Expenses</td>
                                            <td class="text-center">OE-004</td>
                                            <td class="text-center"><input type="text" name="OE-004-opening-debit" id="OE-004-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-004-opening-credit" id="OE-004-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-004-movement-debit" id="OE-004-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-004-movement-credit" id="OE-004-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Stores / Spares
                                                <input type="hidden" name="accountCode" value="OE-005">
                                                <input type="hidden" name="accountHead" value="Stores / Spares">
                                                <input type="hidden" name="groupCode" value="COS-001">
                                                <input type="hidden" name="groupName" value="Cost of Sales">
                                            </td>
                                            <td>Stores / Spares</td>
                                            <td class="text-center">OE-005</td>
                                            <td class="text-center"><input type="text" name="OE-005-opening-debit" id="OE-005-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-005-opening-credit" id="OE-005-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-005-movement-debit" id="OE-005-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-005-movement-credit" id="OE-005-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Repair / Maintenance
                                                <input type="hidden" name="accountCode" value="OE-006">
                                                <input type="hidden" name="accountHead" value="Repair and Maintenance">
                                                <input type="hidden" name="groupCode" value="COS-001">
                                                <input type="hidden" name="groupName" value="Cost of Sales">
                                            </td>
                                            <td>Repair and Maintenance</td>
                                            <td class="text-center">OE-006</td>
                                            <td class="text-center"><input type="text" name="OE-006-opening-debit" id="OE-006-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-006-opening-credit" id="OE-006-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-006-movement-debit" id="OE-006-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-006-movement-credit" id="OE-006-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Other Direct Expenses
                                                <input type="hidden" name="accountCode" value="OE-007">
                                                <input type="hidden" name="accountHead" value="Miscellaneous Expenses">
                                                <input type="hidden" name="groupCode" value="COS-001">
                                                <input type="hidden" name="groupName" value="Cost of Sales">
                                            </td>
                                            <td>Miscellaneous Expenses</td>
                                            <td class="text-center">OE-007</td>
                                            <td class="text-center"><input type="text" name="OE-007-opening-debit" id="OE-007-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-007-opening-credit" id="OE-007-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-007-movement-debit" id="OE-007-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-007-movement-credit" id="OE-007-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Accounting Amortization
                                                <input type="hidden" name="accountCode" value="OE-008">
                                                <input type="hidden" name="accountHead" value="Amortization">
                                                <input type="hidden" name="groupCode" value="COS-001">
                                                <input type="hidden" name="groupName" value="Cost of Sales">
                                            </td>
                                            <td>Amortization</td>
                                            <td class="text-center">OE-008</td>
                                            <td class="text-center"><input type="text" name="OE-008-opening-debit" id="OE-008-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-008-opening-credit" id="OE-008-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-008-movement-debit" id="OE-008-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-008-movement-credit" id="OE-008-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Accounting Depreciation
                                                <input type="hidden" name="accountCode" value="OE-009">
                                                <input type="hidden" name="accountHead" value="Depreciation">
                                                <input type="hidden" name="groupCode" value="COS-001">
                                                <input type="hidden" name="groupName" value="Cost of Sales">
                                            </td>
                                            <td>Depreciation</td>
                                            <td class="text-center">OE-009</td>
                                            <td class="text-center"><input type="text" name="OE-009-opening-debit" id="OE-009-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-009-opening-credit" id="OE-009-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-009-movement-debit" id="OE-009-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OE-009-movement-credit" id="OE-009-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr data-group-code="COS-001" class="parent">
                        <td>
                            <a href="#" class="btn btn-sm toggle-arrow" data-group-id="COS-001">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                        <td>
                            <strong>Cost of Sales/ Services</strong>
                            <input type="hidden" name="accountCode" value="COS-001">
                            <input type="hidden" name="accountHead" value="Cost of Sales">
                            <input type="hidden" name="groupCode" value="COS-001">
                            <input type="hidden" name="groupName" value="Cost of Sales">
                        </td>
                        <td><strong>Cost of Sales</strong></td>
                        <td class="text-center">COS-001</td>
                        <td class="text-center"><input type="text" name="COS-001-opening-debit" id="COS-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="COS-001-opening-credit" id="COS-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="COS-001-movement-debit" id="COS-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="COS-001-movement-credit" id="COS-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                    </tr>
                    <tr class="sub-table-row" style="display: none;" data-group-id="OI-001">
                        <td colspan="10" class="sub-table-cell p-0">
                            <div class="sub-table-wrapper table-responsive" style="">
                                <table class="table table-striped table-bordered table-hover table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td style="width: 2.45%;"></td>
                                            <td style="width: 24.85%;">
                                                Fee for Technical / Professional Services
                                                <input type="hidden" name="accountCode" value="OR-001">
                                                <input type="hidden" name="accountHead" value="Other Income">
                                                <input type="hidden" name="groupCode" value="OI-001">
                                                <input type="hidden" name="groupName" value="Other Income">
                                            </td>
                                            <td style="width: 22.85%;">Other Income</td>
                                            <td style="width: 5%;" class="text-center">OR-001</td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OR-001-opening-debit" id="OR-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OR-001-opening-credit" id="OR-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OR-001-movement-debit" id="OR-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="OR-001-movement-credit" id="OR-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Accounting Gain on Sale of Intangibles
                                                <input type="hidden" name="accountCode" value="OR-002">
                                                <input type="hidden" name="accountHead" value="Gain on Sale of Intangibles">
                                                <input type="hidden" name="groupCode" value="OI-001">
                                                <input type="hidden" name="groupName" value="Other Income">
                                            </td>
                                            <td>Gain on Sale of Intangibles</td>
                                            <td class="text-center">OR-002</td>
                                            <td class="text-center"><input type="text" name="OR-002-opening-debit" id="OR-002-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-002-opening-credit" id="OR-002-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-002-movement-debit" id="OR-002-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-002-movement-credit" id="OR-002-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Accounting Gain on Sale of Assets
                                                <input type="hidden" name="accountCode" value="OR-003">
                                                <input type="hidden" name="accountHead" value="Gain on Sale of Assets">
                                                <input type="hidden" name="groupCode" value="OI-001">
                                                <input type="hidden" name="groupName" value="Other Income">
                                            </td>
                                            <td>Gain on Sale of Assets</td>
                                            <td class="text-center">OR-003</td>
                                            <td class="text-center"><input type="text" name="OR-003-opening-debit" id="OR-003-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-003-opening-credit" id="OR-003-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-003-movement-debit" id="OR-003-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-003-movement-credit" id="OR-003-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Others
                                                <input type="hidden" name="accountCode" value="OR-004">
                                                <input type="hidden" name="accountHead" value="Profit on Bank">
                                                <input type="hidden" name="groupCode" value="OI-001">
                                                <input type="hidden" name="groupName" value="Other Income">
                                            </td>
                                            <td>Profit on Bank</td>
                                            <td class="text-center">OR-004</td>
                                            <td class="text-center"><input type="text" name="OR-004-opening-debit" id="OR-004-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-004-opening-credit" id="OR-004-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-004-movement-debit" id="OR-004-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-004-movement-credit" id="OR-004-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Share in Untaxed Income from AOP
                                                <input type="hidden" name="accountCode" value="OR-005">
                                                <input type="hidden" name="accountHead" value="Share in Untaxed Income from AOP">
                                                <input type="hidden" name="groupCode" value="OI-001">
                                                <input type="hidden" name="groupName" value="Other Income">
                                            </td>
                                            <td>Share in Untaxed Income from AOP</td>
                                            <td class="text-center">OR-005</td>
                                            <td class="text-center"><input type="text" name="OR-005-opening-debit" id="OR-005-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-005-opening-credit" id="OR-005-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-005-movement-debit" id="OR-005-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-005-movement-credit" id="OR-005-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Share in Taxed Income from AOP
                                                <input type="hidden" name="accountCode" value="OR-006">
                                                <input type="hidden" name="accountHead" value="Share in Taxed Income from AOP">
                                                <input type="hidden" name="groupCode" value="OI-001">
                                                <input type="hidden" name="groupName" value="Other Income">
                                            </td>
                                            <td>Share in Taxed Income from AOP</td>
                                            <td class="text-center">OR-006</td>
                                            <td class="text-center"><input type="text" name="OR-006-opening-debit" id="OR-006-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-006-opening-credit" id="OR-006-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-006-movement-debit" id="OR-006-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="OR-006-movement-credit" id="OR-006-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr data-group-code="OI-001" class="parent">
                        <td>
                            <a href="#" class="btn btn-sm toggle-arrow" data-group-id="OI-001">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                        <td>
                            <strong>Other Revenue</strong>
                            <input type="hidden" name="groupCode" value="OI-001">
                        </td>
                        <td><strong>Other Income</strong></td>
                        <td class="text-center">OI-001</td>
                        <td class="text-center"><input type="text" name="OI-001-opening-debit" id="OI-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="OI-001-opening-credit" id="OI-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="OI-001-movement-debit" id="OI-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="OI-001-movement-credit" id="OI-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                    </tr>
                    <tr class="sub-table-row" style="display: none;" data-group-id="EX-001">
                        <td colspan="10" class="sub-table-cell p-0">
                            <div class="sub-table-wrapper table-responsive" style="">
                                <table class="table table-striped table-bordered table-hover table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td style="width: 2.45%;"></td>
                                            <td style="width: 24.85%;">
                                                Rent
                                                <input type="hidden" name="accountCode" value="MASFE-001">
                                                <input type="hidden" name="accountHead" value="Rent Expenses">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td style="width: 22.85%;">Rent Expenses</td>
                                            <td style="width: 5%;" class="text-center">MASFE-001</td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="MASFE-001-opening-debit" id="MASFE-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="MASFE-001-opening-credit" id="MASFE-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="MASFE-001-movement-debit" id="MASFE-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center"><input type="text" name="MASFE-001-movement-credit" id="MASFE-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                            <td style="width: 7.5%;" class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Rates / Taxes / Cess
                                                <input type="hidden" name="accountCode" value="MASFE-002">
                                                <input type="hidden" name="accountHead" value="Tax Expenses">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Tax Expenses</td>
                                            <td class="text-center">MASFE-002</td>
                                            <td class="text-center"><input type="text" name="MASFE-002-opening-debit" id="MASFE-002-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-002-opening-credit" id="MASFE-002-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-002-movement-debit" id="MASFE-002-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-002-movement-credit" id="MASFE-002-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Salaries / Wages / Perquisties / Benefits
                                                <input type="hidden" name="accountCode" value="MASFE-003">
                                                <input type="hidden" name="accountHead" value="Salaries, Wages and Benefits">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Salaries, Wages and Benefits</td>
                                            <td class="text-center">MASFE-003</td>
                                            <td class="text-center"><input type="text" name="MASFE-003-opening-debit" id="MASFE-003-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-003-opening-credit" id="MASFE-003-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-003-movement-debit" id="MASFE-003-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-003-movement-credit" id="MASFE-003-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Traveling / Conveyance / Vehicles Running / Maintenance
                                                <input type="hidden" name="accountCode" value="MASFE-004">
                                                <input type="hidden" name="accountHead" value="Traveling and Conveyance">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Traveling and Conveyance</td>
                                            <td class="text-center">MASFE-004</td>
                                            <td class="text-center"><input type="text" name="MASFE-004-opening-debit" id="MASFE-004-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-004-opening-credit" id="MASFE-004-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-004-movement-debit" id="MASFE-004-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-004-movement-credit" id="MASFE-004-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Electricity / Water / Gas
                                                <input type="hidden" name="accountCode" value="MASFE-005">
                                                <input type="hidden" name="accountHead" value="Utilities">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Utilities</td>
                                            <td class="text-center">MASFE-005</td>
                                            <td class="text-center"><input type="text" name="MASFE-005-opening-debit" id="MASFE-005-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-005-opening-credit" id="MASFE-005-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-005-movement-debit" id="MASFE-005-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-005-movement-credit" id="MASFE-005-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Communication
                                                <input type="hidden" name="accountCode" value="MASFE-006">
                                                <input type="hidden" name="accountHead" value="Communication Expense">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Communication Expense</td>
                                            <td class="text-center">MASFE-006</td>
                                            <td class="text-center"><input type="text" name="MASFE-006-opening-debit" id="MASFE-006-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-006-opening-credit" id="MASFE-006-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-006-movement-debit" id="MASFE-006-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-006-movement-credit" id="MASFE-006-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Repair / Maintenance
                                                <input type="hidden" name="accountCode" value="MASFE-007">
                                                <input type="hidden" name="accountHead" value="Repair and Maintenance">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Repair and Maintenance</td>
                                            <td class="text-center">MASFE-007</td>
                                            <td class="text-center"><input type="text" name="MASFE-007-opening-debit" id="MASFE-007-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-007-opening-credit" id="MASFE-007-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-007-movement-debit" id="MASFE-007-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-007-movement-credit" id="MASFE-007-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Stationery / Printing / Photocopies / Office Supplies
                                                <input type="hidden" name="accountCode" value="MASFE-008">
                                                <input type="hidden" name="accountHead" value="Printing and Stationery">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Printing and Stationery</td>
                                            <td class="text-center">MASFE-008</td>
                                            <td class="text-center"><input type="text" name="MASFE-008-opening-debit" id="MASFE-008-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-008-opening-credit" id="MASFE-008-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-008-movement-debit" id="MASFE-008-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-008-movement-credit" id="MASFE-008-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Advertisement / Publicity / Promotion
                                                <input type="hidden" name="accountCode" value="MASFE-009">
                                                <input type="hidden" name="accountHead" value="Advertisement Expenses">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Advertisement Expenses</td>
                                            <td class="text-center">MASFE-009</td>
                                            <td class="text-center"><input type="text" name="MASFE-009-opening-debit" id="MASFE-009-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-009-opening-credit" id="MASFE-009-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-009-movement-debit" id="MASFE-009-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-009-movement-credit" id="MASFE-009-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Insurance
                                                <input type="hidden" name="accountCode" value="MASFE-010">
                                                <input type="hidden" name="accountHead" value="Insurance Expenses">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Insurance Expenses</td>
                                            <td class="text-center">MASFE-010</td>
                                            <td class="text-center"><input type="text" name="MASFE-010-opening-debit" id="MASFE-010-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-010-opening-credit" id="MASFE-010-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-010-movement-debit" id="MASFE-010-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-010-movement-credit" id="MASFE-010-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Professional Charges
                                                <input type="hidden" name="accountCode" value="MASFE-011">
                                                <input type="hidden" name="accountHead" value="Legal and Professional">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Legal and Professional</td>
                                            <td class="text-center">MASFE-011</td>
                                            <td class="text-center"><input type="text" name="MASFE-011-opening-debit" id="MASFE-011-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-011-opening-credit" id="MASFE-011-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-011-movement-debit" id="MASFE-011-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-011-movement-credit" id="MASFE-011-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Donation / Charity
                                                <input type="hidden" name="accountCode" value="MASFE-012">
                                                <input type="hidden" name="accountHead" value="Donation and Charity">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Donation and Charity</td>
                                            <td class="text-center">MASFE-012</td>
                                            <td class="text-center"><input type="text" name="MASFE-012-opening-debit" id="MASFE-012-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-012-opening-credit" id="MASFE-012-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-012-movement-debit" id="MASFE-012-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-012-movement-credit" id="MASFE-012-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Brokerage / Commission
                                                <input type="hidden" name="accountCode" value="MASFE-013">
                                                <input type="hidden" name="accountHead" value="Commission Expenses">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Commission Expenses</td>
                                            <td class="text-center">MASFE-013</td>
                                            <td class="text-center"><input type="text" name="MASFE-013-opening-debit" id="MASFE-013-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-013-opening-credit" id="MASFE-013-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-013-movement-debit" id="MASFE-013-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-013-movement-credit" id="MASFE-013-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Other Indirect Expenses
                                                <input type="hidden" name="accountCode" value="MASFE-014">
                                                <input type="hidden" name="accountHead" value="Miscellaneous / Entertainment">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Miscellaneous / Entertainment</td>
                                            <td class="text-center">MASFE-014</td>
                                            <td class="text-center"><input type="text" name="MASFE-014-opening-debit" id="MASFE-014-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-014-opening-credit" id="MASFE-014-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-014-movement-debit" id="MASFE-014-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-014-movement-credit" id="MASFE-014-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Irrecoverable Debts Written Off
                                                <input type="hidden" name="accountCode" value="MASFE-015">
                                                <input type="hidden" name="accountHead" value="Bad Debts Written Off">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Bad Debts Written Off</td>
                                            <td class="text-center">MASFE-015</td>
                                            <td class="text-center"><input type="text" name="MASFE-015-opening-debit" id="MASFE-015-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-015-opening-credit" id="MASFE-015-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-015-movement-debit" id="MASFE-015-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-015-movement-credit" id="MASFE-015-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Obsolete Stocks / Stores / Spares / Fixed Assets Written Off
                                                <input type="hidden" name="accountCode" value="MASFE-016">
                                                <input type="hidden" name="accountHead" value="Assets Written Off">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Assets Written Off</td>
                                            <td class="text-center">MASFE-016</td>
                                            <td class="text-center"><input type="text" name="MASFE-016-opening-debit" id="MASFE-016-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-016-opening-credit" id="MASFE-016-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-016-movement-debit" id="MASFE-016-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-016-movement-credit" id="MASFE-016-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Accounting (Loss) On Sale of Intangibles
                                                <input type="hidden" name="accountCode" value="MASFE-017">
                                                <input type="hidden" name="accountHead" value="Loss On Sale of Intangibles">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Loss On Sale of Intangibles</td>
                                            <td class="text-center">MASFE-017</td>
                                            <td class="text-center"><input type="text" name="MASFE-017-opening-debit" id="MASFE-017-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-017-opening-credit" id="MASFE-017-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-017-movement-debit" id="MASFE-017-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-017-movement-credit" id="MASFE-017-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Contribution to an Approved Gratuity Fund / Pension Fund / Superannuation Fund
                                                <input type="hidden" name="accountCode" value="MASFE-018">
                                                <input type="hidden" name="accountHead" value="Contribution to an Approved Gratuity Fund / Pension Fund / Superannuation Fund">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Contribution to an Approved Gratuity Fund / Pension Fund / Superannuation Fund</td>
                                            <td class="text-center">MASFE-018</td>
                                            <td class="text-center"><input type="text" name="MASFE-018-opening-debit" id="MASFE-018-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-018-opening-credit" id="MASFE-018-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-018-movement-debit" id="MASFE-018-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-018-movement-credit" id="MASFE-018-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Accounting (Loss) On Sale of Assets
                                                <input type="hidden" name="accountCode" value="MASFE-019">
                                                <input type="hidden" name="accountHead" value="Loss On Sale of Assets">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Loss On Sale of Assets</td>
                                            <td class="text-center">MASFE-019</td>
                                            <td class="text-center"><input type="text" name="MASFE-019-opening-debit" id="MASFE-019-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-019-opening-credit" id="MASFE-019-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-019-movement-debit" id="MASFE-019-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-019-movement-credit" id="MASFE-019-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Accounting Amortization
                                                <input type="hidden" name="accountCode" value="MASFE-020">
                                                <input type="hidden" name="accountHead" value="Amortization">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Amortization</td>
                                            <td class="text-center">MASFE-020</td>
                                            <td class="text-center"><input type="text" name="MASFE-020-opening-debit" id="MASFE-020-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-020-opening-credit" id="MASFE-020-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-020-movement-debit" id="MASFE-020-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-020-movement-credit" id="MASFE-020-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                Accounting Depreciation
                                                <input type="hidden" name="accountCode" value="MASFE-021">
                                                <input type="hidden" name="accountHead" value="Depreciation">
                                                <input type="hidden" name="groupCode" value="EX-001">
                                                <input type="hidden" name="groupName" value="Administrative and General Expenses">
                                            </td>
                                            <td>Depreciation</td>
                                            <td class="text-center">MASFE-021</td>
                                            <td class="text-center"><input type="text" name="MASFE-021-opening-debit" id="MASFE-021-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-021-opening-credit" id="MASFE-021-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-021-movement-debit" id="MASFE-021-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center"><input type="text" name="MASFE-021-movement-credit" id="MASFE-021-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr data-group-code="EX-001" class="parent">
                        <td>
                            <a href="#" class="btn btn-sm toggle-arrow" data-group-id="EX-001">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                        <td>
                            <strong>Management, Administrative, Selling & Financial Expenses</strong>
                            <input type="hidden" name="groupCode" value="EX-001">
                        </td>
                        <td><strong>Administrative and General Expenses</strong></td>
                        <td class="text-center">EX-001</td>
                        <td class="text-center"><input type="text" name="EX-001-opening-debit" id="EX-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="EX-001-opening-credit" id="EX-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="EX-001-movement-debit" id="EX-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="EX-001-movement-credit" id="EX-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                    </tr>
                    <tr data-group-code="FC-001" class="parent">
                        <td></td>
                        <td>
                            <strong>Profit On Debt (Financial Charges / Markup / Interest)</strong>
                            <input type="hidden" name="accountCode" value="FC-001">
                            <input type="hidden" name="accountHead" value="Profit On Debt (Financial Charges / Markup / Interest)">
                            <input type="hidden" name="groupCode" value="FC-001">
                            <input type="hidden" name="groupName" value="Financial Charges">
                        </td>
                        <td><strong>Financial Charges</strong></td>
                        <td class="text-center">FC-001</td>
                        <td class="text-center"><input type="text" name="FC-001-opening-debit" id="FC-001-opening-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="FC-001-opening-credit" id="FC-001-opening-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="FC-001-movement-debit" id="FC-001-movement-debit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center"><input type="text" name="FC-001-movement-credit" id="FC-001-movement-credit" value="0" class="form-control form-control-sm bg-transparent border-0 text-center editable"></td>
                        <td class="text-center">0</td>
                        <td class="text-center">0</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right"><strong>Totals</strong></th>
                    <th class="text-center" id="tfoot-opening-debit">0.00</th>
                    <th class="text-center" id="tfoot-opening-credit">0.00</th>
                    <th class="text-center" id="tfoot-movement-debit">0.00</th>
                    <th class="text-center" id="tfoot-movement-credit">0.00</th>
                    <th class="text-center" id="tfoot-closing-debit">0.00</th>
                    <th class="text-center" id="tfoot-closing-credit">0.00</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <button class="btn btn-primary mb-3" id="saveTrailBalanceBtn">Save</button>
@endsection

@push('scripts')
    <script>
        $(function() {

            /*var trailBalance = [
                [
                    "groupCode": "CA-001",
                    "groupName": "Advances / Deposits / Prepayments",
                    "classification": "Advances / Deposits / Prepayments",
                    "children": [
                        [
                            "accountCode": "ADP-001",
                            "accountHead": "Advance Tax",
                            "groupCode": "CA-001",
                            "groupName": "Advances / Deposits / Prepayments",
                            "notesClassification": "Advance Tax",
                        ],
                        [
                            "accountCode": "ADP-002",
                            "accountHead": "Sales Tax Refundable",
                            "groupCode": "CA-001",
                            "groupName": "Advances / Deposits / Prepayments",
                            "notesClassification": "Sales Tax Refundable",
                        ],
                        [
                            "accountCode": "ADP-003",
                            "accountHead": "Advances To Employees",
                            "groupCode": "CA-001",
                            "groupName": "Advances / Deposits / Prepayments",
                            "notesClassification": "Advances To Employees",
                        ],
                        [
                            "accountCode": "ADP-004",
                            "accountHead": "Advance To Vendors",
                            "groupCode": "CA-001",
                            "groupName": "Advances / Deposits / Prepayments",
                            "notesClassification": "Advance To Vendors",
                        ],
                        [
                            "accountCode": "ADP-005",
                            "accountHead": "Trade Debtors",
                            "groupCode": "CA-001",
                            "groupName": "Advances / Deposits / Prepayments",
                            "notesClassification": "Trade Debtors",
                        ]
                    ]
                ],
                [
                    "groupCode": "CA-002",
                    "groupName": "Cash / Cash Equivalents",
                    "classification": "Cash / Cash Equivalents",
                    "children": [
                        [
                            "accountCode": "CCE-001",
                            "accountHead": "Cash in Hand",
                            "groupCode": "CA-002",
                            "groupName": "Cash / Cash Equivalents",
                            "notesClassification": "Cash in Hand",
                        ],
                        [
                            "accountCode": "CCE-002",
                            "accountHead": "Cash at Bank - Current",
                            "groupCode": "CA-002",
                            "groupName": "Cash / Cash Equivalents",
                            "notesClassification": "Cash at Bank - Current",
                        ],
                        [
                            "accountCode": "CCE-003",
                            "accountHead": "Cash at Bank - Savings",
                            "groupCode": "CA-002",
                            "groupName": "Cash / Cash Equivalents",
                            "notesClassification": "Cast at Bank - Savings",
                        ]
                    ]
                ],
                [
                    "groupCode": "CA-003",
                    "groupName": "Other Assets",
                    "classification": "Other Assets",
                    "children": [
                        [
                            "accountCode": "OA-001",
                            "accountHead": "Investments in Other Businesses",
                            "groupCode": "CA-003",
                            "groupName": "Other Assets",
                            "notesClassification": "Investments in Other Businesses",
                        ],
                        [
                            "accountCode": "OA-002",
                            "accountHead": "Security Deposits",
                            "groupCode": "CA-003",
                            "groupName": "Other Assets",
                            "notesClassification": "Security Deposits",
                        ]
                    ]
                ],
                [
                    "groupCode": "EQ-001",
                    "groupName": "Capital",
                    "classification": "Capital",
                    "children": [
                        [
                            "accountCode": "CAP-001",
                            "accountHead": "Opening Capital",
                            "groupCode": "EQ-001",
                            "groupName": "Capital",
                            "notesClassification": "Opening Capital",
                        ],
                        [
                            "accountCode": "CAP-002",
                            "accountHead": "Profit for the Year",
                            "groupCode": "EQ-001",
                            "groupName": "Capital",
                            "notesClassification": "Profit for the Year",
                        ],
                        [
                            "accountCode": "CAP-003",
                            "accountHead": "Capital Injection",
                            "groupCode": "EQ-001",
                            "groupName": "Capital",
                            "notesClassification": "Capital Injection",
                        ],
                        [
                            "accountCode": "CAP-004",
                            "accountHead": "Drawings",
                            "groupCode": "EQ-001",
                            "groupName": "Capital",
                            "notesClassification": "Drawings",
                        ]
                    ]
                ],
                [
                    "groupCode": "NCL-001",
                    "groupName": "Long Term Borrowings / Debt / Loan",
                    "classification": "Non Current Liabilities",
                    "children": [
                        [
                            "accountCode": "LTBDL-001",
                            "accountHead": "Loan from Directors",
                            "groupCode": "NCL-001",
                            "groupName": "Non Current Liabilities",
                            "notesClassification": "Loan from Directors",
                        ],
                        [
                            "accountCode": "LTBDL-002",
                            "accountHead": "Loan from Bank - Non Current Portion",
                            "groupCode": "NCL-001",
                            "groupName": "Non Current Liabilities",
                            "notesClassification": "Loan from Bank - Non Current Portion",
                        ],
                        [
                            "accountCode": "LTBDL-003",
                            "accountHead": "Lease Liability - Non Current Portion",
                            "groupCode": "NCL-001",
                            "groupName": "Non Current Liabilities",
                            "notesClassification": "Lease Liability - Non Current Portion",
                        ]
                    ]
                ],
                [
                    "groupCode": "CL-001",
                    "groupName": "Trade Creditors / Payables",
                    "classification": "Trade Creditors and other Payables -Current Liabilities",
                    "children": [
                        [
                            "accountCode": "TCP-001",
                            "accountHead": "Trade Creditors",
                            "groupCode": "CL-001",
                            "groupName": "Trade Creditors and other Payables -Current Liabilities",
                        ],
                        [
                            "accountCode": "TCP-002",
                            "accountHead": "Accrued Liabilities",
                            "groupCode": "CL-001",
                            "groupName": "Trade Creditors and other Payables -Current Liabilities",
                            "notesClassification": "Accrued Liabilities",
                        ],
                        [
                            "accountCode": "TCP-003",
                            "accountHead": "Advances from Customers",
                            "groupCode": "CL-001",
                            "groupName": "Trade Creditors and other Payables -Current Liabilities",
                            "notesClassification": "Advances from Customers",
                        ],
                        [
                            "accountCode": "TCP-004",
                            "accountHead": "Sales Tax Payable",
                            "groupCode": "CL-001",
                            "groupName": "Trade Creditors and other Payables -Current Liabilities",
                            "notesClassification": "Sales Tax Payable",
                        ],
                        [
                            "accountCode": "TCP-005",
                            "accountHead": "Provision for Tax",
                            "groupCode": "CL-001",
                            "groupName": "Trade Creditors and other Payables -Current Liabilities",
                            "notesClassification": "Provision for Tax",
                        ]
                    ]
                ],
                [
                    "groupCode": "CL-002",
                    "groupName": "Other Liabilities",
                    "classification": "Other Liabilities",
                    "children": [
                        [
                            "accountCode": "OL-001",
                            "accountHead": "Loan from Directors",
                            "groupCode": "CL-002",
                            "groupName": "Other Liabilities",
                            "notesClassification": "Loan from Directors",
                        ],
                        [
                            "accountCode": "OL-002",
                            "accountHead": "Loan from Bank - Current Portion",
                            "groupCode": "CL-002",
                            "groupName": "Other Liabilities",
                            "notesClassification": "Loan from Bank - Current Portion",
                        ],
                        [
                            "accountCode": "OL-003",
                            "accountHead": "Lease Liability - Current Portion",
                            "groupCode": "CL-002",
                            "groupName": "Other Liabilities",
                            "notesClassification": "Lease Liability - Current Portion",
                        ]
                    ]
                ],
                [
                    "groupCode": "S-001",
                    "groupName": "Gross Revenue (Excluding Sales Tax, Federal Excise)",
                    "classification": "Revenue",
                    "children": [
                        [
                            "accountCode": "GR-001",
                            "accountHead": "Selling Expenss (Freight Outward, Brokerage, Commission, Discount, Etc.)",
                            "groupCode": "S-001",
                            "groupName": "Revenue",
                            "notesClassification": "Selling Expenses",
                        ]
                    ]
                ],
                [
                    "groupCode": "COS-001",
                    "groupName": "Cost of Sales/ Services",
                    "classification": "Cost of Sales",
                    "children": [
                        [
                            "accountCode": "PR-001",
                            "accountHead": "Purchases",
                            "groupCode": "COS-001",
                            "groupName": "Purchases",
                            "notesClassification": "Purchases",
                        ],
                        [
                            "accountCode": "PR-002",
                            "accountHead": "Opening Stock",
                            "groupCode": "COS-001",
                            "groupName": "Purchases",
                            "notesClassification": "Opening Stock",
                        ],
                        [
                            "accountCode": "PR-003",
                            "accountHead": "Closing Stock",
                            "groupCode": "COS-001",
                            "groupName": "Purchases",
                            "notesClassification": "Closing Stock",
                        ],
                        [
                            "accountCode": "OE-001",
                            "accountHead": "Salaries, Wages and Benefits",
                            "groupCode": "COS-001",
                            "groupName": "Cost of Sales",
                            "notesClassification": "Salaries, Wages and Benefits",
                        ],
                        [
                            "accountCode": "OE-002",
                            "accountHead": "Fuel Expenses",
                            "groupCode": "COS-001",
                            "groupName": "Cost of Sales",
                            "notesClassification": "Fuel Expenses",
                        ],
                        [
                            "accountCode": "OE-003",
                            "accountHead": "Electricity Expenses",
                            "groupCode": "COS-001",
                            "groupName": "Cost of Sales",
                            "notesClassification": "Electricity Expenses",
                        ],
                        [
                            "accountCode": "OE-004",
                            "accountHead": "Gas Expenses",
                            "groupCode": "COS-001",
                            "groupName": "Cost of Sales",
                            "notesClassification": "Gas Expenses",
                        ],
                        [
                            "accountCode": "OE-005",
                            "accountHead": "Stores / Spares",
                            "groupCode": "COS-001",
                            "groupName": "Cost of Sales",
                            "notesClassification": "Stores / Spares",
                        ],
                        [
                            "accountCode": "OE-006",
                            "accountHead": "Repair and Maintenance",
                            "groupCode": "COS-001",
                            "groupName": "Cost of Sales",
                            "notesClassification": "Repair and Maintenance",
                        ],
                        [
                            "accountCode": "OE-007",
                            "accountHead": "Miscellaneous Expenses",
                            "groupCode": "COS-001",
                            "groupName": "Cost of Sales",
                            "notesClassification": "Miscellaneous Expenses",
                        ],
                        [
                            "accountCode": "OE-008",
                            "accountHead": "Amortization",
                            "groupCode": "COS-001",
                            "groupName": "Cost of Sales",
                            "notesClassification": "Amortization",
                        ],
                        [
                            "accountCode": "OE-009",
                            "accountHead": "Depreciation",
                            "groupCode": "COS-001",
                            "groupName": "Cost of Sales",
                            "notesClassification": "Depreciation",
                        ]
                    ]
                ],
                [
                    "groupCode": "OI-001",
                    "groupName": "Other Revenue",
                    "classification": "Other Income",
                    "children": [
                        [
                            "accountCode": "OR-001",
                            "accountHead": "Other Income",
                            "groupCode": "OI-001",
                            "groupName": "Other Income",
                            "notesClassification": "Other Income",
                        ],
                        [
                            "accountCode": "OR-002",
                            "accountHead": "Gain on Sale of Intangibles",
                            "groupCode": "OI-001",
                            "groupName": "Other Income",
                            "notesClassification": "Gain on Sale of Intangibles",
                        ],
                        [
                            "accountCode": "OR-003",
                            "accountHead": "Gain on Sale of Assets",
                            "groupCode": "OI-001",
                            "groupName": "Other Income",
                            "notesClassification": "Gain on Sale of Assets",
                        ],
                        [
                            "accountCode": "OR-004",
                            "accountHead": "Profit on Bank",
                            "groupCode": "OI-001",
                            "groupName": "Other Income",
                            "notesClassification": "Profit on Bank",
                        ],
                        [
                            "accountCode": "OR-005",
                            "accountHead": "Share in Untaxed Income from AOP",
                            "groupCode": "OI-001",
                            "groupName": "Other Income",
                            "notesClassification": "Share in Untaxed Income from AOP",
                        ],
                        [
                            "accountCode": "OR-006",
                            "accountHead": "Share in Taxed Income from AOP",
                            "groupCode": "OI-001",
                            "groupName": "Other Income",
                            "notesClassification": "Share in Taxed Income from AOP",
                        ]
                    ]
                ],
                [
                    "groupCode": "EX-001",
                    "groupName": "Management, Administrative, Selling & Financial Expenses",
                    "classification": "Administrative and General Expenses",
                    "children": [
                        [
                            "accountCode": "MASFE-001",
                            "accountHead": "Rent Expenses",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Rent Expenses",
                        ],
                        [
                            "accountCode": "MASFE-002",
                            "accountHead": "Tax Expenses",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Tax Expenses",
                        ],
                        [
                            "accountCode": "MASFE-003",
                            "accountHead": "Salaries, Wages and Benefits",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Salaries, Wages and Benefits",
                        ],
                        [
                            "accountCode": "MASFE-004",
                            "accountHead": "Traveling and Conveyance",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Traveling and Conveyance",
                        ],
                        [
                            "accountCode": "MASFE-005",
                            "accountHead": "Utilities",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Utilities",
                        ],
                        [
                            "accountCode": "MASFE-006",
                            "accountHead": "Communication Expense",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Communication Expense",
                        ],
                        [
                            "accountCode": "MASFE-007",
                            "accountHead": "Repair and Maintenance",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Repair and Maintenance",
                        ],
                        [
                            "accountCode": "MASFE-008",
                            "accountHead": "Printing and Stationery",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Printing and Stationery",
                        ],
                        [
                            "accountCode": "MASFE-009",
                            "accountHead": "Advertisement Expenses",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Advertisement Expenses",
                        ],
                        [
                            "accountCode": "MASFE-010",
                            "accountHead": "Insurance Expenses",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Insurance Expenses",
                        ],
                        [
                            "accountCode": "MASFE-011",
                            "accountHead": "Legal and Professional",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Legal and Professional",
                        ],
                        [
                            "accountCode": "MASFE-012",
                            "accountHead": "Donation and Charity",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Donation and Charity",
                        ],
                        [
                            "accountCode": "MASFE-013",
                            "accountHead": "Commission Expenses",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Commission Expenses",
                        ],
                        [
                            "accountCode": "MASFE-014",
                            "accountHead": "Miscellaneous / Entertainment",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Miscellaneous / Entertainment",
                        ],
                        [
                            "accountCode": "MASFE-015",
                            "accountHead": "Bad Debts Written Off",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Bad Debts Written Off",
                        ],
                        [
                            "accountCode": "MASFE-016",
                            "accountHead": "Assets Written Off",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Assets Written Off",
                        ],
                        [
                            "accountCode": "MASFE-017",
                            "accountHead": "Loss On Sale of Intangibles",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Loss On Sale of Intangibles",
                        ],
                        [
                            "accountCode": "MASFE-018",
                            "accountHead": "Contribution to an Approved Gratuity Fund / Pension Fund / Superannuation Fund",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Contribution to an Approved Gratuity Fund / Pension Fund / Superannuation Fund",
                        ],
                        [
                            "accountCode": "MASFE-019",
                            "accountHead": "Loss On Sale of Assets",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Loss On Sale of Assets",
                        ],
                        [
                            "accountCode": "MASFE-020",
                            "accountHead": "Amortization",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Amortization",
                        ],
                        [
                            "accountCode": "MASFE-021",
                            "accountHead": "Depreciation",
                            "groupCode": "EX-001",
                            "groupName": "Administrative and General Expenses",
                            "notesClassification": "Depreciation",
                        ]
                    ]
                ],
                [
                    "groupCode": "FC-001",
                    "groupName": "Profit On Debt (Financial Charges / Markup / Interest)",
                    "classification": "Financial Charges",
                    "children": []
                ]
            ];*/

            $('#saveTrailBalanceBtn').on('click', function(e){
                e.preventDefault();

                var totalOpeningDebit = parseFloat($('#tfoot-opening-debit').text().replace(/,/g, '')) || 0;
                var totalOpeningCredit = parseFloat($('#tfoot-opening-credit').text().replace(/,/g, '')) || 0;
                var totalMovementDebit = parseFloat($('#tfoot-movement-debit').text().replace(/,/g, '')) || 0;
                var totalMovementCredit = parseFloat($('#tfoot-movement-credit').text().replace(/,/g, '')) || 0;
                var totalClosingDebit = parseFloat($('#tfoot-closing-debit').text().replace(/,/g, '')) || 0;
                var totalClosingCredit = parseFloat($('#tfoot-closing-credit').text().replace(/,/g, '')) || 0;

                if (totalOpeningDebit !== totalOpeningCredit) {
                    alert('Total Opening Debit should match with Total Opening Credit')
                    return false;
                }

                if (totalMovementDebit !== totalMovementCredit) {
                    alert('Total Movement Debit should match with Total Movement Credit');
                    return false;
                }

                if (totalClosingDebit !== totalClosingCredit) {
                    alert('Total Closing Debit should match with Total Closing Credit');
                    return false;
                }

                var entries = [];

                // Loop through all sub-table rows (child tables)
                $('.sub-table-row').each(function() {
                    var $subTable = $(this);

                    // Loop through each row in the child table
                    $subTable.find('table tbody tr').each(function() {
                        var $row = $(this);
                        var $cells = $row.find('> td');

                        // Get hidden input values
                        var accountCode = $row.find('input[name="accountCode"]').val();
                        var accountHead = $row.find('input[name="accountHead"]').val();
                        var groupCode = $row.find('input[name="groupCode"]').val();
                        var groupName = $row.find('input[name="groupName"]').val();

                        // Skip if no account code (empty row)
                        if (!accountCode) return;

                        // Get input values
                        var openingDebit = parseFloat($('#' + accountCode + '-opening-debit').val().replace(/,/g, '')) || 0;
                        var openingCredit = parseFloat($('#' + accountCode + '-opening-credit').val().replace(/,/g, '')) || 0;
                        var movementDebit = parseFloat($('#' + accountCode + '-movement-debit').val().replace(/,/g, '')) || 0;
                        var movementCredit = parseFloat($('#' + accountCode + '-movement-credit').val().replace(/,/g, '')) || 0;

                        // Get closing values from last 2 td cells
                        var totalCells = $cells.length;
                        var closingDebit = parseFloat($cells.eq(totalCells - 2).text().replace(/,/g, '')) || 0;
                        var closingCredit = parseFloat($cells.eq(totalCells - 1).text().replace(/,/g, '')) || 0;

                        entries.push({
                            accountCode: accountCode,
                            accountHead: accountHead,
                            groupCode: groupCode,
                            groupName: groupName,
                            openingDebit: openingDebit,
                            openingCredit: openingCredit,
                            movementDebit: movementDebit,
                            movementCredit: movementCredit,
                            closingDebit: closingDebit,
                            closingCredit: closingCredit,
                        });
                    });
                });

                // Also get parent rows that don't have child accounts
                $('tr[data-group-code]').each(function() {
                    var $row = $(this);
                    var groupCode = $row.data('group-code');

                    // Check if this group has a sub-table with children
                    var $subTable = $('.sub-table-row[data-group-id="' + groupCode + '"]');
                    var hasChildren = $subTable.length > 0 && $subTable.find('table tbody tr input[name="accountCode"]').length > 0;

                    // If no children, add the parent row as an entry
                    if (!hasChildren) {
                        var $cells = $row.find('> td');
                        var totalCells = $cells.length;

                        // Get group name from the row
                        var groupName = $row.find('td:eq(1)').text().trim();

                        // Get input values
                        var openingDebit = parseFloat($('#' + groupCode + '-opening-debit').val().replace(/,/g, '')) || 0;
                        var openingCredit = parseFloat($('#' + groupCode + '-opening-credit').val().replace(/,/g, '')) || 0;
                        var movementDebit = parseFloat($('#' + groupCode + '-movement-debit').val().replace(/,/g, '')) || 0;
                        var movementCredit = parseFloat($('#' + groupCode + '-movement-credit').val().replace(/,/g, '')) || 0;

                        // Get closing values from last 2 td cells
                        var closingDebit = parseFloat($cells.eq(totalCells - 2).text().replace(/,/g, '')) || 0;
                        var closingCredit = parseFloat($cells.eq(totalCells - 1).text().replace(/,/g, '')) || 0;

                        entries.push({
                            accountCode: groupCode,
                            accountHead: groupName,
                            groupCode: groupCode,
                            groupName: groupName,
                            openingDebit: openingDebit,
                            openingCredit: openingCredit,
                            movementDebit: movementDebit,
                            movementCredit: movementCredit,
                            closingDebit: closingDebit,
                            closingCredit: closingCredit,
                        });
                    }
                });

                // console.log(entries);

                // Send to server via AJAX
                $.ajax({
                    url: '{{ route("trail-balance.store", $company->id) }}',
                    method: 'POST',
                    data: { 
                        _token: '{{ csrf_token() }}',
                        entries: entries 
                    },
                    success: function(response) {
                        alert('Entries saved successfully!');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('An error occurred while saving entries.');
                    }
                });
            });

            // Define parent-child relationships
            var parentChildMap = {
                'CA-001': ['ADP-001', 'ADP-002', 'ADP-003', 'ADP-004', 'ADP-005'],
                'CA-002': ['CCE-001', 'CCE-002', 'CCE-003'],
                'CA-003': ['OA-001', 'OA-002'],
                'EQ-001': ['CAP-001', 'CAP-002', 'CAP-003', 'CAP-004'],
                'NCL-001': ['LTBDL-001', 'LTBDL-002', 'LTBDL-003'],
                'CL-001': ['TCP-001', 'TCP-002', 'TCP-003', 'TCP-004', 'TCP-005'],
                'CL-002': ['OL-001', 'OL-002', 'OL-003'],
            };

            // Divide parent values equally intochild rows
            $(document).on('keyup change', 'tr[data-group-code] input.editable', function() {
                var $input = $(this);
                var $parentRow = $input.closest('tr[data-group-code]');
                var groupCode = $parentRow.data('group-code');

                // Check if this group has children to distribute to
                if (!parentChildMap[groupCode]) return;

                var childCodes = parentChildMap[groupCode];
                var childCount = childCodes.length;

                if (childCount === 0) return;
                
                // Get the field type from input name (opening-debit, opening-credit)
                var inputName = $input.attr('name');
                var fieldType = inputName.replace(groupCode + '-', '');

                // Get parent value and divide equally
                var parentValue = parseFloat($input.val().replace(/,/g, '')) || 0;
                var childValue = parentValue / childCount;

                // Update each child row
                childCodes.forEach(function(childCode) {
                    var $childInput = $('#' + childCode + '-' + fieldType);
                    if ($childInput.length) {
                        $childInput.val(childValue.toFixed(2));
                        // Trigger change to recalculate closing balance
                        $childInput.trigger('change');
                    }
                });
            });

            /**
             * Calculate closing debit and credit based on opening and movement values
             * @param {number} openingDebit - Opening debit balance
             * @param {number} openingCredit - Opening credit balance
             * @param {number} movementDebit - Movement debit amount
             * @param {number} movementCredit - Movement credit amount
             * @returns {object} Object with closingDebit and closingCredit properties
             */
            function calculateClosingBalance(openingDebit, openingCredit, movementDebit, movementCredit) {
                var closingDebit = 0;
                var closingCredit = 0;

                // Convert to numbers and handle empty/null values
                openingDebit = parseFloat(openingDebit) || 0;
                openingCredit = parseFloat(openingCredit) || 0;
                movementDebit = parseFloat(movementDebit) || 0;
                movementCredit = parseFloat(movementCredit) || 0;

                // Calculate net balance
                var netBalance = openingDebit + movementDebit - openingCredit - movementCredit;

                if (netBalance > 0) {
                    closingDebit = netBalance;
                    closingCredit = 0;
                } else if (netBalance < 0) {
                    closingDebit = 0;
                    closingCredit = Math.abs(netBalance);
                } else {
                    closingDebit = 0;
                    closingCredit = 0;
                }

                return {
                    closingDebit: closingDebit,
                    closingCredit: closingCredit
                };
            }

            // Make the function globally accessible if needed
            // window.calculateClosingBalance = calculateClosingBalance;

            // Apply calculation on input keyup/change events using editable class
            $(document).on('keyup change', 'input.editable', function() {
                var $input = $(this);
                var $row = $input.closest('tr');
                var classList = $row.attr('class');
                var groupCode = $row.find('input[name="groupCode"]').val();

                if (classList == 'parent') {
                    var openingDebit = $row.find('input.editable[name="' + groupCode + '-opening-debit"]').val();
                    var openingCredit = $row.find('input.editable[name="' + groupCode + '-opening-credit"]').val();
                    var movementDebit = $row.find('input.editable[name="' + groupCode + '-movement-debit"]').val();
                    var movementCredit = $row.find('input.editable[name="' + groupCode + '-movement-credit"]').val();
                } else {
                    var accountCode = $row.find('input[name="accountCode"]').val();
                    var openingDebit = $row.find('input.editable[name="' + accountCode + '-opening-debit"]').val();
                    var openingCredit = $row.find('input.editable[name="' + accountCode + '-opening-credit"]').val();
                    var movementDebit = $row.find('input.editable[name="' + accountCode + '-movement-debit"]').val();
                    var movementCredit = $row.find('input.editable[name="' + accountCode + '-movement-credit"]').val();
                }

                var result = calculateClosingBalance(openingDebit, openingCredit, movementDebit, movementCredit);
                // console.log(result);

                // Update the closing columns (they are static text cells, not inputs)
                $row.find('td').eq(8).html((result.closingDebit.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $row.find('td').eq(9).html((result.closingCredit.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                updateFooterTotals();
            });

            // Calculate and update parent group totals when child inputs change
            $(document).on('keyup change', '.sub-table-row input.editable', function() {
                var $subTableRow = $(this).closest('.sub-table-row');
                var groupId = $subTableRow.data('group-id');

                // Find the parent row with matching data-group-code
                var $parentRow = $('tr[data-group-code="' + groupId + '"]');

                if ($parentRow.length === 0) return;

                // Initialize sums
                var sumOpeningDebit = 0;
                var sumOpeningCredit = 0;
                var sumMovementDebit = 0;
                var sumMovementCredit = 0;
                var sumClosingDebit = 0;
                var sumClosingCredit = 0;

                // Loop through all child rows in this sub-table
                $subTableRow.find('table tbody tr').each(function() {
                    var $row = $(this);
                    var $cells = $row.find('> td');

                    // Get values from inputs
                    $row.find('input.editable').each(function() {
                        var name = $(this).attr('name') || '';
                        var val = parseFloat($(this).val()) || 0;

                        if (name.indexOf('-opening-debit') !== -1) {
                            sumOpeningDebit += val;
                        } else if (name.indexOf('-opening-credit') !== -1) {
                            sumOpeningCredit += val;
                        } else if (name.indexOf('-movement-debit') !== -1) {
                            sumMovementDebit += val;
                        } else if (name.indexOf('-movement-credit') !== -1) {
                            sumMovementCredit += val;
                        }
                    });

                    // Get closing values from last 2 ts cells
                    var totalCells = $cells.length;
                    if (totalCells > 2) {
                        sumClosingDebit += parseFloat($cells.eq(totalCells - 2).text()) || 0;
                        sumClosingCredit += parseFloat($cells.eq(totalCells - 1).text()) || 0;
                    }
                });

                // Update parent row inputs
                $('#' + groupId + '-opening-debit').val(sumOpeningDebit.toFixed(2));
                $('#' + groupId + '-opening-credit').val(sumOpeningCredit.toFixed(2));
                $('#' + groupId + '-movement-debit').val(sumMovementDebit.toFixed(2));
                $('#' + groupId + '-movement-credit').val(sumMovementCredit.toFixed(2));

                // Update parent row closing columns (last 2 td cells)
                var $parentCells = $parentRow.find('> td');
                var parentTotalCells = $parentCells.length;
                $parentCells.eq(parentTotalCells - 2).text(sumClosingDebit.toFixed(2));
                $parentCells.eq(parentTotalCells - 1).text(sumClosingCredit.toFixed(2));

                updateFooterTotals();
            });

            // Calculate and update tfoot totals from all parent rows
            function updateFooterTotals() {
                var totalOpeningDebit = 0;
                var totalOpeningCredit = 0;
                var totalMovementDebit = 0;
                var totalMovementCredit = 0;
                var totalClosingDebit = 0;
                var totalClosingCredit = 0;

                // Loop through all parent rows with data-group-code
                $('tr[data-group-code]').each(function() {
                    var $row = $(this);
                    var groupCode = $row.data('group-code');

                    // Get values from parent row inputs
                    totalOpeningDebit += parseFloat($('#' + groupCode + '-opening-debit').val().replace(/,/g, '')) || 0;
                    totalOpeningCredit += parseFloat($('#' + groupCode + '-opening-credit').val().replace(/,/g, '')) || 0;
                    totalMovementDebit += parseFloat($('#' + groupCode + '-movement-debit').val().replace(/,/g, '')) || 0;
                    totalMovementCredit += parseFloat($('#' + groupCode + '-movement-credit').val().replace(/,/g, '')) || 0;

                    // Get closing values from last 2 td cells
                    var $cells = $row.find('> td');
                    var totalCells = $cells.length;
                    if (totalCells >= 2) {
                        var closingDebitText = $cells.eq(totalCells - 2).text().replace(/,/g, '');
                        var closingCreditText = $cells.eq(totalCells - 1).text().replace(/,/g, '');
                        totalClosingDebit += parseFloat(closingDebitText) || 0;
                        totalClosingCredit += parseFloat(closingCreditText) || 0;
                    }
                });

                // Update tfoot
                $('#tfoot-opening-debit').text(totalOpeningDebit.toFixed(2));
                $('#tfoot-opening-credit').text(totalOpeningCredit.toFixed(2));
                $('#tfoot-movement-debit').text(totalMovementDebit.toFixed(2));
                $('#tfoot-movement-credit').text(totalMovementCredit.toFixed(2));
                $('#tfoot-closing-debit').text(totalClosingDebit.toFixed(2));
                $('#tfoot-closing-credit').text(totalClosingCredit.toFixed(2));
            }

            updateFooterTotals();

            $('.toggle-arrow').on('click', function(e) {
                e.preventDefault();
                var groupId = $(this).data('group-id');
                var subTableRow = $('.sub-table-row[data-group-id="' + groupId + '"]');
                var icon = $(this).find('i');

                if (subTableRow.is(':visible')) {
                    subTableRow.hide();
                    icon.removeClass('fa-chevron-up').addClass('fa-chevron-right');
                } else {
                    subTableRow.show();
                    icon.removeClass('fa-chevron-right').addClass('fa-chevron-up');
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .table tr th {
            vertical-align: middle;
        }

        .trail-balance > .table > thead tr:first-child th, 
        .trail-balance > .table > thead tr:nth-child(2) th {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #FFFFFF;
        }

        .trail-balance > .table > thead tr:nth-child(2) th {
            top: 32px;
        }

        .table tr td input {
            width: 100%;
        }
    </style>
@endpush
