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

        <table class="table table-borderless table-sm mb-3 w-100" style="width: 100%;">
            <thead>
                <tr>
                    <th style="width: 75%;">&nbsp;</th>
                    <th style="text-align: center; width: 5%;">Note</th>
                    <th style="text-align: center; width: 10%;">{{ \Carbon\Carbon::parse($company->end_date)->format('Y') }}</th>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <th style="text-align: center; width: 10%;">{{ \Carbon\Carbon::parse($company->start_date)->format('Y') }}</th>
                    @endif
                </tr>
                <tr>
                    <td style="width: 75%;">&nbsp;</td>
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
                    <td>Revenue</td>
                    <td style="text-align: center;"><strong>{{ $revenue['index'] }}</strong></td>
                    <td style="text-align: center;">{{ ($revenue['total_current_year'] < 0) ? '('. number_format(abs(round($revenue['total_current_year'])), 0, '.', ',') . ')' : number_format(round($revenue['total_current_year']), 0, '.', ',') }}</td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">{{ ($revenue['total_previous_year'] < 0) ? '('. number_format(abs(round($revenue['total_previous_year'])), 0, '.', ',') . ')' : number_format(round($revenue['total_previous_year']), 0, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    <td>Cost of revenue</td>
                    <td style="text-align: center;"><strong>{{ $costOfSales['index'] }}</strong></td>
                    <td style="text-align: center;">{{ ($costOfSales['total_current_year'] < 0) ? '('. number_format(abs(round($costOfSales['total_current_year'])), 0, '.', ',') . ')' : number_format(round($costOfSales['total_current_year']), 0, '.', ',') }}</td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">{{ ($costOfSales['total_previous_year'] < 0) ? '('. number_format(abs(round($costOfSales['total_previous_year'])), 0, '.', ',') . ')' : number_format(round($costOfSales['total_previous_year']), 0, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    @php
                        $gpl_current_year = ($revenue['total_current_year'] + $costOfSales['total_current_year']);
                        $gpl_previous_year = ($revenue['total_previous_year'] + $costOfSales['total_previous_year']);
                    @endphp
                    <td><strong>{{ ( $gpl_current_year >= 0 ) ? 'Gross Profit' : 'Gross Loss' }}</strong></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center; border-top: 2px solid #000;"><strong>{{ ($gpl_current_year < 0) ? '('. number_format(abs(round($gpl_current_year)), 0, '.', ',') .')' : number_format(abs($gpl_current_year), 0, '.', ',') }}</strong></td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-top: 2px solid #000;"><strong>{{ ($gpl_previous_year < 0) ? '('. number_format(abs(round($gpl_previous_year)), 0, '.', ',') .')' : number_format(abs($gpl_previous_year), 0, '.', ',') }}</strong></td>
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
                    <td style="text-align: center;">{{ ($adminExpense['total_current_year'] <= 0) ? '('. number_format(abs(round($adminExpense['total_current_year'])), 0, '.', ',') .')' : number_format(abs($adminExpense['total_current_year']), 0, '.', ',') }}</td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">{{ ($adminExpense['total_previous_year'] <= 0) ? '('. number_format(abs(round($adminExpense['total_previous_year'])), 0, '.', ',') .')' : number_format(abs($adminExpense['total_previous_year']), 0, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    <td>Financial Charges</td>
                    <td style="text-align: center;"><strong>{{ $financialCharges['index'] }}</strong></td>
                    <td style="text-align: center;">{{ ($financialCharges['total_current_year'] <= 0) ? '('. number_format(abs(round($financialCharges['total_current_year'])), 0, '.', ',') .')' : number_format(abs($financialCharges['total_current_year']), 0, '.', ',') }}</td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">{{ ($financialCharges['total_previous_year'] <= 0) ? '('. number_format(abs(round($financialCharges['total_previous_year'])), 0, '.', ',') .')' : number_format(abs($financialCharges['total_previous_year']), 0, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    <td>Other Income</td>
                    <td style="text-align: center;"><strong>{{ $otherIncome['index'] }}</strong></td>
                    <td style="text-align: center;">{{ number_format(abs(round($otherIncome['total_current_year'])), 0, '.', ',') }}</td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">{{ number_format(abs(round($otherIncome['total_previous_year'])), 0, '.', ',') }}</td>
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
                    <td style="text-align: center;"></td>
                    <td style="text-align: center; border-top: 2px solid #000;">{{ ($pbt_current_year < 0) ? '('. number_format(abs(round($pbt_current_year)), 0, '.', ',') .')' : number_format(abs(round($pbt_current_year)), 0, '.', ',') }}</td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-top: 2px solid #000;">{{ ($pbt_previous_year < 0) ? '('. number_format(abs(round($pbt_previous_year)), 0, '.', ',') .')' : number_format(abs(round($pbt_previous_year)), 0, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    @php
                        $taxation_current_year = $taxation['total_current_year'] ?? 0;
                        $taxation_previous_year = $taxation['total_previous_year'] ?? 0;
                    @endphp
                    <td>Taxation</td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;">{{ ($taxation_current_year < 0) ? '('. number_format(abs(round($taxation_current_year)), 0, '.', ',') .')' : number_format(abs(round($taxation_current_year)), 0, '.', ',') }}</td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">{{ ($taxation_previous_year < 0) ? '('. number_format(abs(round($taxation_previous_year)), 0, '.', ',') .')' : number_format(abs(round($taxation_previous_year)), 0, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    <td><strong>{{ ( ($pbt_current_year + $taxation_current_year) >= 0 ) ? 'Profit after Taxation' : 'Loss after Taxation' }}</strong></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center; border-top: 2px solid #000; border-bottom: 4px double #000;">{{ (($pbt_current_year + $taxation_current_year) < 0) ? '('. number_format(abs(round($pbt_current_year - $taxation_current_year)), 0, '.', ',') .')' : number_format(abs(round($pbt_current_year - $taxation_current_year)), 0, '.', ',') }}</td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-top: 2px solid #000; border-bottom: 4px double #000;">{{ (($pbt_previous_year + $taxation_previous_year) < 0) ? '('. number_format(abs(round($pbt_previous_year - $taxation_previous_year)), 0, '.', ',') .')' : number_format(abs(round($pbt_previous_year - $taxation_previous_year)), 0, '.', ',') }}</td>
                    @endif
                </tr>
            </tbody>
        </table>
        <p>The annexed notes from 1 to {{ $lastIndex }} form an integral part of these financial statements.</p>
    </body>
</html>
