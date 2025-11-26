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
            <p class="text-center mb-0"><strong>STATEMENT OF FINANCIAL POSITION</strong></p>
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
                            <th class="text-center" style="width: 10%;">{{ \Carbon\Carbon::parse($company->start_date)->format('Y') }}</th>
                        </tr>
                        <tr>
                            <td></td>
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
                            <td></td>
                        </tr>
                        <tr>
                            <td><strong>ASSETS</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><strong>NON CURRENT ASSETS</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Property, plant equipments</td>
                            <td class="text-center"><strong>{{ $propertyEquipment['index'] }}</strong></td>
                            <td class="text-center">({{ rtrim(rtrim(number_format($propertyEquipment['total_current_year'], 2), '0'), '.') }})</td>
                            <td class="text-center">({{ rtrim(rtrim(number_format($propertyEquipment['total_previous_year'], 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-top: 2px solid #000;"><strong>{{ rtrim(rtrim(number_format($tnca_current_year, 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000;"><strong>{{ rtrim(rtrim(number_format($tnca_previous_year, 2), '0'), '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>CURRENT ASSETS</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Capital work in process</td>
                            <td class="text-center"><strong>{{ $capital['index'] }}</strong></td>
                            <td class="text-center" style="border-left: 1px solid #000; border-top: 1px solid #000;">{{ rtrim(rtrim(number_format($capital['total_current_year'], 2), '0'), '.') }}</td>
                            <td class="text-center" style="border-right: 1px solid #000; border-top: 1px solid #000;">{{ rtrim(rtrim(number_format($capital['total_previous_year'], 2), '0'), '.') }}</td>
                        </tr>
                        <tr>
                            <td>Trade and other receivables</td>
                            <td class="text-center"><strong>{{ $tradeReceivable['index'] }}</strong></td>
                            <td class="text-center" style="border-left: 1px solid #000;">{{ rtrim(rtrim(number_format($tradeReceivable['total_current_year'], 2), '0'), '.') }}</td>
                            <td class="text-center" style="border-right: 1px solid #000;">{{ rtrim(rtrim(number_format($tradeReceivable['total_previous_year'], 2), '0'), '.') }}</td>
                        </tr>
                        <tr>
                            <td>Advances, deposits and other receivables</td>
                            <td class="text-center"><strong>{{ $advanceDepositPrepayment['index'] }}</strong></td>
                            <td class="text-center" style="border-left: 1px solid #000;">{{ rtrim(rtrim(number_format($advanceDepositPrepayment['total_current_year'], 2), '0'), '.') }}</td>
                            <td class="text-center" style="border-right: 1px solid #000;">{{ rtrim(rtrim(number_format($advanceDepositPrepayment['total_previous_year'], 2), '0'), '.') }}</td>
                        </tr>
                        <tr>
                            <td>Cash and bank balances</td>
                            <td class="text-center"><strong>{{ $cashEquivalent['index'] }}</strong></td>
                            <td class="text-center" style="border-left: 1px solid #000; border-bottom: 1px solid #000;">{{ rtrim(rtrim(number_format($cashEquivalent['total_current_year'], 2), '0'), '.') }}</td>
                            <td class="text-center" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">{{ rtrim(rtrim(number_format($cashEquivalent['total_previous_year'], 2), '0'), '.') }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center"></td>
                            <td class="text-center"><strong>{{ ($tca_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($tca_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tca_current_year), 2), '0'), '.') }}</strong></td>
                            <td class="text-center"><strong>{{ ($tca_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($tca_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tca_previous_year), 2), '0'), '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            @php
                                $total_assets_current_year = ($tnca_current_year + $tca_current_year);
                                $total_assets_previous_year = ($tnca_previous_year + $tca_previous_year);
                            @endphp
                            <td><strong>TOTAL ASSETS</strong></td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($total_assets_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($total_assets_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($total_assets_current_year), 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($total_assets_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($total_assets_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($total_assets_previous_year), 2), '0'), '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><strong>EQUITY AND LIABILITIES</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><strong>EQUITY</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Authorized share capital</td>
                            <td class="text-center"><strong>9.1</strong></td>
                            <td class="text-center" style="border-bottom: 2px double #000;"><strong>{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-bottom: 2px double #000;"><strong>{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Issued, subscribed and paid-up</td>
                            <td class="text-center"><strong>9.2</strong></td>
                            <td class="text-center" style="border-left: 1px solid #000; border-top: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                            <td class="text-center" style="border-right: 1px solid #000; border-top: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            <td>Accumulated profit/(losses)</td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-left: 1px solid #000; border-bottom: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                            <td class="text-center" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            @php
                                $pbt_current_year = 0;
                                $pbt_previous_year = 0;
                            @endphp
                            <td></td>
                            <td class="text-center"></td>
                            <td class="text-center"><strong>{{ ($pbt_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($pbt_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($pbt_current_year), 2), '0'), '.') }}</strong></td>
                            <td class="text-center"><strong>{{ ($pbt_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($pbt_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($pbt_previous_year), 2), '0'), '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td><strong>CURRENT LIABILITY</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Advance from customers</td>
                            <td class="text-center"><strong>10</strong></td>
                            <td class="text-center" style="border-left: 1px solid #000; border-top: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                            <td class="text-center" style="border-right: 1px solid #000; border-top: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                        </tr>
                        <tr>
                            <td>Trade creditors and other payables</td>
                            <td class="text-center"><strong>11</strong></td>
                            <td class="text-center" style="border-left: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                            <td class="text-center" style="border-right: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            <td>Accrued and other liabilities</td>
                            <td class="text-center"><strong>12</strong></td>
                            <td class="text-center" style="border-left: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                            <td class="text-center" style="border-right: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            <td>Provision for taxation</td>
                            <td class="text-center"><strong>13</strong></td>
                            <td class="text-center" style="border-left: 1px solid #000; border-bottom: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                            <td class="text-center" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            @php
                                $pbt_current_year = 0;
                                $pbt_previous_year = 0;
                            @endphp
                            <td></td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-top: 2px solid #000;"><strong>{{ ($pbt_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($pbt_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($pbt_current_year), 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000;"><strong>{{ ($pbt_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($pbt_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($pbt_previous_year), 2), '0'), '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><strong>CONTINGENCIES AND COMMITMENTS</strong></td>
                            <td class="text-center"><strong>14</strong></td>
                            <td class="text-center"><strong>{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</strong></td>
                            <td class="text-center"><strong>{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            @php
                                $tel_current_year = (0 - 0);
                                $tel_previous_year = (0 - 0);
                            @endphp
                            <td><strong>TOTAL EQUITY AND LIABILITIES</strong></td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <p>The annexed notes from 1 to {{ $lastIndex }} form an integral part of these financial statements.</p>
            </div>
        </div>
    </div>
@endsection
