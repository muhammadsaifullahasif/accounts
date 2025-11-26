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
            <p class="text-center mb-0"><strong>STATEMENT OF CHANGES IN EQUITY</strong></p>
            <p class="text-center mb-0"><strong>FOR THE YEAR ENDED {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-borderless table-sm mb-3">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center align-middle" style="border: 2px solid #000; width: 10%;">Share capital</th>
                            <th class="text-center align-middle" style="border: 2px solid #000; width: 10%;">Accumulated Profit/(losses)</th>
                            <th class="text-center align-middle" style="border: 2px solid #000; width: 10%;">Total</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center header-rupee" colspan="3"><strong><span>RUPEES</span></strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><strong>Balance as at {{ \Carbon\Carbon::parse($company->start_date)->format('M d, Y') }}</strong></td>
                            <td class="text-center"><strong>{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</strong></td>
                            <td class="text-center"><strong>{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</strong></td>
                            <td class="text-center"><strong>{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td>Total comprehensive income</td>
                            <td class="text-center">{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</td>
                            <td class="text-center">{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</td>
                            <td class="text-center">{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Balance as at {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 4px double #000;"><strong>{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 4px double #000;"><strong>{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 4px double #000;"><strong>{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</strong></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Balance as at {{ \Carbon\Carbon::parse($company->start_date)->format('M d, Y') }}</strong></td>
                            <td class="text-center"><strong>{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</strong></td>
                            <td class="text-center"><strong>{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</strong></td>
                            <td class="text-center"><strong>{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td>Total comprehensive income</td>
                            <td class="text-center">{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</td>
                            <td class="text-center">{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</td>
                            <td class="text-center">{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Balance as at {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 4px double #000;"><strong>{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 4px double #000;"><strong>{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 4px double #000;"><strong>{{ rtrim(rtrim(number_format(0, 2), '0'), '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <p>The annexed notes from 1 to {{ $lastIndex }} form an integral part of these financial statements.</p>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .header-rupee {
            position: relative;
        }

        .header-rupee strong::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            height: 3px;
            border-top: 3px dotted #000;
        }

        .header-rupee strong span {
            background: #fff;
            position: relative;
            padding: 1px;
            z-index: 999;
        }
    </style>
@endpush
