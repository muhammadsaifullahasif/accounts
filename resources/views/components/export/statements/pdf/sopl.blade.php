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
        <h1 style="text-align: center;"><strong>STATEMENT OF PROFIT OR LOSS</strong></h1>
        <h1 style="text-align: center;"><strong>FOR THE YEAR ENDED {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></h1>

        <table class="table table-hover table-borderless table-sm mb-3" style="width: 100%;">
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
                    <td style="text-align: center;"><strong>{{ $revenue['index'] }}</strong></td>
                    <td style="text-align: center;">
                        @if ($revenue['current_year'] < 0)
                            ({{ number_format(abs(round($revenue['current_year'])), 0, '.', ',') }})
                        @else
                            {{ number_format(round($revenue['current_year']), 0, '.', ',') }}
                        @endif
                    </td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">
                            @if ($revenue['current_year'] < 0)
                                ({{ number_format(abs(round($revenue['previous_year'])), 0, '.', ',') }})
                            @else
                                {{ number_format(round($revenue['previous_year']), 0, '.', ',') }}
                            @endif
                        </td>
                    @endif
                </tr>
                <tr>
                    <td>Cost of revenue</td>
                    <td style="text-align: center;"><strong>{{ $costOfSales['index'] }}</strong></td>
                    <td style="text-align: center;">
                        @if ($costOfSales['current_year'] < 0)
                            ({{ number_format(abs(round($costOfSales['current_year'])), 0, '.', ',') }})
                        @else
                            {{ number_format(round($costOfSales['current_year']), 0, '.', ',') }}
                        @endif
                    </td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">
                            @if ($costOfSales['current_year'] < 0)
                                ({{ number_format(abs(round($costOfSales['previous_year'])), 0, '.', ',') }})
                            @else
                                {{ number_format(round($costOfSales['previous_year']), 0, '.', ',') }}
                            @endif
                        </td>
                    @endif
                </tr>
                <tr>
                    @php
                        $gpl_current_year = ($revenue['current_year'] + $costOfSales['current_year']);
                        $gpl_previous_year = ($revenue['previous_year'] + $costOfSales['previous_year']);
                    @endphp
                    <td><strong>{{ ( $gpl_current_year >= 0 ) ? 'Gross Profit' : 'Gross Loss' }}</strong></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center; border-top: 2px solid #000;">
                        <strong>
                            @if ($gpl_current_year < 0)
                                ({{ number_format(abs(round($gpl_current_year)), 0, '.', ',') }})
                            @else
                                {{ number_format(round($gpl_current_year), 0, '.', ',') }}
                            @endif
                        </strong>
                    </td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-top: 2px solid #000;">
                            <strong>
                                @if ($gpl_previous_year < 0)
                                    ({{ number_format(abs(round($gpl_previous_year)), 0, '.', ',') }})
                                @else
                                    {{ number_format(round($gpl_previous_year), 0, '.', ',') }}
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
                    <td>Administrative expenses</td>
                    <td style="text-align: center;"><strong>{{ $adminExpense['index'] }}</strong></td>
                    <td style="text-align: center;">
                        @if ($adminExpense['current_year'] < 0)
                            ({{ number_format(abs(round($adminExpense['current_year'])), 0, '.', ',') }})
                        @else
                            {{ number_format(round($adminExpense['current_year']), 0, '.', ',') }}
                        @endif
                    </td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">
                            @if ($adminExpense['previous_year'] < 0)
                                ({{ number_format(abs(round($adminExpense['previous_year'])), 0, '.', ',') }})
                            @else
                                {{ number_format(round($adminExpense['previous_year']), 0, '.', ',') }}
                            @endif
                        </td>
                    @endif
                </tr>
                <tr>
                    <td>Financial Charges</td>
                    <td style="text-align: center;"><strong>{{ $financialCharges['index'] }}</strong></td>
                    <td style="text-align: center;">
                        @if ($financialCharges['current_year'] < 0)
                            ({{ number_format(abs(round($financialCharges['current_year'])), 0, '.', ',') }})
                        @else
                            {{ number_format(round($financialCharges['current_year']), 0, '.', ',') }}
                        @endif
                    </td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">
                            @if ($financialCharges['previous_year'] < 0)
                                ({{ number_format(abs(round($financialCharges['previous_year'])), 0, '.', ',') }})
                            @else
                                {{ number_format(round($financialCharges['previous_year']), 0, '.', ',') }}
                            @endif
                        </td>
                    @endif
                </tr>
                <tr>
                    <td>Other Income</td>
                    <td style="text-align: center;"><strong>{{ $otherIncome['index'] }}</strong></td>
                    <td style="text-align: center;">
                        @if ($otherIncome['current_year'] < 0)
                            ({{ number_format(abs(round($otherIncome['current_year'])), 0, '.', ',') }})
                        @else
                            {{ number_format(round($otherIncome['current_year']), 0, '.', ',') }}
                        @endif
                    </td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">
                            @if ($otherIncome['previous_year'] < 0)
                                ({{ number_format(abs(round($otherIncome['previous_year'])), 0, '.', ',') }})
                            @else
                                {{ number_format(round($otherIncome['previous_year']), 0, '.', ',') }}
                            @endif
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
                        // $pbt_current_year = ($revenue['total_current_year'] + $costOfSales['total_current_year']) + $adminExpense['total_current_year'] + $financialCharges['total_current_year'] + $otherIncome['total_current_year'];
                        // $pbt_previous_year = ($revenue['total_previous_year'] + $costOfSales['total_previous_year']) + $adminExpense['total_previous_year'] + $financialCharges['total_current_year'] + $otherIncome['total_previous_year'];
                        $plbt_current_year = $gpl_current_year + $adminExpense['current_year'] + $financialCharges['current_year'] + $otherIncome['current_year'];
                        $plbt_previous_year = $gpl_previous_year + $adminExpense['previous_year'] + $financialCharges['previous_year'] + $otherIncome['previous_year'];
                    @endphp
                    <td><strong>{{ ( $plbt_current_year >= 0 ) ? 'Profit before Taxation' : 'Loss before Taxation' }}</strong></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center; border-top: 2px solid #000;">
                        @if ($plbt_current_year < 0)
                            ({{ number_format(abs(round($plbt_current_year)), 0, '.', ',') }})
                        @else
                            {{ number_format(round($plbt_current_year), 0, '.', ',') }}
                        @endif
                    </td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-top: 2px solid #000;">
                            @if ($plbt_previous_year < 0)
                                ({{ number_format(abs(round($plbt_previous_year)), 0, '.', ',') }})
                            @else
                                {{ number_format(round($plbt_previous_year), 0, '.', ',') }}
                            @endif
                        </td>
                    @endif
                </tr>
                <tr>
                    @php
                        $taxation_current_year = $taxation['current_year'] ?? 0;
                        $taxation_previous_year = $taxation['previous_year'] ?? 0;
                    @endphp
                    <td>Taxation</td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;">
                        @if ($taxation_current_year < 0)
                            ({{ number_format(abs(round($taxation_current_year)), 0, '.', ',') }})
                        @else
                            {{ number_format(round($taxation_current_year), 0, '.', ',') }}
                        @endif
                    </td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">
                            @if ($taxation_previous_year < 0)
                                ({{ number_format(abs(round($taxation_previous_year)), 0, '.', ',') }})
                            @else
                                {{ number_format(round($taxation_previous_year), 0, '.', ',') }}
                            @endif
                        </td>
                    @endif
                </tr>
                <tr>
                    <td><strong>{{ ( ($plbt_current_year - $taxation_current_year) >= 0 ) ? 'Profit after Taxation' : 'Loss after Taxation' }}</strong></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center; border-top: 2px solid #000; border-bottom: 4px double #000;">
                        @if (($plbt_current_year - $taxation_current_year) < 0)
                            ({{ number_format(abs(round(($plbt_current_year - $taxation_current_year))), 0, '.', ',') }})
                        @else
                            {{ number_format(round(($plbt_current_year - $taxation_current_year)), 0, '.', ',') }}
                        @endif
                    </td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-top: 2px solid #000; border-bottom: 4px double #000;">
                            @if (($plbt_previous_year - $taxation_previous_year) < 0)
                                ({{ number_format(abs(round(($plbt_previous_year - $taxation_previous_year))), 0, '.', ',') }})
                            @else
                                {{ number_format(round(($plbt_previous_year - $taxation_previous_year)), 0, '.', ',') }}
                            @endif
                        </td>
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
