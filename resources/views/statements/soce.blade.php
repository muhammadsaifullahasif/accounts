@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Statement</h1>
                    <a href="{{ route('statements.soce.export.pdf', $company->id) }}" class="btn btn-primary btn-sm mb-3">Export PDF</a>
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
            <p class="text-center mb-0"><strong>STATEMENT OF CHANGES IN EQUITY</strong></p>
            <p class="text-center mb-0"><strong>FOR THE YEAR ENDED {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-borderless table-sm mb-3">
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
                                <td class="text-center text-bold scb_previous_year">
                                    {{ number_format(abs(round($opening_capital['previous_year'])), 0, '.', ',') }}
                                </td>
                                <td class="text-center text-bold aplb_previous_year">{{ round($aplb_previous_year->meta_value ?? 0) }}</td>
                                <td class="text-center text-bold tb_previous_year">{{ number_format(0, 0, '.', ',') }}</td>
                            </tr>
                            <tr>
                                <td>{{ ( $totalComprehensiveProfitLoss['previous_year'] >= 0 ) ? 'Total comprehensive income' : 'Total comprehensive loss' }}</td>
                                <td class="text-center sctc_previous_year">0</td>
                                <td class="text-center apltc_previous_year">
                                    @if ($totalComprehensiveProfitLoss['previous_year'] < 0)
                                        ({{ number_format(abs(round($totalComprehensiveProfitLoss['previous_year'])), 0, '.', ',') }})
                                    @else
                                        {{ number_format(abs(round($totalComprehensiveProfitLoss['previous_year'])), 0, '.', ',') }}
                                    @endif
                                </td>
                                <td class="text-center ttc_previous_year">{{ number_format(0, 0, '.', ',') }}</td>
                            </tr>
                            <tr>
                                <td>Capital Injection</td>
                                <td class="text-center scci_previous_year">
                                    @if ($capital_injection['previous_year'] < 0)
                                        ({{ number_format(abs(round($capital_injection['previous_year'])), 0, '.', ',') }})
                                    @else
                                        {{ number_format(abs(round($capital_injection['previous_year'])), 0, '.', ',') }}
                                    @endif
                                    {{-- {{ round($capital_injection['previous_year'] ?? 0) }} --}}
                                </td>
                                <td class="text-center aplci_previous_year">0</td>
                                <td class="text-center tci_previous_year">0</td>
                            </tr>
                            <tr>
                                <td>Drawings</td>
                                <td class="text-center scd_previous_year">
                                    @if ($drawings['previous_year'] < 0)
                                        ({{ number_format(abs(round($drawings['previous_year'])), 0, '.', ',') }})
                                    @else
                                        {{ number_format(abs(round($drawings['previous_year'])), 0, '.', ',') }}
                                    @endif
                                    {{-- {{ round($drawings['previous_year'] ?? 0) }} --}}
                                </td>
                                <td class="text-center apld_previous_year">0</td>
                                <td class="text-center td_previous_year">0</td>
                            </tr>
                            <tr>
                                <td><strong>Balance as at {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></td>
                                <td class="text-center text-bold tsc_previous_year" style="border-top: 2px solid #000; border-bottom: 4px double #000;">{{ number_format(0, 0, '.', ',') }}</td>
                                <td class="text-center text-bold tapl_previous_year" style="border-top: 2px solid #000; border-bottom: 4px double #000;">
                                    @if (0 < 0)
                                        ({{ number_format(abs(0), 0, '.', ',') }})
                                    @else
                                        {{ number_format(abs(0), 0, '.', ',') }}
                                    @endif
                                </td>
                                <td class="text-center text-bold tt_previous_year" style="border-top: 2px solid #000; border-bottom: 4px double #000;">{{ number_format(0, 0, '.', ',') }}</td>
                            </tr>
                        @endif

                        <tr>
                            <td><strong>Balance as at {{ \Carbon\Carbon::parse($company->start_date)->format('M d, Y') }}</strong></td>
                            <td class="text-center text-bold scb_current_year">{{ number_format(0, 0, '.', ',') }}</td>
                            <td class="text-center text-bold aplb_current_year">{{ (0 < 0) ? '('. number_format(abs(0), 0, '.', ',') .')' : number_format(abs(0), 0, '.', ',') }}</td>
                            <td class="text-center text-bold tb_current_year">{{ number_format(0, 0, '.', ',') }}</td>
                        </tr>
                        <tr>
                            <td>{{ ( $totalComprehensiveProfitLoss['current_year'] >= 0 ) ? 'Total comprehensive income' : 'Total comprehensive loss' }}</td>
                            <td class="text-center sctc_current_year">0</td>
                            <td class="text-center apltc_current_year">
                                @if ($totalComprehensiveProfitLoss['current_year'] < 0)
                                    ({{ number_format(abs(round($totalComprehensiveProfitLoss['current_year'])), 0, '.', ',') }})
                                @else
                                    {{ number_format(abs(round($totalComprehensiveProfitLoss['current_year'])), 0, '.', ',') }}
                                @endif
                            </td>
                            <td class="text-center ttc_current_year">{{ number_format(0, 0, '.', ',') }}</td>
                        </tr>
                        <tr>
                            <td>Capital Injection</td>
                            <td class="text-center scci_current_year">
                                @if ($capital_injection['current_year'] < 0)
                                    ({{ number_format(abs(round($capital_injection['current_year'])), 0, '.', ',') }})
                                @else
                                    {{ number_format(abs(round($capital_injection['current_year'])), 0, '.', ',') }}
                                @endif
                                {{-- {{ $capital_injection['current_year'] }} --}}
                            </td>
                            <td class="text-center aplci_current_year">0</td>
                            <td class="text-center tci_current_year">0</td>
                        </tr>
                        <tr>
                            <td>Drawings</td>
                            <td class="text-center scd_current_year">
                                @if ($drawings['current_year'] < 0)
                                    ({{ number_format(abs(round($drawings['current_year'])), 0, '.', ',') }})
                                @else
                                    {{ number_format(abs(round($drawings['current_year'])), 0, '.', ',') }}
                                @endif
                                {{-- {{ number_format(round(abs($drawings['current_year'])), 0, '.', ',') }} --}}
                            </td>
                            <td class="text-center apld_current_year">0</td>
                            <td class="text-center td_current_year">0</td>
                        </tr>
                        <tr>
                            <td><strong>Balance as at {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</strong></td>
                            <td class="text-center tsc_current_year" style="border-top: 2px solid #000; border-bottom: 4px double #000;">{{ number_format(0, 0, '.', ',') }}</td>
                            <td class="text-center tapl_current_year" style="border-top: 2px solid #000; border-bottom: 4px double #000;">{{ (0 < 0) ? '('. number_format(abs(0), 0, '.', ',') .')' : number_format(abs(0), 0, '.', ',') }}</td>
                            <td class="text-center tt_current_year" style="border-top: 2px solid #000; border-bottom: 4px double #000;">{{ number_format(0, 0, '.', ',') }}</td>
                        </tr>
                    </tbody>
                </table>
                <p>The annexed notes from 1 to {{ $lastIndex }} form an integral part of these financial statements.</p>
            </div>
            @if (in_array('SOFP', explode(',', $company->required_statements)))
            <a href="{{ route('statements.sofp', $company->id) }}" class="btn btn-primary">Next Statement</a>
            @elseif (in_array('SOCF', explode(',', $company->required_statements)))
            <a href="{{ route('statements.socf', $company->id) }}" class="btn btn-primary">Next Statement</a>
            @endif
            {{-- <button id="saveSOCEbtn" class="btn btn-primary">Save</button> --}}
            {{-- @if (($company->company_meta['comparative_accounts'] ?? 'Yes') == 'Yes')
                <button id="saveSOCEbtn" class="btn btn-primary">Save</button>
            @else
                @if (in_array('SOFP', explode(',', $company->required_statements)))
                    <a href="{{ route('statements.sofp', $company->id) }}" class="btn btn-primary">Next Statement</a>
                @elseif (in_array('SOCF', explode(',', $company->required_statements)))
                    <a href="{{ route('statements.socf', $company->id) }}" class="btn btn-primary">Next Statement</a>
                @endif
            @endif --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const parseValue = (value) => {
            const v = value.trim();
            return (v.startsWith('(') && v.endsWith(')') ? -1 : 1) * (parseFloat(v.replace(/[(),]/g, '')) || 0);
        };

        $(document).ready(function(){

            $(document).on('focus', '.editable', function() {
                if (parseValue($(this).text()) == 0) {
                    $(this).text('');
                }
            });

            $(document).on('blur', '.editable', function() {
                if (parseValue($(this).text()) == '') {
                    $(this).text(0);
                }
            });

            $('#saveSOCEbtn').on('click', function() {
                var aplb_previous_year = parseFloat(parseValue($('.aplb_previous_year').text())) || 0;
                var scci_previous_year = parseFloat(parseValue($('.scci_previous_year').text())) || 0;
                var scd_previous_year = parseFloat(parseValue($('.scd_previous_year').text())) || 0;

                // Send to server via AJAX
                $.ajax({
                    url: '{{ route("statements.soce.update", $company->id) }}',
                    method: 'PUT',
                    data: { 
                        _token: '{{ csrf_token() }}',
                        soce_aplb: aplb_previous_year,
                        soce_scci: scci_previous_year,
                        soce_scd: scd_previous_year, 
                    },
                    success: function(response) {
                        alert('Entries saved successfully!');
                        // location.reload();
                        @if (in_array('SOFP', explode(',', $company->required_statements)))
                            window.location.href = '{{ route("statements.sofp", $company->id) }}';
                        @elseif (in_array('SOCF', explode(',', $company->required_statements)))
                            window.location.href = '{{ route("statements.socf", $company->id) }}';
                        @else
                            location.reload();
                        @endif
                    },
                    error: function(xhr) {
                        alert('An error occurred while saving entries.');
                    }
                });
            });

            $(document).on('keyup change', '.editable', calculateTotal);

            function calculateTotal()
            {
                var scb_previous_year = {{ $opening_capital['previous_year'] }};
                var aplb_previous_year = {{ $aplb_previous_year->meta_value ?? 0 }};
                var tb_previous_year = scb_previous_year + aplb_previous_year;
                if (tb_previous_year < 0) {
                    $('.tb_previous_year').html('(' + (Math.abs(tb_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tb_previous_year').html((tb_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var sctc_previous_year = 0;
                var apltc_previous_year = {{ $totalComprehensiveProfitLoss['previous_year'] }};
                var ttc_previous_year = sctc_previous_year + apltc_previous_year;
                if (ttc_previous_year < 0) {
                    $('.ttc_previous_year').html('(' + (Math.abs(ttc_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.ttc_previous_year').html((ttc_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var scci_previous_year = {{ $capital_injection['previous_year'] }};
                var aplci_previous_year = 0;
                var tci_previous_year = scci_previous_year + aplci_previous_year;
                if (tci_previous_year < 0) {
                    $('.tci_previous_year').html('(' + (Math.abs(tci_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tci_previous_year').html((tci_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var scd_previous_year = {{ $drawings['previous_year'] }};
                var apld_previous_year = 0;
                var td_previous_year = scd_previous_year + apld_previous_year;
                if (td_previous_year < 0) {
                    $('.td_previous_year').html('(' + (Math.abs(td_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.td_previous_year').html((td_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var tsc_previous_year = (scb_previous_year + sctc_previous_year + scci_previous_year) + scd_previous_year;
                var tapl_previous_year = (aplb_previous_year + apltc_previous_year + aplci_previous_year) + apld_previous_year;
                var tt_previous_year = tsc_previous_year + tapl_previous_year;
                if (tsc_previous_year < 0) {
                    $('.tsc_previous_year').html('(' + (Math.abs(tsc_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tsc_previous_year').html((tsc_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
                if (tapl_previous_year < 0) {
                    $('.tapl_previous_year').html('(' + (Math.abs(tapl_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tapl_previous_year').html((tapl_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
                if (tt_previous_year < 0) {
                    $('.tt_previous_year').html('(' + (Math.abs(tt_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tt_previous_year').html((tt_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }


                var scb_current_year = tsc_previous_year;
                var aplb_current_year = tapl_previous_year;
                var tb_current_year = tt_previous_year;
                if (scb_current_year < 0) {
                    $('.scb_current_year').html('(' + (Math.abs(scb_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.scb_current_year').html((scb_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
                if (aplb_current_year < 0) {
                    $('.aplb_current_year').html('(' + (Math.abs(aplb_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.aplb_current_year').html((aplb_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
                if (tb_current_year < 0) {
                    $('.tb_current_year').html('(' + (Math.abs(tb_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tb_current_year').html((tb_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var sctc_current_year = parseFloat(parseValue($('.sctc_current_year').text())) || 0;
                var apltc_current_year = parseFloat(parseValue($('.apltc_current_year').text())) || 0;
                var ttc_current_year = sctc_current_year + apltc_current_year;
                if (ttc_current_year < 0) {
                    $('.ttc_current_year').html('(' + (Math.abs(ttc_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.ttc_current_year').html((ttc_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var scci_current_year = parseFloat(parseValue($('.scci_current_year').text())) || 0;
                var aplci_current_year = parseFloat(parseValue($('.aplci_current_year').text())) || 0;
                var tci_current_year = scci_current_year + aplci_current_year;
                if (tci_current_year < 0) {
                    $('.tci_current_year').html('(' + (Math.abs(tci_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tci_current_year').html((tci_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var scd_current_year = parseFloat(parseValue($('.scd_current_year').text())) || 0;
                var apld_current_year = parseFloat(parseValue($('.apld_current_year').text())) || 0;
                var td_current_year = scd_current_year + apld_current_year;
                if (td_current_year < 0) {
                    $('.td_current_year').html('(' + (Math.abs(td_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.td_current_year').html((td_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var tsc_current_year = (scb_current_year + sctc_current_year + scci_current_year) + scd_current_year;
                var tapl_current_year = (aplb_current_year + apltc_current_year + aplci_current_year) + apld_current_year;
                var tt_current_year = tsc_current_year + tapl_current_year;
                if (tsc_current_year < 0) {
                    $('.tsc_current_year').html('(' + (Math.abs(tsc_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tsc_current_year').html((tsc_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
                if (tapl_current_year < 0) {
                    $('.tapl_current_year').html('(' + (Math.abs(tapl_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tapl_current_year').html((tapl_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
                if (tt_current_year < 0) {
                    $('.tt_current_year').html('(' + (Math.abs(tt_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tt_current_year').html((tt_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
            }

            /*function calculateTotal()
            {
                var scb_previous_year = parseFloat(parseValue($('.scb_previous_year').text())) || 0;
                var aplb_previous_year = parseFloat(parseValue($('.aplb_previous_year').text())) || 0;
                var tb_previous_year = scb_previous_year + aplb_previous_year;
                if (tb_previous_year < 0) {
                    $('.tb_previous_year').html('(' + (Math.abs(tb_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tb_previous_year').html((tb_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var sctc_previous_year = parseFloat(parseValue($('.sctc_previous_year').text())) || 0;
                var apltc_previous_year = parseFloat(parseValue($('.apltc_previous_year').text())) || 0;
                var ttc_previous_year = sctc_previous_year + apltc_previous_year;
                if (ttc_previous_year < 0) {
                    $('.ttc_previous_year').html('(' + (Math.abs(ttc_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.ttc_previous_year').html((ttc_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var scci_previous_year = parseFloat(parseValue($('.scci_previous_year').text())) || 0;
                var aplci_previous_year = parseFloat(parseValue($('.aplci_previous_year').text())) || 0;
                var tci_previous_year = scci_previous_year + aplci_previous_year;
                if (tci_previous_year < 0) {
                    $('.tci_previous_year').html('(' + (Math.abs(tci_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tci_previous_year').html((tci_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var scd_previous_year = parseFloat(parseValue($('.scd_previous_year').text())) || 0;
                var apld_previous_year = parseFloat(parseValue($('.apld_previous_year').text())) || 0;
                var td_previous_year = scd_previous_year + apld_previous_year;
                if (td_previous_year < 0) {
                    $('.td_previous_year').html('(' + (Math.abs(td_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.td_previous_year').html((td_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var tsc_previous_year = (scb_previous_year + sctc_previous_year + scci_previous_year) - scd_previous_year;
                var tapl_previous_year = (aplb_previous_year + apltc_previous_year + aplci_previous_year) - apld_previous_year;
                var tt_previous_year = tsc_previous_year + tapl_previous_year;
                if (tsc_previous_year < 0) {
                    $('.tsc_previous_year').html('(' + (Math.abs(tsc_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tsc_previous_year').html((tsc_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
                if (tapl_previous_year < 0) {
                    $('.tapl_previous_year').html('(' + (Math.abs(tapl_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tapl_previous_year').html((tapl_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
                if (tt_previous_year < 0) {
                    $('.tt_previous_year').html('(' + (Math.abs(tt_previous_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tt_previous_year').html((tt_previous_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }


                var scb_current_year = tsc_previous_year;
                var aplb_current_year = tapl_previous_year;
                var tb_current_year = tt_previous_year;
                if (scb_current_year < 0) {
                    $('.scb_current_year').html('(' + (Math.abs(scb_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.scb_current_year').html((scb_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
                if (aplb_current_year < 0) {
                    $('.aplb_current_year').html('(' + (Math.abs(aplb_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.aplb_current_year').html((aplb_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
                if (tb_current_year < 0) {
                    $('.tb_current_year').html('(' + (Math.abs(tb_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tb_current_year').html((tb_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var sctc_current_year = parseFloat(parseValue($('.sctc_current_year').text())) || 0;
                var apltc_current_year = parseFloat(parseValue($('.apltc_current_year').text())) || 0;
                var ttc_current_year = sctc_current_year + apltc_current_year;
                if (ttc_current_year < 0) {
                    $('.ttc_current_year').html('(' + (Math.abs(ttc_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.ttc_current_year').html((ttc_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var scci_current_year = parseFloat(parseValue($('.scci_current_year').text())) || 0;
                var aplci_current_year = parseFloat(parseValue($('.aplci_current_year').text())) || 0;
                var tci_current_year = scci_current_year + aplci_current_year;
                if (tci_current_year < 0) {
                    $('.tci_current_year').html('(' + (Math.abs(tci_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tci_current_year').html((tci_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var scd_current_year = parseFloat(parseValue($('.scd_current_year').text())) || 0;
                var apld_current_year = parseFloat(parseValue($('.apld_current_year').text())) || 0;
                var td_current_year = scd_current_year + apld_current_year;
                if (td_current_year < 0) {
                    $('.td_current_year').html('(' + (Math.abs(td_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.td_current_year').html((td_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                var tsc_current_year = (scb_current_year + sctc_current_year + scci_current_year) - scd_current_year;
                var tapl_current_year = (aplb_current_year + apltc_current_year + aplci_current_year) - apld_current_year;
                var tt_current_year = tsc_current_year + tapl_current_year;
                if (tsc_current_year < 0) {
                    $('.tsc_current_year').html('(' + (Math.abs(tsc_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tsc_current_year').html((tsc_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
                if (tapl_current_year < 0) {
                    $('.tapl_current_year').html('(' + (Math.abs(tapl_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tapl_current_year').html((tapl_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
                if (tt_current_year < 0) {
                    $('.tt_current_year').html('(' + (Math.abs(tt_current_year)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ')');
                } else {
                    $('.tt_current_year').html((tt_current_year).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
            }*/

            calculateTotal();

        });
    </script>
@endpush

@push('styles')
    <style>
        .header-rupee {
            position: relative;
        }

        .header-rupee strong::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            height: 3px;
            border-top: 3px dotted #000;
        }

        .header-rupee strong span {
            background: #fff;
            position: relative;
            padding: 1px;
            z-index: 999;
        }
    </style>
@endpush
