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
                            <th class="text-center" style="width: 10%;">{{ \Carbon\Carbon::parse($company->start_date)->format('Y') }}</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="border-top: 2px solid #000;"><strong>RUPEES</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000;"><strong>RUPEES</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Profit after taxation</td>
                            <td class="text-center">{{ ($plAfterTax['current_year'] < 0) ? '('. rtrim(rtrim(number_format(abs($plAfterTax['current_year']), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($plAfterTax['current_year']), 2), '0'), '.') }}</td>
                            <td class="text-center">{{ ($plAfterTax['previous_year'] < 0) ? '('. rtrim(rtrim(number_format(abs($plAfterTax['previous_year']), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($plAfterTax['previous_year']), 2), '0'), '.') }}</td>
                        </tr>
                        <tr>
                            <td>Other comprehensive income</td>
                            <td class="text-center">({{ rtrim(rtrim(number_format($otherComprehensiveIncome['current_year'], 2), '0'), '.') }})</td>
                            <td class="text-center">({{ rtrim(rtrim(number_format($otherComprehensiveIncome['previous_year'], 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            @php
                                $gpl_current_year = ($plAfterTax['current_year'] - $otherComprehensiveIncome['current_year']);
                                $gpl_previous_year = ($plAfterTax['previous_year'] - $otherComprehensiveIncome['previous_year']);
                            @endphp
                            <td><strong>{{ ( $gpl_current_year >= 0 ) ? 'Total comprehensive Income for the year' : 'Total comprehensive Loss for the year' }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 4px double #000;"><strong>{{ ($gpl_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($gpl_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($gpl_current_year), 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 4px double #000;"><strong>{{ ($gpl_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($gpl_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($gpl_previous_year), 2), '0'), '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <p>The annexed notes from 1 to {{ $lastIndex }} form an integral part of these financial statements.</p>
            </div>
        </div>
    </div>
@endsection