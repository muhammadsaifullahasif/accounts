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
            <p class="text-center mb-0"><strong>STATEMENT OF CASH FLOWS</strong></p>
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
                            <td><strong>CASH FLOW FROM OPERATING ACTIVITIES</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Profit before taxation</td>
                            <td class="text-center"></td>
                            <td class="text-center">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                            <td class="text-center">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            <td><strong>Adjustment for non cash items</strong></td>
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
                            <td>Depreciation</td>
                            <td class="text-center"></td>
                            <td class="text-center">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                            <td class="text-center">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            <td><strong>Changes in working capital:</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><strong>(Increase)/decrease in current assets:</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Trade and other receivables</td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-left: 1px solid #000; border-top: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                            <td class="text-center" style="border-right: 1px solid #000; border-top: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            <td>Advances, deposits and other receivables</td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-left: 1px solid #000; border-bottom: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                            <td class="text-center" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            @php
                                $total_assets_current_year = (0 - 0);
                                $total_assets_previous_year = (0 - 0);
                            @endphp
                            <td></td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-bottom: 5px double #000;"><strong>{{ ($total_assets_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($total_assets_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($total_assets_current_year), 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-bottom: 5px double #000;"><strong>{{ ($total_assets_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($total_assets_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($total_assets_previous_year), 2), '0'), '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><strong>Increase/(decrease) in current liabilities:</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Advance from customers</td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-left: 1px solid #000; border-top: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                            <td class="text-center" style="border-right: 1px solid #000; border-top: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                        </tr>
                        <tr>
                            <td>Trade creditor and other payables</td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-left: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                            <td class="text-center" style="border-right: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                        </tr>
                        <tr>
                            <td>Accrued and other liabilities</td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-left: 1px solid #000; border-bottom: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                            <td class="text-center" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
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
                            @php
                                $pbt_current_year = 0;
                                $pbt_previous_year = 0;
                            @endphp
                            <td><strong>Cash (used in)/generated from operations</strong></td>
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
                            <td>Income tax paid</td>
                            <td class="text-center"></td>
                            <td class="text-center">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                            <td class="text-center">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            @php
                                $tel_current_year = (0 - 0);
                                $tel_previous_year = (0 - 0);
                            @endphp
                            <td><strong>Net cash (used in)/generated from operating activities</strong></td>
                            <td class="text-center"><strong>A</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>CASH FLOW FROM INVESTING ACTIVITIES</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        
                        <tr>
                            <td>Purchase of Property, plant and equipments</td>
                            <td class="text-center"></td>
                            <td class="text-center">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                            <td class="text-center">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            <td>Capital work in process</td>
                            <td class="text-center"></td>
                            <td class="text-center">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                            <td class="text-center">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            @php
                                $tel_current_year = (0 - 0);
                                $tel_previous_year = (0 - 0);
                            @endphp
                            <td><strong>Net cash generated from/(used in) invensting activities</strong></td>
                            <td class="text-center"><strong>B</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td><strong>CASH FLOW FROM FINANCING ACTIVITIES</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Issuance of share capital</td>
                            <td class="text-center"></td>
                            <td class="text-center">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                            <td class="text-center">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                        </tr>
                        <tr>
                            @php
                                $tel_current_year = (0 - 0);
                                $tel_previous_year = (0 - 0);
                            @endphp
                            <td><strong>Net cash generated from financing activities</strong></td>
                            <td class="text-center"><strong>C</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') }}</strong></td>
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
                            <td>Net Increase in cash and cash equivalent</td>
                            <td class="text-center"><strong>A+B+C</strong></td>
                            <td class="text-center">{{ ($tel_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') }}</td>
                            <td class="text-center">{{ ($tel_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') }}</td>
                        </tr>
                        <tr>
                            <td>Cash and cash equivalents at the beginning of year</td>
                            <td class="text-center"></td>
                            <td class="text-center">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                            <td class="text-center">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                        </tr>
                        <tr>
                            @php
                                $pbt_current_year = 0;
                                $pbt_previous_year = 0;
                            @endphp
                            <td><strong>CASH AND CASH EQUIVALENTS AT THE END OF YEAR</strong></td>
                            <td class="text-center"><strong>8</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px solid #000;"><strong>{{ ($pbt_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($pbt_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($pbt_current_year), 2), '0'), '.') }}</strong></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px solid #000;"><strong>{{ ($pbt_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($pbt_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($pbt_previous_year), 2), '0'), '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <p>The annexed notes from 1 to {{ $lastIndex }} form an integral part of these financial statements.</p>
            </div>
        </div>
    </div>
@endsection
