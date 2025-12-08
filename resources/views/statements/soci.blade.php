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
            <p class="text-center mb-0"><strong>STATEMENT OF COMPREHENSIVE INCOME</strong></p>
            <p class="text-center mb-0"><strong>FOR THE YEAR ENDED {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-borderless table-sm mb-3">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center" style="width: 10%;">{{ \Carbon\Carbon::parse($company->end_date)->format('Y') }}</th>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <th class="text-center" style="width: 10%;">{{ \Carbon\Carbon::parse($company->start_date)->format('Y') }}</th>
                            @endif
                        </tr>
                        <tr>
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
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <td>{{ ($plAfterTax['current_year'] < 0) ? 'Loss after taxation' : 'Profit after taxation' }}</td>
                            <td class="text-center">{{ ($plAfterTax['current_year'] < 0) ? '('. number_format(abs(round($plAfterTax['current_year'])), 0, '.', ',') .')' : number_format(abs(round($plAfterTax['current_year'])), 0, '.', ',') }}</td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center">{{ ($plAfterTax['previous_year'] < 0) ? '('. number_format(abs(round($plAfterTax['previous_year'])), 0, '.', ',') .')' : number_format(abs(round($plAfterTax['previous_year'])), 0, '.', ',') }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Other comprehensive income</td>
                            <td class="text-center">{{ number_format(abs(round($otherComprehensiveIncome['current_year'])), 0, '.', ',') }}</td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center">{{ number_format(abs(round($otherComprehensiveIncome['previous_year'])), 0, '.', ',') }}</td>
                            @endif
                        </tr>
                        <tr>
                            @php
                                $gpl_current_year = ($plAfterTax['current_year'] + ($otherComprehensiveIncome['current_year']));
                                $gpl_previous_year = ($plAfterTax['previous_year'] + ($otherComprehensiveIncome['previous_year']));
                            @endphp
                            <td><strong>{{ ( $gpl_current_year >= 0 ) ? 'Total comprehensive Income for the year' : 'Total comprehensive Loss for the year' }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 4px double #000;"><strong>{{ ($gpl_current_year < 0) ? '('. number_format(abs(round($gpl_current_year)), 0, '.', ',') .')' : number_format(abs(round($gpl_current_year)), 0, '.', ',') }}</strong></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center" style="border-top: 2px solid #000; border-bottom: 4px double #000;"><strong>{{ ($gpl_previous_year < 0) ? '('. number_format(abs(round($gpl_previous_year)), 0, '.', ',') .')' : number_format(abs(round($gpl_previous_year)), 0, '.', ',') }}</strong></td>
                            @endif
                        </tr>
                    </tbody>
                </table>
                <p>The annexed notes from 1 to {{ $lastIndex }} form an integral part of these financial statements.</p>
            </div>
        </div>
        <div class="card-footer">
            @if (in_array('SOCE', explode(',', $company->required_statements)))
                <a href="{{ route('statements.soce', $company->id) }}" class="btn btn-primary">Next Statements</a>
            @elseif (in_array('SOFP', explode(',', $company->required_statements)))
                <a href="{{ route('statements.sofp', $company->id) }}" class="btn btn-primary">Next Statements</a>
            @elseif (in_array('SOCF', explode(',', $company->required_statements)))
                <a href="{{ route('statements.socf', $company->id) }}" class="btn btn-primary">Next Statements</a>
            @endif
        </div>
    </div>
@endsection