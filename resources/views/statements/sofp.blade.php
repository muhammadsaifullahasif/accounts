@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Statement</h1>
                    <a href="{{ route('statements.sofp.export.pdf', $company->id) }}" class="btn btn-primary btn-sm mb-3">Export PDF</a>
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
                            <td><strong>ASSETS</strong></td>
                            <td></td>
                            <td></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <td><strong>NON CURRENT ASSETS</strong></td>
                            <td></td>
                            <td></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td></td>
                            @endif
                        </tr>
                        @php
                            $tnca_current_year = $non_current_assets['total_current_year'];
                            $tnca_previous_year = $non_current_assets['total_previous_year'];
                        @endphp
                        <tr>
                            <td>{{ $non_current_assets['group_name'] }}</td>
                            <td class="text-center"><strong>{{ $non_current_assets['index'] }}</strong></td>
                            <td class="text-center">
                                @if ($non_current_assets['total_current_year'] < 0)
                                    ({{ number_format(abs(round($non_current_assets['total_current_year'])), 0, '.', ',') }})
                                @elseif ($non_current_assets['total_current_year'] > 0)
                                    {{ number_format(abs(round($non_current_assets['total_current_year'])), 0, '.', ',') }}
                                @else
                                    -
                                @endif
                            </td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center">
                                    @if ($non_current_assets['total_previous_year'] < 0)
                                        ({{ number_format(abs(round($non_current_assets['total_previous_year'])), 0, '.', ',') }})
                                    @elseif ($non_current_assets['total_previous_year'] > 0)
                                        {{ number_format(abs(round($non_current_assets['total_previous_year'])), 0, '.', ',') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-top: 2px solid #000;">
                                <strong>
                                    @if ($tnca_current_year < 0)
                                        ({{ number_format(abs(round($tnca_current_year)), 0, '.', ',') }})
                                    @elseif ($tnca_current_year > 0)
                                        {{ number_format(abs(round($tnca_current_year)), 0, '.', ',') }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center" style="border-top: 2px solid #000;">
                                    <strong>
                                        @if ($tnca_previous_year < 0)
                                            ({{ number_format(abs(round($tnca_previous_year)), 0, '.', ',') }})
                                        @elseif ($tnca_previous_year > 0)
                                            {{ number_format(abs(round($tnca_previous_year)), 0, '.', ',') }}
                                        @else
                                            -
                                        @endif
                                    </strong>
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td><strong>CURRENT ASSETS</strong></td>
                            <td></td>
                            <td></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td></td>
                            @endif
                        </tr>
                        @php
                            $tca_current_year = 0;
                            $tca_previous_year = 0;
                        @endphp
                        @foreach ($current_assets as $current_asset)
                            @php
                                $tca_current_year += $current_asset['total_current_year'];
                                $tca_previous_year += $current_asset['total_previous_year'];
                            @endphp
                            @if ($loop->first)
                                @php
                                    if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes') {
                                        $current_year_style = `border-left: 1px solid #000; border-top: 1px solid #000;`;
                                        $previous_year_style = `border-right: 1px solid #000; border-top: 1px solid #000;`;
                                    } else {
                                        $current_year_style = `border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;`;
                                    }
                                @endphp
                            @elseif ($loop->last)
                                @php
                                    if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes') {
                                        $current_year_style = `border-left: 1px solid #000; border-bottom: 1px solid #000;`;
                                        $previous_year_style = `border-right: 1px solid #000; border-bottom: 1px solid #000;`;
                                    } else {
                                        $current_year_style = `border-left: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000;`;
                                    }
                                @endphp
                            @else
                                @php
                                    if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes') {
                                        $current_year_style = `border-left: 1px solid #000;`;
                                        $previous_year_style = `border-right: 1px solid #000;`;
                                    } else {
                                        $current_year_style = `border-left: 1px solid #000; border-right: 1px solid #000;`;
                                    }
                                @endphp
                            @endif
                            <tr>
                                <td>{{ $current_asset['group_name'] }}</td>
                                <td class="text-center"><strong>{{ $current_asset['index'] }}</strong></td>
                                <td class="text-center" style="{{ $current_year_style }}">
                                    @if ($current_asset['total_current_year'] < 0)
                                        ({{ number_format(abs(round($current_asset['total_current_year'])), 0, '.', ',') }})
                                    @elseif ($current_asset['total_current_year'] > 0)
                                        {{ number_format(abs(round($current_asset['total_current_year'])), 0, '.', ',') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                    <td class="text-center" style="{{ $previous_year_style }}">
                                        @if ($current_asset['total_previous_year'] < 0)
                                            ({{ number_format(abs(round($current_asset['total_previous_year'])), 0, '.', ',') }})
                                        @elseif ($current_asset['total_previous_year'] > 0)
                                            {{ number_format(abs(round($current_asset['total_previous_year'])), 0, '.', ',') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <strong>
                                    @if ($tca_current_year < 0)
                                        ({{ number_format(abs(round($tca_current_year)), 0, '.', ',') }})
                                    @elseif ($tca_current_year > 0)
                                        {{ number_format(abs(round($tca_current_year)), 0, '.', ',') }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center">
                                    <strong>
                                        @if ($tca_previous_year < 0)
                                            ({{ number_format(abs(round($tca_previous_year)), 0, '.', ',') }})
                                        @elseif ($tca_previous_year > 0)
                                            {{ number_format(abs(round($tca_previous_year)), 0, '.', ',') }}
                                        @else
                                            -
                                        @endif
                                    </strong>
                                </td>
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
                                $total_assets_current_year = ($tnca_current_year + $tca_current_year);
                                $total_assets_previous_year = ($tnca_previous_year + $tca_previous_year);
                            @endphp
                            <td><strong>TOTAL ASSETS</strong></td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;">
                                <strong>
                                    @if ($total_assets_current_year < 0)
                                        {{ number_format(abs(round($total_assets_current_year)), 0, '.', ',') }}
                                    @elseif ($total_assets_current_year > 0)
                                        {{ number_format(abs(round($total_assets_current_year)), 0, '.', ',') }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;">
                                    <strong>
                                        @if ($total_assets_previous_year < 0)
                                            {{ number_format(abs(round($total_assets_previous_year)), 0, '.', ',') }}
                                        @elseif ($total_assets_previous_year > 0)
                                            {{ number_format(abs(round($total_assets_previous_year)), 0, '.', ',') }}
                                        @else
                                            -
                                        @endif
                                    </strong>
                                </td>
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
                            <td><strong>EQUITY AND LIABILITIES</strong></td>
                            <td></td>
                            <td></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <td><strong>EQUITY</strong></td>
                            <td></td>
                            <td></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td></td>
                            @endif
                        </tr>
                        @php
                            $tequity_current_year = 0;
                            $tequity_previous_year = 0;
                        @endphp
                        @if ($company->account_type === 'Company')
                            <tr>
                                <td>Authorized share capital</td>
                                <td class="text-center"></td>
                                <td class="text-center" style="border-bottom: 2px double #000;">
                                    <strong>
                                        @if (($company->company_meta['authorize_capital'] ?? 0) < 0)
                                            ({{ number_format(abs($company->company_meta['authorize_capital'] ?? 0), 0, '.', ',') }})
                                        @elseif (($company->company_meta['authorize_capital'] ?? 0) > 0)
                                            {{ number_format(abs($company->company_meta['authorize_capital'] ?? 0), 0, '.', ',') }}
                                        @else
                                            -
                                        @endif
                                    </strong>
                                </td>
                                @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                    <td class="text-center" style="border-bottom: 2px double #000;">
                                        <strong>
                                            @if (($company->company_meta['authorize_capital'] ?? 0) < 0)
                                                ({{ number_format(abs($company->company_meta['authorize_capital'] ?? 0), 0, '.', ',') }})
                                            @elseif (($company->company_meta['authorize_capital'] ?? 0) > 0)
                                                {{ number_format(abs($company->company_meta['authorize_capital'] ?? 0), 0, '.', ',') }}
                                            @else
                                                -
                                            @endif
                                        </strong>
                                    </td>
                                @endif
                            </tr>
                        @endif
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td></td>
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
                            <td>Issued, subscribed and paid-up</td>
                            <td class="text-center"><strong></strong></td>
                            <td class="text-center" style="border-left: 1px solid #000; border-top: 1px solid #000; @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'No') border-right: 1px solid #000; @endif">
                                @if ($paidup_capital['current_year'] < 0)
                                    ({{ number_format(abs(round($paidup_capital['current_year'])), 0, '.', ',') }})
                                @elseif ($paidup_capital['current_year'] > 0)
                                    {{ number_format(abs(round($paidup_capital['current_year'])), 0, '.', ',') }}
                                @else
                                    -
                                @endif
                            </td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center" style="border-right: 1px solid #000; border-top: 1px solid #000;">
                                    @if ($paidup_capital['previous_year'] < 0)
                                        ({{ number_format(abs(round($paidup_capital['previous_year'])), 0, '.', ',') }})
                                    @elseif ($paidup_capital['previous_year'] > 0)
                                        {{ number_format(abs(round($paidup_capital['previous_year'])), 0, '.', ',') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td>Accumulated profit/(losses)</td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-left: 1px solid #000; border-bottom: 1px solid #000; @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'No') border-right: 1px solid #000; @endif">
                                @if ($apl['current_year'] < 0)
                                    ({{ number_format(abs(round($apl['current_year'])), 0, '.', ',') }})
                                @elseif ($apl['current_year'] > 0)
                                    {{ number_format(abs(round($apl['current_year'])), 0, '.', ',') }}
                                @else
                                    -
                                @endif
                            </td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">
                                    @if ($apl['previous_year'] < 0)
                                        ({{ number_format(abs(round($apl['previous_year'])), 0, '.', ',') }})
                                    @elseif ($apl['previous_year'] > 0)
                                        {{ number_format(abs(round($apl['previous_year'])), 0, '.', ',') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endif
                        </tr>
                        <tr>
                            @php
                                $tequity_current_year = $paidup_capital['current_year'] + $apl['current_year'];
                                $tequity_previous_year = $paidup_capital['previous_year'] + $apl['previous_year'];
                            @endphp
                            <td></td>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <strong>
                                    @if ($tequity_current_year < 0)
                                        ({{ number_format(abs(round($tequity_current_year)), 0, '.', ',') }})
                                    @elseif ($tequity_current_year > 0)
                                        {{ number_format(abs(round($tequity_current_year)), 0, '.', ',') }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center">
                                    <strong>
                                        @if ($tequity_previous_year < 0)
                                            ({{ number_format(abs(round($tequity_previous_year)), 0, '.', ',') }})
                                        @elseif ($tequity_previous_year > 0)
                                            {{ number_format(abs(round($tequity_previous_year)), 0, '.', ',') }}
                                        @else
                                            -
                                        @endif
                                    </strong>
                                </td>
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
                            <td><strong>NON CURRENT LIABILITY</strong></td>
                            <td></td>
                            <td></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td></td>
                            @endif
                        </tr>
                        @php
                            $tncl_current_year = 0;
                            $tncl_previous_year = 0;
                        @endphp
                        @foreach ($non_current_liabilities as $non_current_liability)
                            @php
                                $tncl_current_year += $non_current_liability['total_current_year'];
                                $tncl_previous_year += $non_current_liability['total_previous_year'];
                            @endphp
                            <tr>
                                <td>{{ $non_current_liability['group_name'] }}</td>
                                <td class="text-center"><strong>{{ $non_current_liability['index'] }}</strong></td>
                                <td class="text-center">
                                    @if ($non_current_liability['total_current_year'] < 0)
                                        ({{ number_format(abs(round($non_current_liability['total_current_year'])), 0, '.', ',') }})
                                    @elseif ($non_current_liability['total_current_year'] > 0)
                                        {{ number_format(abs(round($non_current_liability['total_current_year'])), 0, '.', ',') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                    <td class="text-center">
                                        @if ($non_current_liability['total_previous_year'] < 0)
                                            ({{ number_format(abs(round($non_current_liability['total_previous_year'])), 0, '.', ',') }})
                                        @elseif ($non_current_liability['total_previous_year'] > 0)
                                            {{ number_format(abs(round($non_current_liability['total_previous_year'])), 0, '.', ',') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-top: 2px solid #000;">
                                <strong>
                                    @if ($tncl_current_year < 0)
                                        ({{ number_format(abs(round($tncl_current_year)), 0, '.', ',') }})
                                    @elseif ($tncl_current_year > 0)
                                        {{ number_format(abs(round($tncl_current_year)), 0, '.', ',') }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center" style="border-top: 2px solid #000;">
                                    <strong>
                                        @if ($tncl_previous_year < 0)
                                            ({{ number_format(abs(round($tncl_previous_year)), 0, '.', ',') }})
                                        @elseif ($tncl_previous_year > 0)
                                            {{ number_format(abs(round($tncl_previous_year)), 0, '.', ',') }}
                                        @else
                                            -
                                        @endif
                                    </strong>
                                </td>
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
                            <td><strong>CURRENT LIABILITY</strong></td>
                            <td></td>
                            <td></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td></td>
                            @endif
                        </tr>
                        @php
                            $tcl_current_year = 0;
                            $tcl_previous_year = 0;
                        @endphp
                        @foreach ($current_liabilities as $current_liability)
                            @php
                                $tcl_current_year += $current_liability['total_current_year'];
                                $tcl_previous_year += $current_liability['total_previous_year'];
                            @endphp
                            <tr>
                                <td>{{ $current_liability['group_name'] }}</td>
                                <td class="text-center"><strong>{{ $current_liability['index'] }}</strong></td>
                                <td class="text-center">
                                    @if ($current_liability['total_current_year'] < 0)
                                        ({{ number_format(abs(round($current_liability['total_current_year'])), 0, '.', ',') }})
                                    @elseif ($current_liability['total_current_year'] > 0)
                                        {{ number_format(abs(round($current_liability['total_current_year'])), 0, '.', ',') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                    <td class="text-center">
                                        @if ($current_liability['total_previous_year'] < 0)
                                            ({{ number_format(abs(round($current_liability['total_previous_year'])), 0, '.', ',') }})
                                        @elseif ($current_liability['total_previous_year'] > 0)
                                            {{ number_format(abs(round($current_liability['total_previous_year'])), 0, '.', ',') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-top: 2px solid #000;">
                                <strong>
                                    @if ($tcl_current_year < 0)
                                        ({{ number_format(abs(round($tcl_current_year)), 0, '.', ',') }})
                                    @elseif ($tcl_current_year > 0)
                                        {{ number_format(abs(round($tcl_current_year)), 0, '.', ',') }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center" style="border-top: 2px solid #000;">
                                    <strong>
                                        @if ($tcl_previous_year < 0)
                                            ({{ number_format(abs(round($tcl_previous_year)), 0, '.', ',') }})
                                        @elseif ($tcl_previous_year > 0)
                                            {{ number_format(abs(round($tcl_previous_year)), 0, '.', ',') }}
                                        @else
                                            -
                                        @endif
                                    </strong>
                                </td>
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
                            <td><strong>CONTINGENCIES AND COMMITMENTS</strong></td>
                            <td class="text-center"><strong>14</strong></td>
                            <td class="text-center"><strong>-</strong></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center"><strong>-</strong></td>
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
                            <td></td>
                            <td></td>
                            <td></td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            @php
                                $tel_current_year = ($tequity_current_year - $tcl_current_year);
                                $tel_previous_year = ($tequity_previous_year - $tcl_previous_year);
                            @endphp
                            <td><strong>TOTAL EQUITY AND LIABILITIES</strong></td>
                            <td class="text-center"></td>
                            <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;">
                                <strong>
                                    @if ($tel_current_year < 0)
                                        ({{ number_format(abs(round($tel_current_year)), 0, '.', ',') }})
                                    @elseif ($tel_current_year > 0)
                                        {{ number_format(abs(round($tel_current_year)), 0, '.', ',') }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </td>
                            @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                                <td class="text-center" style="border-top: 2px solid #000; border-bottom: 5px double #000;">
                                    <strong>
                                        @if ($tel_previous_year < 0)
                                            ({{ number_format(abs(round($tel_previous_year)), 0, '.', ',') }})
                                        @elseif ($tel_previous_year > 0)
                                            {{ number_format(abs(round($tel_previous_year)), 0, '.', ',') }}
                                        @else
                                            -
                                        @endif
                                    </strong>
                                </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
                <p>The annexed notes from 1 to {{ $lastIndex }} form an integral part of these financial statements.</p>
            </div>
        </div>
        <div class="card-footer">
            @if (in_array('SOCF', explode(',', $company->required_statements)))
                <a href="{{ route('statements.socf', $company->id) }}" class="btn btn-primary">Next Statements</a>
            @endif
        </div>
    </div>
@endsection
