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
                    <th style="text-align: center; border: 2px solid #000; width: 15%;">Share capital</th>
                    <th style="text-align: center; border: 2px solid #000; width: 25%;">Accumulated Profit/(losses)</th>
                    <th style="text-align: center; border: 2px solid #000; width: 10%;">Total</th>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;"><strong>RUPEES</strong></td>
                    <td style="text-align: center;"><strong>RUPEES</strong></td>
                    <td style="text-align: center;"><strong>RUPEES</strong></td>
                    {{-- <td class="text-center header-rupee" colspan="3"><strong><span>RUPEES</span></strong></td> --}}
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
                        <td style="text-align: right; font-weight: bold;" class="scb_previous_year">
                            @if ( $opening_capital['previous_year'] < 0 )
                                ({{ number_format(abs(round($opening_capital['previous_year'])), 0, '.', ',') }})
                            @elseif ( $opening_capital['previous_year'] > 0 )
                                {{ number_format(abs(round($opening_capital['previous_year'])), 0, '.', ',') }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-align: right; font-weight: bold;" class="aplb_previous_year">
                            @if ( ($aplb_previous_year->meta_value ?? 0) < 0 )
                                ({{ round($aplb_previous_year->meta_value ?? 0) }})
                            @elseif ( ($aplb_previous_year->meta_value ?? 0) > 0 )
                                {{ round($aplb_previous_year->meta_value ?? 0) }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-align: right; font-weight: bold;" class="tb_previous_year">
                            @if (($opening_capital['previous_year'] + ($aplb_previous_year->meta_value ?? 0)) < 0)
                                ({{ number_format(abs(round(($opening_capital['previous_year'] + ($aplb_previous_year->meta_value ?? 0)))), 0, '.', ',') }})
                            @elseif ( ($opening_capital['previous_year'] + ($aplb_previous_year->meta_value ?? 0)) > 0 )
                                {{ number_format(abs(round(($opening_capital['previous_year'] + ($aplb_previous_year->meta_value ?? 0)))), 0, '.', ',') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @if ( $totalComprehensiveProfitLoss['previous_year'] != 0 )
                        <tr>
                            <td>{{ ( $totalComprehensiveProfitLoss['previous_year'] >= 0 ) ? 'Total comprehensive income' : 'Total comprehensive loss' }}</td>
                            <td style="text-align: right;" class="sctc_previous_year">-</td>
                            <td style="text-align: right;" class="apltc_previous_year">
                                @if ($totalComprehensiveProfitLoss['previous_year'] < 0)
                                    ({{ number_format(abs(round($totalComprehensiveProfitLoss['previous_year'])), 0, '.', ',') }})
                                @elseif ($totalComprehensiveProfitLoss['previous_year'] > 0)
                                    {{ number_format(abs(round($totalComprehensiveProfitLoss['previous_year'])), 0, '.', ',') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="text-align: right;" class="ttc_previous_year">
                                @if ($totalComprehensiveProfitLoss['previous_year'] < 0)
                                    ({{ number_format(abs(round($totalComprehensiveProfitLoss['previous_year'])), 0, '.', ',') }})
                                @elseif ($totalComprehensiveProfitLoss['previous_year'] > 0)
                                    {{ number_format(abs(round($totalComprehensiveProfitLoss['previous_year'])), 0, '.', ',') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endif
                    @if ( $capital_injection['previous_year'] != 0 )
                        <tr>
                            <td>Capital Injection</td>
                            <td style="text-align: right;" class="scci_previous_year">
                                @if ($capital_injection['previous_year'] < 0)
                                    ({{ number_format(abs(round($capital_injection['previous_year'])), 0, '.', ',') }})
                                @elseif ($capital_injection['previous_year'] > 0)
                                    {{ number_format(abs(round($capital_injection['previous_year'])), 0, '.', ',') }}
                                @else
                                    -
                                @endif
                                {{-- {{ round($capital_injection['previous_year'] ?? 0) }} --}}
                            </td>
                            <td style="text-align: right;" class="aplci_previous_year">-</td>
                            <td style="text-align: right;" class="tci_previous_year">
                                @if ($capital_injection['previous_year'] < 0)
                                    ({{ number_format(abs(round($capital_injection['previous_year'])), 0, '.', ',') }})
                                @elseif ($capital_injection['previous_year'] > 0)
                                    {{ number_format(abs(round($capital_injection['previous_year'])), 0, '.', ',') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endif
                    @if ( $drawings['previous_year'] != 0 )
                        <tr>
                            <td>Drawings</td>
                            <td style="text-align: right;" class="scd_previous_year">
                                @if ($drawings['previous_year'] < 0)
                                    ({{ number_format(abs(round($drawings['previous_year'])), 0, '.', ',') }})
                                @elseif ($drawings['previous_year'] > 0)
                                    {{ number_format(abs(round($drawings['previous_year'])), 0, '.', ',') }}
                                @else
                                    -
                                @endif
                                {{-- {{ round($drawings['previous_year'] ?? 0) }} --}}
                            </td>
                            <td style="text-align: right;" class="apld_previous_year">-</td>
                            <td style="text-align: right;" class="td_previous_year">
                                @if ($drawings['previous_year'] < 0)
                                    ({{ number_format(abs(round($drawings['previous_year'])), 0, '.', ',') }})
                                @elseif ($drawings['previous_year'] > 0)
                                    {{ number_format(abs(round($drawings['previous_year'])), 0, '.', ',') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td><strong>Balance as at {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></td>
                        <td class="tsc_previous_year" style="text-align: right; font-weight: bold; border-top: 2px solid #000; border-bottom: 4px double #000;">
                            @php
                                $shareCapitalPreviousYear = $opening_capital['previous_year'] + $capital_injection['previous_year'] + $drawings['previous_year'];
                            @endphp
                            @if ($shareCapitalPreviousYear < 0)
                                ({{ number_format(abs(round($shareCapitalPreviousYear)), 0, '.', ',') }})
                            @elseif ($shareCapitalPreviousYear > 0)
                                {{ number_format(abs(round($shareCapitalPreviousYear)), 0, '.', ',') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="tapl_previous_year" style="text-align: right; font-weight: bold; border-top: 2px solid #000; border-bottom: 4px double #000;">
                            @php
                                $accumulatedProfitLossPreviousYear = ($aplb_previous_year->meta_value ?? 0) + $totalComprehensiveProfitLoss['previous_year'];
                            @endphp
                            @if ($accumulatedProfitLossPreviousYear < 0)
                                ({{ number_format(abs(round($accumulatedProfitLossPreviousYear)), 0, '.', ',') }})
                            @elseif ($accumulatedProfitLossPreviousYear > 0)
                                {{ number_format(abs(round($accumulatedProfitLossPreviousYear)), 0, '.', ',') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="tt_previous_year" style="text-align: right; font-weight: bold; border-top: 2px solid #000; border-bottom: 4px double #000;">
                            @if (($shareCapitalPreviousYear + $accumulatedProfitLossPreviousYear) < 0)
                                ({{ number_format(abs(round(($shareCapitalPreviousYear + $accumulatedProfitLossPreviousYear))), 0, '.', ',') }})
                            @elseif (($shareCapitalPreviousYear + $accumulatedProfitLossPreviousYear) > 0)
                                {{ number_format(abs(round(($shareCapitalPreviousYear + $accumulatedProfitLossPreviousYear))), 0, '.', ',') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endif

                <tr>
                    <td><strong>Balance as at {{ \Carbon\Carbon::parse($company->start_date)->format('M d, Y') }}</strong></td>
                    <td style="text-align: right; font-weight: bold;" class="scb_current_year">
                        @php
                            $shareCapitalPreviousYear = $opening_capital['previous_year'] + $capital_injection['previous_year'] + $drawings['previous_year'];
                        @endphp
                        @if ($shareCapitalPreviousYear < 0)
                            ({{ number_format(abs(round($shareCapitalPreviousYear)), 0, '.', ',') }})
                        @elseif ($shareCapitalPreviousYear > 0)
                            {{ number_format(abs(round($shareCapitalPreviousYear)), 0, '.', ',') }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align: right; font-weight: bold;" class="aplb_current_year">
                        @php
                            $accumulatedProfitLossPreviousYear = ($aplb_previous_year->meta_value ?? 0) + $totalComprehensiveProfitLoss['previous_year'];
                        @endphp
                        @if ($accumulatedProfitLossPreviousYear < 0)
                            ({{ number_format(abs(round($accumulatedProfitLossPreviousYear)), 0, '.', ',') }})
                        @elseif ($accumulatedProfitLossPreviousYear > 0)
                            {{ number_format(abs(round($accumulatedProfitLossPreviousYear)), 0, '.', ',') }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align: right; font-weight: bold;" class="tb_current_year">
                        @if (($shareCapitalPreviousYear + $accumulatedProfitLossPreviousYear) < 0)
                            ({{ number_format(abs(round(($shareCapitalPreviousYear + $accumulatedProfitLossPreviousYear))), 0, '.', ',') }})
                        @elseif (($shareCapitalPreviousYear + $accumulatedProfitLossPreviousYear) > 0)
                            {{ number_format(abs(round(($shareCapitalPreviousYear + $accumulatedProfitLossPreviousYear))), 0, '.', ',') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @if ( $totalComprehensiveProfitLoss['current_year'] != 0 )
                    <tr>
                        <td>{{ ( $totalComprehensiveProfitLoss['current_year'] >= 0 ) ? 'Total comprehensive income' : 'Total comprehensive loss' }}</td>
                        <td style="text-align: right;" class="sctc_current_year">-</td>
                        <td style="text-align: right;" class="apltc_current_year">
                            @if ($totalComprehensiveProfitLoss['current_year'] < 0)
                                ({{ number_format(abs(round($totalComprehensiveProfitLoss['current_year'])), 0, '.', ',') }})
                            @elseif ($totalComprehensiveProfitLoss['current_year'] > 0)
                                {{ number_format(abs(round($totalComprehensiveProfitLoss['current_year'])), 0, '.', ',') }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-align: right;" class="ttc_current_year">
                            @if ($totalComprehensiveProfitLoss['current_year'] < 0)
                                ({{ number_format(abs(round($totalComprehensiveProfitLoss['current_year'])), 0, '.', ',') }})
                            @elseif ($totalComprehensiveProfitLoss['current_year'] > 0)
                                {{ number_format(abs(round($totalComprehensiveProfitLoss['current_year'])), 0, '.', ',') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endif
                @if ( $capital_injection['current_year'] != 0 )
                    <tr>
                        <td>Capital Injection</td>
                        <td style="text-align: right;" class="scci_current_year">
                            @if ($capital_injection['current_year'] < 0)
                                ({{ number_format(abs(round($capital_injection['current_year'])), 0, '.', ',') }})
                            @elseif ($capital_injection['current_year'] > 0)
                                {{ number_format(abs(round($capital_injection['current_year'])), 0, '.', ',') }}
                            @else
                                -
                            @endif
                            {{-- {{ $capital_injection['current_year'] }} --}}
                        </td>
                        <td style="text-align: right;" class="aplci_current_year">-</td>
                        <td style="text-align: right;" class="tci_current_year">
                            @if ($capital_injection['current_year'] < 0)
                                ({{ number_format(abs(round($capital_injection['current_year'])), 0, '.', ',') }})
                            @elseif ($capital_injection['current_year'] > 0)
                                {{ number_format(abs(round($capital_injection['current_year'])), 0, '.', ',') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endif
                @if ( $drawings['current_year'] != 0 )
                    <tr>
                        <td>Drawings</td>
                        <td style="text-align: right;" class="scd_current_year">
                            @if ($drawings['current_year'] < 0)
                                ({{ number_format(abs(round($drawings['current_year'])), 0, '.', ',') }})
                            @elseif ($drawings['current_year'] > 0)
                                {{ number_format(abs(round($drawings['current_year'])), 0, '.', ',') }}
                            @else
                                -
                            @endif
                            {{-- {{ number_format(round(abs($drawings['current_year'])), 0, '.', ',') }} --}}
                        </td>
                        <td style="text-align: right;" class="apld_current_year">-</td>
                        <td style="text-align: right;" class="td_current_year">
                            @if ($drawings['current_year'] < 0)
                                ({{ number_format(abs(round($drawings['current_year'])), 0, '.', ',') }})
                            @elseif ($drawings['current_year'] > 0)
                                {{ number_format(abs(round($drawings['current_year'])), 0, '.', ',') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endif
                <tr>
                    <td><strong>Balance as at {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></td>
                    <td class="tsc_current_year" style="text-align: right; border-top: 2px solid #000; border-bottom: 4px double #000;">
                        @php
                            $shareCapitalCurrentYear = $shareCapitalPreviousYear + $capital_injection['current_year'] + $drawings['current_year'];
                        @endphp
                        @if ($shareCapitalCurrentYear < 0)
                            ({{ number_format(abs(round($shareCapitalCurrentYear)), 0, '.', ',') }})
                        @elseif ($shareCapitalCurrentYear > 0)
                            {{ number_format(abs(round($shareCapitalCurrentYear)), 0, '.', ',') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="tapl_current_year" style="text-align: right; border-top: 2px solid #000; border-bottom: 4px double #000;">
                        @php
                            $accumulatedProfitLossCurrentYear = $accumulatedProfitLossPreviousYear + $totalComprehensiveProfitLoss['current_year'];
                        @endphp
                        @if ($accumulatedProfitLossCurrentYear < 0)
                            ({{ number_format(abs(round($accumulatedProfitLossCurrentYear)), 0, '.', ',') }})
                        @elseif ($accumulatedProfitLossCurrentYear > 0)
                            {{ number_format(abs(round($accumulatedProfitLossCurrentYear)), 0, '.', ',') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="tt_current_year" style="text-align: right; border-top: 2px solid #000; border-bottom: 4px double #000;">
                        @if (($shareCapitalCurrentYear + $accumulatedProfitLossCurrentYear) < 0)
                            ({{ number_format(abs(round(($shareCapitalCurrentYear + $accumulatedProfitLossCurrentYear))), 0, '.', ',') }})
                        @elseif (($shareCapitalCurrentYear + $accumulatedProfitLossCurrentYear) > 0)
                            {{ number_format(abs(round(($shareCapitalCurrentYear + $accumulatedProfitLossCurrentYear))), 0, '.', ',') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="width: 100%; text-align: left;">The annexed notes from 1 to {{ $lastIndex }} form an integral part of these financial statements.</p>
        @if ($company->account_type == 'Proprietor')
            <div style="position: fixed; bottom: -60px; left: 0; right: 0; width: 100%; height: 50px; display: flex; align-items: center; justify-content: space-between;">
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: left;">Proprietor</span>
            </div>
        @endif
        @if ($company->account_type == 'AOP')
            <div style="position: fixed; bottom: -60px; left: 0; right: 0; width: 100%; height: 50px; display: flex; align-items: center; justify-content: space-between;">
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: left;">Partner</span>
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: right;">Partner</span>
            </div>
        @endif
        @if ($company->account_type == 'Company')
            <div style="position: fixed; bottom: -60px; left: 0; right: 0; width: 100%; height: 50px; display: flex; align-items: center; justify-content: space-between;">
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: left;">CEO</span>
                <span style="border-top: 2px solid #000; flex: 0 0 10%; width: 10%; float: right;">Director</span>
            </div>
        @endif
    </body>
</html>
