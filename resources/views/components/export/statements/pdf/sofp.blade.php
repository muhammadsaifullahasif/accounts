<html>
    <head>
        <style>
            @font-face {
                font-family: 'Calibri';
                src: url('../../../../fonts/calibri-regular.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }
        </style>
        <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
        @if ($style)
            {!! $style !!}
        @endif
    </head>
    <body class="container">
        <h1 style="text-align: center;"><strong>{{ $company->name }}</strong></h1>
        <h1 style="text-align: center;"><strong>STATEMENT OF FINANCIAL POSITION</strong></h1>
        <h1 style="text-align: center;"><strong>FOR THE YEAR ENDED {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></h1>

        <table class="table table-hover table-borderless table-sm mb-3" style="width: 100%;">
            <thead>
                <tr>
                    <th></th>
                    <th style="text-align: center; width: 5%;">Note</th>
                    <th style="text-align: center; width: 10%;">{{ \Carbon\Carbon::parse($company->end_date)->format('Y') }}</th>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <th style="text-align: center; width: 10%;">{{ \Carbon\Carbon::parse($company->start_date)->format('Y') }}</th>
                    @endif
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align: center; border-top: 2px solid #000;"><strong>RUPEES</strong></td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-top: 2px solid #000;"><strong>RUPEES</strong></td>
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
                    <td style="text-align: center;"><strong>{{ $non_current_assets['index'] }}</strong></td>
                    <td style="text-align: center;">{{ number_format(abs(round($non_current_assets['total_current_year'])), 0, '.', ',') }}</td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">{{ number_format(abs(round($non_current_assets['total_previous_year'])), 0, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center; border-top: 2px solid #000;"><strong>{{ number_format(abs(round($tnca_current_year)), 0, '.', ',') }}</strong></td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-top: 2px solid #000;"><strong>{{ number_format(abs(round($tnca_previous_year)), 0, '.', ',') }}</strong></td>
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
                                $current_year_style = `text-align: center; border-left: 1px solid #000; border-top: 1px solid #000;`;
                                $previous_year_style = `text-align: center; border-right: 1px solid #000; border-top: 1px solid #000;`;
                            } else {
                                $current_year_style = `text-align: center; border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;`;
                            }
                        @endphp
                    @elseif ($loop->last)
                        @php
                            if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes') {
                                $current_year_style = `text-align: center; border-left: 1px solid #000; border-bottom: 1px solid #000;`;
                                $previous_year_style = `text-align: center; border-right: 1px solid #000; border-bottom: 1px solid #000;`;
                            } else {
                                $current_year_style = `text-align: center; border-left: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000;`;
                            }
                        @endphp
                    @else
                        @php
                            if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes') {
                                $current_year_style = `text-align: center; border-left: 1px solid #000;`;
                                $previous_year_style = `text-align: center; border-right: 1px solid #000;`;
                            } else {
                                $current_year_style = `text-align: center; border-left: 1px solid #000; border-right: 1px solid #000;`;
                            }
                        @endphp
                    @endif
                    <tr>
                        <td>{{ $current_asset['group_name'] }}</td>
                        <td style="text-align: center;"><strong>{{ $current_asset['index'] }}</strong></td>
                        <td style="{{ $current_year_style }}">{{ number_format(abs(round($current_asset['total_current_year'])), 0, '.', ',') }}</td>
                        @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                            <td style="{{ $previous_year_style }}">{{ number_format(abs(round($current_asset['total_previous_year'])), 0, '.', ',') }}</td>
                        @endif
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"><strong>{{ ($tca_current_year < 0) ? '('. number_format(abs(round($tca_current_year)), 0, '.', ',') .')' : number_format(abs(round($tca_current_year)), 0, '.', ',') }}</strong></td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;"><strong>{{ ($tca_previous_year < 0) ? '('. number_format(abs(round($tca_previous_year)), 0, '.', ',') .')' : number_format(abs(round($tca_previous_year)), 0, '.', ',') }}</strong></td>
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
                    <td style="text-align: center;"></td>
                    <td style="text-align: center; border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($total_assets_current_year < 0) ? '('. number_format(abs(round($total_assets_current_year)), 0, '.', ',') .')' : number_format(abs(round($total_assets_current_year)), 0, '.', ',') }}</strong></td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($total_assets_previous_year < 0) ? '('. number_format(abs(round($total_assets_previous_year)), 0, '.', ',') .')' : number_format(abs(round($total_assets_previous_year)), 0, '.', ',') }}</strong></td>
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
                <tr>
                    <td>Authorized share capital</td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center; border-bottom: 2px double #000;"><strong>{{ (($company->company_meta['authorize_capital'] ?? 0) < 0) ? '('. number_format(abs($company->company_meta['authorize_capital'] ?? 0), 0, '.', ',') .')' : number_format(abs($company->company_meta['authorize_capital'] ?? 0), 0, '.', ',') }}</strong></td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-bottom: 2px double #000;"><strong>{{ (($company->company_meta['authorize_capital'] ?? 0) < 0) ? '('. number_format(abs($company->company_meta['authorize_capital'] ?? 0), 0, '.', ',') .')' : number_format(abs($company->company_meta['authorize_capital'] ?? 0), 0, '.', ',') }}</strong></td>
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
                    <td>Issued, subscribed and paid-up</td>
                    <td style="text-align: center;"><strong></strong></td>
                    <td style="text-align: center; border-left: 1px solid #000; border-top: 1px solid #000; @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'No') border-right: 1px solid #000; @endif">
                        {{ ($paidup_capital['current_year'] < 0) ? '('. number_format(abs(round($paidup_capital['current_year'])), 0, '.', ',') .')' : number_format(abs(round($paidup_capital['current_year'])), 0, '.', ',') }}
                    </td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-right: 1px solid #000; border-top: 1px solid #000;">
                            {{ ($paidup_capital['previous_year'] < 0) ? '('. number_format(abs(round($paidup_capital['previous_year'])), 0, '.', ',') .')' : number_format(abs(round($paidup_capital['previous_year'])), 0, '.', ',') }}
                        </td>
                    @endif
                </tr>
                <tr>
                    <td>Accumulated profit/(losses)</td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center; border-left: 1px solid #000; border-bottom: 1px solid #000; @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'No') border-right: 1px solid #000; @endif">{{ ($apl['current_year'] < 0) ? '('. number_format(abs(round($apl['current_year'])), 0, '.', ',') .')' : number_format(abs(round($apl['current_year'])), 0, '.', ',') }}</td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-right: 1px solid #000; border-bottom: 1px solid #000;">{{ ($apl['previous_year'] < 0) ? '('. number_format(abs(round($apl['previous_year'])), 0, '.', ',') .')' : number_format(abs(round($apl['previous_year'])), 0, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    @php
                        $tequity_current_year = $paidup_capital['current_year'] + $apl['current_year'];
                        $tequity_previous_year = $paidup_capital['previous_year'] + $apl['previous_year'];
                    @endphp
                    <td></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"><strong>{{ ($tequity_current_year < 0) ? '('. number_format(abs(round($tequity_current_year)), 0, '.', ',') .')' : number_format(abs(round($tequity_current_year)), 0, '.', ',') }}</strong></td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;"><strong>{{ ($tequity_previous_year < 0) ? '('. number_format(abs(round($tequity_previous_year)), 0, '.', ',') .')' : number_format(abs(round($tequity_previous_year)), 0, '.', ',') }}</strong></td>
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
                        <td style="text-align: center;"><strong>{{ $current_liability['index'] }}</strong></td>
                        <td style="text-align: center;">{{ ($current_liability['total_current_year'] < 0) ? '('. number_format(abs(round($current_liability['total_current_year'])), 0, '.', ',') .')' : number_format(abs(round($current_liability['total_current_year'])), 0, '.', ',') }}</td>
                        @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                            <td style="text-align: center;">{{ ($current_liability['total_previous_year'] < 0) ? '('. number_format(abs(round($current_liability['total_previous_year'])), 0, '.', ',') .')' : number_format(abs(round($current_liability['total_previous_year'])), 0, '.', ',') }}</td>
                        @endif
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center; border-top: 2px solid #000;"><strong>{{ ($tcl_current_year < 0) ? '('. number_format(abs(round($tcl_current_year)), 0, '.', ',') .')' : number_format(abs(round($tcl_current_year)), 0, '.', ',') }}</strong></td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-top: 2px solid #000;"><strong>{{ ($tcl_previous_year < 0) ? '('. number_format(abs(round($tcl_previous_year)), 0, '.', ',') .')' : number_format(abs(round($tcl_previous_year)), 0, '.', ',') }}</strong></td>
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
                    <td style="text-align: center;"><strong>14</strong></td>
                    <td style="text-align: center;"><strong>-</strong></td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;"><strong>-</strong></td>
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
                    <td style="text-align: center;"></td>
                    <td style="text-align: center; border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_current_year < 0) ? '('. number_format(abs(round($tel_current_year)), 0, '.', ',') .')' : number_format(abs(round($tel_current_year)), 0, '.', ',') }}</strong></td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_previous_year < 0) ? '('. number_format(abs(round($tel_previous_year)), 0, '.', ',') .')' : number_format(abs(round($tel_previous_year)), 0, '.', ',') }}</strong></td>
                    @endif
                </tr>
            </tbody>
        </table>
        <p>The annexed notes from 1 to {{ $lastIndex }} form an integral part of these financial statements.</p>
        @if ($company->account_type == 'Proprietor')
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top: 100px;">
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: left;">Proprietor</span>
            </div>
        @endif
        @if ($company->account_type == 'AOP')
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top: 100px;">
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: left;">Partner</span>
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: right;">Partner</span>
            </div>
        @endif
        @if ($company->account_type == 'Company')
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top: 100px;">
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: left;">CEO</span>
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: right;">Director</span>
            </div>
        @endif
    </body>
</html>
