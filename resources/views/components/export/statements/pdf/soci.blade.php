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
        <h1 style="text-align: center;"><strong>STATEMENT OF COMPREHENSIVE INCOME</strong></h1>
        <h1 style="text-align: center;"><strong>FOR THE YEAR ENDED {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></h1>

        <table class="table table-hover table-borderless table-sm mb-3" style="width: 100%;">
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
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td></td>
                    @endif
                </tr>
                <tr>
                    <td>{{ ($profitLossAfterTaxation['current_year'] < 0) ? 'Loss after taxation' : 'Profit after taxation' }}</td>
                    <td style="text-align: right;">
                        @if ($profitLossAfterTaxation['current_year'] < 0)
                            ({{ number_format(abs(round($profitLossAfterTaxation['current_year'])), 0, '.', ',') }})
                        @elseif ($profitLossAfterTaxation['current_year'] > 0)
                            {{ number_format(abs(round($profitLossAfterTaxation['current_year'])), 0, '.', ',') }}
                        @else
                            -
                        @endif
                    </td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: right;">
                            @if ($profitLossAfterTaxation['previous_year'] < 0)
                                ({{ number_format(abs(round($profitLossAfterTaxation['previous_year'])), 0, '.', ',') }})
                            @elseif ($profitLossAfterTaxation['previous_year'] > 0)
                                {{ number_format(abs(round($profitLossAfterTaxation['previous_year'])), 0, '.', ',') }}
                            @else
                                -
                            @endif
                        </td>
                    @endif
                </tr>
                <tr>
                    <td>Other comprehensive income</td>
                    <td style="text-align: right;">
                        @if ($otherComprehensiveIncome['current_year'] < 0)
                            ({{ number_format(abs(round($otherComprehensiveIncome['current_year'])), 0, '.', ',') }})
                        @elseif ($otherComprehensiveIncome['current_year'] > 0)
                            {{ number_format(abs(round($otherComprehensiveIncome['current_year'])), 0, '.', ',') }}
                        @else
                            -
                        @endif
                    </td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: right;">
                            @if ($otherComprehensiveIncome['previous_year'] < 0)
                                ({{ number_format(abs(round($otherComprehensiveIncome['previous_year'])), 0, '.', ',') }})
                            @elseif ($otherComprehensiveIncome['previous_year'] > 0)
                                {{ number_format(abs(round($otherComprehensiveIncome['previous_year'])), 0, '.', ',') }}
                            @else
                                -
                            @endif
                        </td>
                    @endif
                </tr>
                <tr>
                    @php
                        $gpl_current_year = ($profitLossAfterTaxation['current_year'] + ($otherComprehensiveIncome['current_year']));
                        $gpl_previous_year = ($profitLossAfterTaxation['previous_year'] + ($otherComprehensiveIncome['previous_year']));
                    @endphp
                    <td><strong>{{ ( $gpl_current_year >= 0 ) ? 'Total comprehensive Income for the year' : 'Total comprehensive Loss for the year' }}</strong></td>
                    <td style="text-align: right; border-top: 2px solid #000; border-bottom: 4px double #000;">
                        <strong>
                            @if ($gpl_current_year < 0)
                                ({{ number_format(abs(round($gpl_current_year)), 0, '.', ',') }})
                            @elseif ($gpl_current_year > 0)
                                {{ number_format(abs(round($gpl_current_year)), 0, '.', ',') }}
                            @else
                                -
                            @endif
                        </strong>
                    </td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: right; border-top: 2px solid #000; border-bottom: 4px double #000;">
                            <strong>
                                @if ($gpl_previous_year < 0)
                                    ({{ number_format(abs(round($gpl_previous_year)), 0, '.', ',') }})
                                @elseif ($gpl_previous_year > 0)
                                    {{ number_format(abs(round($gpl_previous_year)), 0, '.', ',') }}
                                @else
                                    -
                                @endif
                            </strong>
                        </td>
                    @endif
                </tr>
            </tbody>
        </table>
        <p style="width: 100%; text-align: left;">The annexed notes from 1 to {{ $lastIndex }} form an integral part of these financial statements.</p>
        @if ($company->account_type == 'Proprietor')
            <div style="position: fixed; bottom: -60px; left: 0; right: 0; width: 100%; height: 100px; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top: 100px;">
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: left;">Proprietor</span>
            </div>
        @endif
        @if ($company->account_type == 'AOP')
            <div style="position: fixed; bottom: -60px; left: 0; right: 0; width: 100%; height: 100px; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top: 100px;">
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: left;">Partner</span>
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: right;">Partner</span>
            </div>
        @endif
        @if ($company->account_type == 'Company')
            <div style="position: fixed; bottom: -60px; left: 0; right: 0; width: 100%; height: 100px; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top: 100px;">
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: left;">CEO</span>
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: right;">Director</span>
            </div>
        @endif
    </body>
</html>
