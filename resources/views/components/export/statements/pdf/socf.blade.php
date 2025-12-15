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
        <h1 style="text-align: center;"><strong>STATEMENT OF CASH FLOWS</strong></h1>
        <h1 style="text-align: center;"><strong>FOR THE YEAR ENDED {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></h1>

        <table class="table table-hover table-borderless table-sm mb-3" style="width: 100%;">
            <thead>
                <tr>
                    <th></th>
                    <th style="text-align: center; width: 5%;">Note</th>
                    <th style="text-align: center; width: 10%;">{{ \Carbon\Carbon::parse($company->end_date)->format('Y') }}</th>
                    <th style="text-align: center; width: 10%;">{{ \Carbon\Carbon::parse($company->start_date)->format('Y') }}</th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align: center; border-top: 2px solid #000;"><strong>RUPEES</strong></td>
                    <td style="text-align: center; border-top: 2px solid #000;"><strong>RUPEES</strong></td>
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
                    <td style="text-align: center;"></td>
                    <td style="text-align: right;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                    <td style="text-align: right;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
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
                    <td style="text-align: center;"></td>
                    <td style="text-align: right;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                    <td style="text-align: right;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
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
                    <td></td>
                    <td style="text-align: right; border-left: 1px solid #000; border-top: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                    <td style="text-align: right; border-right: 1px solid #000; border-top: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                </tr>
                <tr>
                    <td>Advances, deposits and other receivables</td>
                    <td></td>
                    <td style="text-align: right; border-left: 1px solid #000; border-bottom: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                    <td style="text-align: right; border-right: 1px solid #000; border-bottom: 1px solid #000;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                </tr>
                <tr>
                    @php
                        $total_assets_current_year = (0 - 0);
                        $total_assets_previous_year = (0 - 0);
                    @endphp
                    <td></td>
                    <td></td>
                    <td style="text-align: right; border-bottom: 5px double #000;"><strong>{{ ($total_assets_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($total_assets_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($total_assets_current_year), 2), '0'), '.') }}</strong></td>
                    <td style="text-align: right; border-bottom: 5px double #000;"><strong>{{ ($total_assets_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($total_assets_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($total_assets_previous_year), 2), '0'), '.') }}</strong></td>
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
                    <td></td>
                    <td style="text-align: right; border-left: 1px solid #000; border-top: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                    <td style="text-align: right; border-right: 1px solid #000; border-top: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                </tr>
                <tr>
                    <td>Trade creditor and other payables</td>
                    <td></td>
                    <td style="text-align: right; border-left: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                    <td style="text-align: right; border-right: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                </tr>
                <tr>
                    <td>Accrued and other liabilities</td>
                    <td></td>
                    <td style="text-align: right; border-left: 1px solid #000; border-bottom: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                    <td style="text-align: right; border-right: 1px solid #000; border-bottom: 1px solid #000;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                </tr>
                <tr>
                    @php
                        $pbt_current_year = 0;
                        $pbt_previous_year = 0;
                    @endphp
                    <td></td>
                    <td></td>
                    <td style="text-align: right;"><strong>{{ ($pbt_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($pbt_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($pbt_current_year), 2), '0'), '.') }}</strong></td>
                    <td style="text-align: right;"><strong>{{ ($pbt_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($pbt_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($pbt_previous_year), 2), '0'), '.') }}</strong></td>
                </tr>
                <tr>
                    @php
                        $pbt_current_year = 0;
                        $pbt_previous_year = 0;
                    @endphp
                    <td><strong>Cash (used in)/generated from operations</strong></td>
                    <td></td>
                    <td style="text-align: right; border-top: 2px solid #000;"><strong>{{ ($pbt_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($pbt_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($pbt_current_year), 2), '0'), '.') }}</strong></td>
                    <td style="text-align: right; border-top: 2px solid #000;"><strong>{{ ($pbt_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($pbt_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($pbt_previous_year), 2), '0'), '.') }}</strong></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Income tax paid</td>
                    <td></td>
                    <td style="text-align: right;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                    <td style="text-align: right;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                </tr>
                <tr>
                    @php
                        $tel_current_year = (0 - 0);
                        $tel_previous_year = (0 - 0);
                    @endphp
                    <td><strong>Net cash (used in)/generated from operating activities</strong></td>
                    <td style="text-align: center;"><strong>A</strong></td>
                    <td style="text-align: right; border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') }}</strong></td>
                    <td style="text-align: right; border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') }}</strong></td>
                </tr>
                <tr>
                    <td><strong>CASH FLOW FROM INVESTING ACTIVITIES</strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                
                <tr>
                    <td>Purchase of Property, plant and equipments</td>
                    <td></td>
                    <td style="text-align: right;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                    <td style="text-align: right;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                </tr>
                <tr>
                    <td>Capital work in process</td>
                    <td></td>
                    <td style="text-align: right;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                    <td style="text-align: right;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                </tr>
                <tr>
                    @php
                        $tel_current_year = (0 - 0);
                        $tel_previous_year = (0 - 0);
                    @endphp
                    <td><strong>Net cash generated from/(used in) invensting activities</strong></td>
                    <td style="text-align: center;"><strong>B</strong></td>
                    <td style="text-align: right; border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') }}</strong></td>
                    <td style="text-align: right; border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') }}</strong></td>
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
                    <td></td>
                    <td style="text-align: right;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                    <td style="text-align: right;">{{ (0 < 0) ? '('. rtrim(rtrim(number_format(abs(0), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs(0), 2), '0'), '.') }}</td>
                </tr>
                <tr>
                    @php
                        $tel_current_year = (0 - 0);
                        $tel_previous_year = (0 - 0);
                    @endphp
                    <td><strong>Net cash generated from financing activities</strong></td>
                    <td style="text-align: center;"><strong>C</strong></td>
                    <td style="text-align: right; border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') }}</strong></td>
                    <td style="text-align: right; border-top: 2px solid #000; border-bottom: 5px double #000;"><strong>{{ ($tel_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') }}</strong></td>
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
                    <td style="text-align: center;"><strong>A+B+C</strong></td>
                    <td style="text-align: right;">{{ ($tel_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_current_year), 2), '0'), '.') }}</td>
                    <td style="text-align: right;">{{ ($tel_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($tel_previous_year), 2), '0'), '.') }}</td>
                </tr>
                <tr>
                    <td>Cash and cash equivalents at the beginning of year</td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: right;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                    <td style="text-align: right;">({{ rtrim(rtrim(number_format(0, 2), '0'), '.') }})</td>
                </tr>
                <tr>
                    @php
                        $pbt_current_year = 0;
                        $pbt_previous_year = 0;
                    @endphp
                    <td><strong>CASH AND CASH EQUIVALENTS AT THE END OF YEAR</strong></td>
                    <td><strong>8</strong></td>
                    <td style="text-align: right; border-top: 2px solid #000; border-bottom: 5px solid #000;"><strong>{{ ($pbt_current_year < 0) ? '('. rtrim(rtrim(number_format(abs($pbt_current_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($pbt_current_year), 2), '0'), '.') }}</strong></td>
                    <td style="text-align: right; border-top: 2px solid #000; border-bottom: 5px solid #000;"><strong>{{ ($pbt_previous_year < 0) ? '('. rtrim(rtrim(number_format(abs($pbt_previous_year), 2), '0'), '.') .')' : rtrim(rtrim(number_format(abs($pbt_previous_year), 2), '0'), '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
        <div>
            <span style="width: 100%; max-width: 100%; content-align: left;">The annexed notes from 1 to {{ $lastIndex }} form an integral part of these financial statements.</span>
        </div>
        <div>
            @if ($company->account_type == 'Proprietor')
                <div style="position: fixed; bottom: -60px; left: 0; right: 0; width: 100%; height: 50px; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top: 100px;">
                    <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: left;">Proprietor</span>
                </div>
            @endif
            @if ($company->account_type == 'AOP')
                <div style="position: fixed; bottom: -60px; left: 0; right: 0; width: 100%; height: 50px; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top: 100px;">
                    <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: left;">Partner</span>
                    <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: right;">Partner</span>
                </div>
            @endif
            @if ($company->account_type == 'Company')
                <div style="position: fixed; bottom: -60px; left: 0; right: 0; width: 100%; height: 50px; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top: 100px;">
                    <span style="border-top: 2px solid #000; width: 25%; float: left;">Chief Execuitive Officer</span>
                    <span style="border-top: 2px solid #000;  width: 10%; float: right;">Director</span>
                </div>
            @endif
        </div>
    </body>
</html>
