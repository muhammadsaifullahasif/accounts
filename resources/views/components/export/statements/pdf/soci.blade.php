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
                    <th style="text-align: center; width: 10%;">{{ \Carbon\Carbon::parse($company->end_date)->format('Y') }}</th>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <th style="text-align: center; width: 10%;">{{ \Carbon\Carbon::parse($company->start_date)->format('Y') }}</th>
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
                    <td>{{ ($plAfterTax['current_year'] < 0) ? 'Loss after taxation' : 'Profit after taxation' }}</td>
                    <td style="text-align: center;">{{ ($plAfterTax['current_year'] < 0) ? '('. number_format(abs(round($plAfterTax['current_year'])), 0, '.', ',') .')' : number_format(abs(round($plAfterTax['current_year'])), 0, '.', ',') }}</td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">{{ ($plAfterTax['previous_year'] < 0) ? '('. number_format(abs(round($plAfterTax['previous_year'])), 0, '.', ',') .')' : number_format(abs(round($plAfterTax['previous_year'])), 0, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    <td>Other comprehensive income</td>
                    <td style="text-align: center;">{{ number_format(abs(round($otherComprehensiveIncome['current_year'])), 0, '.', ',') }}</td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center;">{{ number_format(abs(round($otherComprehensiveIncome['previous_year'])), 0, '.', ',') }}</td>
                    @endif
                </tr>
                <tr>
                    @php
                        $gpl_current_year = ($plAfterTax['current_year'] + ($otherComprehensiveIncome['current_year']));
                        $gpl_previous_year = ($plAfterTax['previous_year'] + ($otherComprehensiveIncome['previous_year']));
                    @endphp
                    <td><strong>{{ ( $gpl_current_year >= 0 ) ? 'Total comprehensive Income for the year' : 'Total comprehensive Loss for the year' }}</strong></td>
                    <td style="text-align: center; border-top: 2px solid #000; border-bottom: 4px double #000;"><strong>{{ ($gpl_current_year < 0) ? '('. number_format(abs(round($gpl_current_year)), 0, '.', ',') .')' : number_format(abs(round($gpl_current_year)), 0, '.', ',') }}</strong></td>
                    @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                        <td style="text-align: center; border-top: 2px solid #000; border-bottom: 4px double #000;"><strong>{{ ($gpl_previous_year < 0) ? '('. number_format(abs(round($gpl_previous_year)), 0, '.', ',') .')' : number_format(abs(round($gpl_previous_year)), 0, '.', ',') }}</strong></td>
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
