@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Statement</h1>
                    {{-- <a href="{{ route('companies.create') }}" class="btn btn-outline-primary btn-sm mb-3">Add Company</a> --}}
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
                        <li class="breadcrumb-item active">Statements</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
@endsection

@section('content')
    <div class="track mb-5">
        <div class="step active"> <span class="icon">1</span> <span class="text"><a href="{{ route('companies.create') }}">Company Formation</a></span> </div>
        <div class="step active"> <span class="icon">2</span> <span class="text"><a href="{{ route('fixed-assets.index', $company->id) }}">Fixed Assets Schedual</a></span> </div>
        <div class="step active"> <span class="icon">3</span> <span class="text"><a href="{{ route('trail-balance.index', $company->id) }}">Trail Balance</a></span> </div>
        <div class="step active"> <span class="icon">4</span> <span class="text"><a href="{{ route('notes.index', $company->id) }}">Notes</a></span> </div>
        <div class="step active"> <span class="icon">5</span> <span class="text">Statments</span> </div>
    </div>
    <div class="card w-50 mx-auto">
        <div class="card-header">
            <p class="text-center mb-0"><strong>{{ $company->name }}</strong></p>
            <p class="text-center mb-0"><strong>STATEMENT OF PROFIT OR LOSS</strong></p>
            <p class="text-center mb-0"><strong>FOR THE YEAR ENDED {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-borderless table-sm mb-3">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center" style="width: 5%;">Note</th>
                            <th class="text-center" style="width: 10%;">{{ \Carbon\Carbon::parse($company->end_date)->format('Y') }}</th>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <th class="text-center" style="width: 10%;">{{ \Carbon\Carbon::parse($company->start_date)->format('Y') }}</th>
                            @endif
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="text-center" style="border-top: 2px solid #000;"><strong>RUPEES</strong></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center" style="border-top: 2px solid #000;"><strong>RUPEES</strong></td>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <td>Revenue</td>
                            <td class="text-center"><strong>{{ $revenue['index'] }}</strong></td>
                            <td class="text-center">{{ ($revenue['total_current_year'] < 0) ? '('. number_format(abs(round($revenue['total_current_year'])), 0, '.', ',') . ')' : number_format(round($revenue['total_current_year']), 0, '.', ',') }}</td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center">{{ ($revenue['total_previous_year'] < 0) ? '('. number_format(abs(round($revenue['total_previous_year'])), 0, '.', ',') . ')' : number_format(round($revenue['total_previous_year']), 0, '.', ',') }}</td>
                            @endif
                            {{-- <td class="text-center">{{ rtrim(rtrim(number_format($revenue['total_current_year'], 2), '0'), '.') }}</td> --}}
                            {{-- <td class="text-center">{{ rtrim(rtrim(number_format($revenue['total_previous_year'], 2), '0'), '.') }}</td> --}}
                        </tr>
                        <tr>
                            <td>Cost of revenue</td>
                            <td class="text-center"><strong>{{ $costOfSales['index'] }}</strong></td>
                            <td class="text-center">{{ ($costOfSales['total_current_year'] < 0) ? '('. number_format(abs(round($costOfSales['total_current_year'])), 0, '.', ',') . ')' : number_format(round($costOfSales['total_current_year']), 0, '.', ',') }}</td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center">{{ ($costOfSales['total_previous_year'] < 0) ? '('. number_format(abs(round($costOfSales['total_previous_year'])), 0, '.', ',') . ')' : number_format(round($costOfSales['total_previous_year']), 0, '.', ',') }}</td>
                            @endif
                            {{-- <td class="text-center">{{ ($costOfSales['total_current_year'] < 0) ? '('. rtrim(rtrim(number_format(abs($costOfSales['total_current_year']), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($costOfSales['total_current_year']), 2), '0'), '.') }}</td> --}}
                            {{-- <td class="text-center">{{ ($costOfSales['total_previous_year'] < 0) ? '('. rtrim(rtrim(number_format(abs($costOfSales['total_previous_year']), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($costOfSales['total_previous_year']), 2), '0'), '.') }}</td> --}}
                        </tr>
                        <tr>
                            @php
                                $gpl_current_year = ($revenue['total_current_year'] + $costOfSales['total_current_year']);
                                $gpl_previous_year = ($revenue['total_previous_year'] + $costOfSales['total_previous_year']);
                            @endphp
                            <td><strong>{{ ( $gpl_current_year >= 0 ) ? 'Gross Profit' : 'Gross Loss' }}</strong></td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-top: 2px solid #000;"><strong>{{ ($gpl_current_year < 0) ? '('. number_format(abs(round($gpl_current_year)), 0, '.', ',') .')' : number_format(abs($gpl_current_year), 0, '.', ',') }}</strong></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center" style="border-top: 2px solid #000;"><strong>{{ ($gpl_previous_year < 0) ? '('. number_format(abs(round($gpl_previous_year)), 0, '.', ',') .')' : number_format(abs($gpl_previous_year), 0, '.', ',') }}</strong></td>
                            @endif
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <td>Administrative expenses</td>
                            <td class="text-center"><strong>{{ $adminExpense['index'] }}</strong></td>
                            <td class="text-center">{{ ($adminExpense['total_current_year'] <= 0) ? '('. number_format(abs(round($adminExpense['total_current_year'])), 0, '.', ',') .')' : number_format(abs($adminExpense['total_current_year']), 0, '.', ',') }}</td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center">{{ ($adminExpense['total_previous_year'] <= 0) ? '('. number_format(abs(round($adminExpense['total_previous_year'])), 0, '.', ',') .')' : number_format(abs($adminExpense['total_previous_year']), 0, '.', ',') }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Financial Charges</td>
                            <td class="text-center"><strong>{{ $financialCharges['index'] }}</strong></td>
                            <td class="text-center">{{ ($financialCharges['total_current_year'] <= 0) ? '('. number_format(abs(round($financialCharges['total_current_year'])), 0, '.', ',') .')' : number_format(abs($financialCharges['total_current_year']), 0, '.', ',') }}</td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center">{{ ($financialCharges['total_previous_year'] <= 0) ? '('. number_format(abs(round($financialCharges['total_previous_year'])), 0, '.', ',') .')' : number_format(abs($financialCharges['total_previous_year']), 0, '.', ',') }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Other Income</td>
                            <td class="text-center"><strong>{{ $otherIncome['index'] }}</strong></td>
                            <td class="text-center">{{ number_format(abs(round($otherIncome['total_current_year'])), 0, '.', ',') }}</td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center">{{ number_format(abs(round($otherIncome['total_previous_year'])), 0, '.', ',') }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            @php
                                $pbt_current_year = ($revenue['total_current_year'] + $costOfSales['total_current_year']) + $adminExpense['total_current_year'] + $financialCharges['total_current_year'] + $otherIncome['total_current_year'];
                                $pbt_previous_year = ($revenue['total_previous_year'] + $costOfSales['total_previous_year']) + $adminExpense['total_previous_year'] + $financialCharges['total_current_year'] + $otherIncome['total_previous_year'];
                            @endphp
                            <td><strong>{{ ( $pbt_current_year >= 0 ) ? 'Profit before Taxation' : 'Loss before Taxation' }}</strong></td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-top: 2px solid #000;">{{ ($pbt_current_year < 0) ? '('. number_format(abs(round($pbt_current_year)), 0, '.', ',') .')' : number_format(abs(round($pbt_current_year)), 0, '.', ',') }}</td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center" style="border-top: 2px solid #000;">{{ ($pbt_previous_year < 0) ? '('. number_format(abs(round($pbt_previous_year)), 0, '.', ',') .')' : number_format(abs(round($pbt_previous_year)), 0, '.', ',') }}</td>
                            @endif
                        </tr>
                        <tr>
                            @php
                                $taxation_current_year = $taxation['total_current_year'] ?? 0;
                                $taxation_previous_year = $taxation['total_previous_year'] ?? 0;
                            @endphp
                            <td>Taxation</td>
                            <td class="text-center"></td>
                            <td class="text-center">{{ ($taxation_current_year < 0) ? '('. number_format(abs(round($taxation_current_year)), 0, '.', ',') .')' : number_format(abs(round($taxation_current_year)), 0, '.', ',') }}</td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center">{{ ($taxation_previous_year < 0) ? '('. number_format(abs(round($taxation_previous_year)), 0, '.', ',') .')' : number_format(abs(round($taxation_previous_year)), 0, '.', ',') }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td><strong>{{ ( ($pbt_current_year + $taxation_current_year) >= 0 ) ? 'Profit after Taxation' : 'Loss after Taxation' }}</strong></td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 4px double #000;">{{ (($pbt_current_year + $taxation_current_year) < 0) ? '('. number_format(abs(round($pbt_current_year - $taxation_current_year)), 0, '.', ',') .')' : number_format(abs(round($pbt_current_year - $taxation_current_year)), 0, '.', ',') }}</td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center" style="border-top: 2px solid #000; border-bottom: 4px double #000;">{{ (($pbt_previous_year + $taxation_previous_year) < 0) ? '('. number_format(abs(round($pbt_previous_year - $taxation_previous_year)), 0, '.', ',') .')' : number_format(abs(round($pbt_previous_year - $taxation_previous_year)), 0, '.', ',') }}</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
                <p>The annexed notes from 1 to {{ $lastIndex }} form an integral part of these financial statements.</p>
            </div>
        </div>
        <div class="card-footer">
            @if (in_array('SOCI', explode(',', $company->required_statements)))
                <a href="{{ route('statements.soci', $company->id) }}" class="btn btn-primary">Next Statements</a>
            @elseif (in_array('SOCE', explode(',', $company->required_statements)))
                <a href="{{ route('statements.soce', $company->id) }}" class="btn btn-primary">Next Statements</a>
            @elseif (in_array('SOFP', explode(',', $company->required_statements)))
                <a href="{{ route('statements.sofp', $company->id) }}" class="btn btn-primary">Next Statements</a>
            @elseif (in_array('SOCF', explode(',', $company->required_statements)))
                <a href="{{ route('statements.socf', $company->id) }}" class="btn btn-primary">Next Statements</a>
            @endif
        </div>
    </div>
@endsection
