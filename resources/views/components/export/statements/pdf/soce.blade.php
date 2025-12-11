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
        <h1 style="text-align: center;"><strong>STATEMENT OF CHANGES IN EQUITY</strong></h1>
        <h1 style="text-align: center;"><strong>FOR THE YEAR ENDED {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></h1>

        <table class="table table-hover table-borderless table-sm mb-3" style="width: 100%;">
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
                @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                    <tr>
                        <td><strong>Balance as at {{ \Carbon\Carbon::parse($company->start_date)->format('M d, Y') }}</strong></td>
                        <td style="text-align: center;" class="text-bold scb_previous_year" data-content="@if($opening_capital['closing_debit']) {{ round($opening_capital['closing_debit']) }} @else {{ round($opening_capital['closing_credit']) }} @endif">
                            @if ($opening_capital['closing_debit'] != 0)
                                {{ number_format(abs(round($opening_capital['closing_debit'])), 0, '.', ',') }}
                            @else
                                {{ number_format(abs(round($opening_capital['closing_credit'])), 0, '.', ',') }}
                            @endif
                        </td>
                        <td style="text-align: center;" class="text-bold aplb_previous_year editable" contenteditable="true" data-content="{{ round($aplb_previous_year->meta_value ?? 0) }}">{{ round($aplb_previous_year->meta_value ?? 0) }}</td>
                        <td style="text-align: center;" class="text-bold tb_previous_year" data-content="0">{{ number_format(0, 0, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <td>{{ ( $otherComprehensiveIncome['previous_year'] >= 0 ) ? 'Total comprehensive income' : 'Total comprehensive loss' }}</td>
                        <td style="text-align: center;" class="sctc_previous_year" data-content="0">0</td>
                        <td style="text-align: center;" class="apltc_previous_year" data-content="{{ $otherComprehensiveIncome['previous_year'] }}">{{ ($otherComprehensiveIncome['previous_year'] < 0) ? '('. number_format(abs(round($otherComprehensiveIncome['previous_year'])), 0, '.', ',') .')' : number_format(abs(round($otherComprehensiveIncome['previous_year'])), 0, '.', ',') }}</td>
                        <td style="text-align: center;" class="ttc_previous_year" data-content="0">{{ number_format(0, 0, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <td>Capital Injection</td>
                        <td style="text-align: center;" class="scci_previous_year editable" contenteditable="true" data-content="{{ round($scci_previous_year->meta_value ?? 0) }}">{{ round($scci_previous_year->meta_value ?? 0) }}</td>
                        <td style="text-align: center;" class="aplci_previous_year" data-content="0">0</td>
                        <td style="text-align: center;" class="tci_previous_year" data-content="0">0</td>
                    </tr>
                    <tr>
                        <td>Drawings</td>
                        <td style="text-align: center;" class="scd_previous_year editable" contenteditable="true" data-content="{{ round($scd_previous_year->meta_value ?? 0) }}">{{ round($scd_previous_year->meta_value ?? 0) }}</td>
                        <td style="text-align: center;" class="apld_previous_year" data-content="0">0</td>
                        <td style="text-align: center;" class="td_previous_year" data-content="0">0</td>
                    </tr>
                    <tr>
                        <td><strong>Balance as at {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></td>
                        <td style="text-align: center;" class="text-bold tsc_previous_year" data-content="0" style="border-top: 2px solid #000; border-bottom: 4px double #000;">{{ number_format(0, 0, '.', ',') }}</td>
                        <td style="text-align: center;" class="text-bold tapl_previous_year" data-content="0" style="border-top: 2px solid #000; border-bottom: 4px double #000;">{{ (0 < 0) ? '('. number_format(abs(0), 0, '.', ',') .')' : number_format(abs(0), 0, '.', ',') }}</td>
                        <td style="text-align: center;" class="text-bold tt_previous_year" data-content="0" style="border-top: 2px solid #000; border-bottom: 4px double #000;">{{ number_format(0, 0, '.', ',') }}</td>
                    </tr>
                @endif
                
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
