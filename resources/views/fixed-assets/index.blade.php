@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Fixed Assets</h1>
                    {{-- <a href="{{ route('companies.create') }}" class="btn btn-outline-primary btn-sm mb-3">Add Company</a> --}}
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
                        <li class="breadcrumb-item active">Fixed Assets</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
@endsection

@section('content')
    @if (Session::has('success'))
        <div class="mb-3">
            <div class="alert alert-success alert-dismissible fade show">
                <strong>{{ Session::get('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        </div>
    @endif
    <div class="track mb-5">
        <div class="step active"> <span class="icon">1</span> <span class="text"><a href="{{ route('companies.create') }}">Company Formation</a></span> </div>
        <div class="step active"> <span class="icon">2</span> <span class="text">Fixed Assets Schedual</span> </div>
        <div class="step"> <span class="icon">3</span> <span class="text">Trail Balance</span> </div>
        <div class="step"> <span class="icon">4</span> <span class="text">Notes</span> </div>
        <div class="step"> <span class="icon">5</span> <span class="text">Statments</span> </div>
    </div>
    <div class="table-responsive fixed-assets mb-3">
        <table class="table table-bordered table-hover table-sm fixed-asset-table" id="fixed-asset-table">
            <thead class="bg-white">
                <tr>
                    <th class="text-center" rowspan="2" style="width: 15%;">Particulars</th>
                    <th class="text-center" colspan="6">Cost</th>
                    <th class="text-center">Rate</th>
                    <th class="text-center" colspan="4">Depreciation</th>
                    <th class="text-center">WDV</th>
                    <th class="text-center" rowspan="3" style="vertical-align: bottom;"><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-account-modal"><i class="fas fa-plus"></i></button></th>
                </tr>
                <tr>
                    <th class="text-center">As at<br>{{ \Carbon\Carbon::parse($company->start_date)->format('M d, Y') }}</th>
                    <th class="text-center" colspan="2">Addition</th>
                    <th class="text-center" colspan="2">Deletion</th>
                    <th class="text-center">As at<br>{{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</th>
                    <th class="text-center" rowspan="2">%</th>
                    <th class="text-center">As at<br>{{ \Carbon\Carbon::parse($company->start_date)->format('M d, Y') }}</th>
                    <th class="text-center">For the period</th>
                    <th class="text-center">Disposal</th>
                    <th class="text-center">As at<br>{{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</th>
                    <th class="text-center">As at<br>{{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</th>
                </tr>
                <tr>
                    <th class="text-center"></th>
                    <th class="text-center">Rupees</th>
                    <th class="text-center">Rupees</th>
                    <th class="text-center">No of Days</th>
                    <th class="text-center">Rupees</th>
                    <th class="text-center">No of Days</th>
                    <th class="text-center">Rupees</th>
                    <th class="text-center">Rupees</th>
                    <th class="text-center">Rupees</th>
                    <th class="text-center">Rupees</th>
                    <th class="text-center">Rupees</th>
                    <th class="text-center">Rupees</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalOpening = 0;
                    $totalAddition = 0;
                    $totalDeletion = 0;
                    $totalClosing = 0;
                    $totalDepreciationOpening = 0;
                    $totalDepreciationAddition = 0;
                    $totalDepreciationDeletion = 0;
                    $totalDepreciationClosing = 0;
                    $totalWDV = 0;
                @endphp
                @foreach ($fixedAssets as $fixedAsset)
                    @php
                        $totalOpening += $fixedAsset->opening;
                        $totalAddition += $fixedAsset->addition;
                        $totalDeletion += $fixedAsset->deletion;
                        $totalClosing += $fixedAsset->closing;
                        $totalDepreciationOpening += $fixedAsset->depreciation_opening;
                        $totalDepreciationAddition += $fixedAsset->depreciation_addition;
                        $totalDepreciationDeletion += $fixedAsset->depreciation_deletion;
                        $totalDepreciationClosing += $fixedAsset->depreciation_closing;
                        $totalWDV += $fixedAsset->wdv;
                    @endphp
                    <tr data-account-code="{{ $fixedAsset->account_code }}">
                        <td class="text-center align-middle">
                            {{ $fixedAsset->account_head }}
                            <input type="hidden" value="{{ $fixedAsset->account_code }}" class="accountCode">
                            <input type="hidden" value="{{ $fixedAsset->account_head }}" class="accountHead">
                            <input type="hidden" value="{{ $fixedAsset->depreciation_account_code }}" class="depreciationAccountCode">
                            <input type="hidden" value="{{ $fixedAsset->depreciation_account_head }}" class="depreciationAccountHead">
                            <input type="hidden" value="{{ $company->start_date }}" class="start_date">
                            <input type="hidden" value="{{ $company->end_date }}" class="end_date">
                        </td>
                        <td class="text-center align-middle editable" contenteditable="true">{{ $fixedAsset->opening }}</td>
                        <td class="text-center align-middle editable" contenteditable="true">{{ $fixedAsset->addition }}</td>
                        <td class="text-center align-middle"><input type="date" name="additionNoOfDays[]" value="{{ $fixedAsset->addition_no_of_days }}" class="editable form-control form-control-sm bg-transparent border-0 text-center" min="{{ \Carbon\Carbon::parse($company->start_date)->format('Y-m-d') }}" max="{{ \Carbon\Carbon::parse($company->end_date)->format('Y-m-d') }}"></td>
                        <td class="text-center align-middle editable" contenteditable="true">{{ $fixedAsset->deletion }}</td>
                        <td class="text-center align-middle"><input type="date" name="deletionNoOfDays[]" value="{{ $fixedAsset->deletion_no_of_days }}" class="editable form-control form-control-sm bg-transparent border-0 text-center" min="{{ \Carbon\Carbon::parse($company->start_date)->format('Y-m-d') }}" max="{{ \Carbon\Carbon::parse($company->end_date)->format('Y-m-d') }}"></td>
                        <td class="text-center align-middle">{{ $fixedAsset->closing }}</td>
                        <td class="text-center align-middle editable" contenteditable="true">{{ $fixedAsset->rate }}</td>
                        <td class="text-center align-middle editable" contenteditable="true">{{ $fixedAsset->depreciation_opening }}</td>
                        <td class="text-center align-middle editable" contenteditable="true">{{ $fixedAsset->depreciation_addition }}</td>
                        <td class="text-center align-middle editable" contenteditable="true">{{ $fixedAsset->depreciation_deletion }}</td>
                        <td class="text-center align-middle">{{ $fixedAsset->depreciation_closing }}</td>
                        <td class="text-center align-middle">{{ $fixedAsset->wdv }}</td>
                        <td class="text-center align-middle"><button class="btn btn-danger btn-sm remove-row"><i class="fas fa-times"></i></button></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-right"><strong>Total:</strong></td>
                    <td class="text-center" id="totalOpening"><strong>{{ $totalOpening }}</strong></td>
                    <td class="text-center" id="totalAddition"><strong>{{ $totalAddition }}</strong></td>
                    <td class="text-center"></td>
                    <td class="text-center" id="totalClosing"><strong>{{ $totalDeletion }}</strong></td>
                    <td class="text-center"></td>
                    <td class="text-center" id="totalClosing"><strong>{{ $totalClosing }}</strong></td>
                    <td class="text-center"></td>
                    <td class="text-center" id="totalDepreciationOpening"><strong>{{ $totalDepreciationOpening }}</strong></td>
                    <td class="text-center" id="totalDepreciationAddition"><strong>{{ $totalDepreciationAddition }}</strong></td>
                    <td class="text-center" id="totalDepreciationDeletion"><strong>{{ $totalDepreciationDeletion }}</strong></td>
                    <td class="text-center" id="totalDepreciationClosing"><strong>{{ $totalDepreciationClosing }}</strong></td>
                    <td class="text-center" id="totalWDV"><strong>{{ $totalWDV }}</strong></td>
                    <td class="text-center"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <button class="btn btn-primary" id="saveFixedAssetsBtn">Save</button>
    <div class="modal fade" id="add-account-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Account</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="#">
                        <div class="mb-3">
                            <label for="account">Account:</label>
                            <select name="account" id="account" class="form-control">
                                <option value="">Select Account</option>
                            </select>
                        </div>
                        <button class="btn btn-primary btn-sm" id="addAccountBtn" type="button">Add Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {

            $(document).on('focus', '.editable', function(){
                if (parseCell($(this)) == 0) {
                    $(this).text('');
                }
            });

            $(document).on('blur', '.editable', function(){
                if (parseCell($(this)) == '') {
                    $(this).text(0);
                }
            });

            $('#saveFixedAssetsBtn').on('click', function(){
                var entries = [];

                $('.fixed-asset-table tbody tr').each(function() {
                    var $row = $(this);

                    // Get all values from the row
                    var accountCode = $row.find('.accountCode').val();
                    var accountHead = $row.find('.accountHead').val();
                    var depreciationAccountCode = $row.find('.depreciationAccountCode').val();
                    var depreciationAccountHead = $row.find('.depreciationAccountHead').val();

                    var opening = parseCell($row.find('td').eq(1));
                    var addition = parseCell($row.find('td').eq(2));
                    var additionNoOfDaysValue = $row.find('input[name="additionNoOfDays[]"]').val();
                    var deletion = parseCell($row.find('td').eq(4));
                    var deletionNoOfDaysValue = $row.find('input[name="deletionNoOfDays[]"]').val();
                    var closing = parseCell($row.find('td').eq(6));
                    var rate = parseCell($row.find('td').eq(7));
                    var depreciationOpening = parseCell($row.find('td').eq(8));
                    var depreciationAddition = parseCell($row.find('td').eq(9));
                    var depreciationDeletion = parseCell($row.find('td').eq(10));
                    var depreciationClosing = parseCell($row.find('td').eq(11));
                    var wdv = parseCell($row.find('td').eq(12));

                    // Create entry object
                    var entry = {
                        accountCode: accountCode,
                        accountHead: accountHead,
                        depreciationAccountCode: depreciationAccountCode,
                        depreciationAccountHead: depreciationAccountHead,
                        opening: opening,
                        addition: addition,
                        additionNoOfDaysValue: additionNoOfDaysValue,
                        deletion: deletion,
                        deletionNoOfDaysValue: deletionNoOfDaysValue,
                        closing: closing,
                        rate: rate,
                        depreciationOpening: depreciationOpening,
                        depreciationAddition: depreciationAddition,
                        depreciationDeletion: depreciationDeletion,
                        depreciationClosing: depreciationClosing,
                        wdv:wdv
                    };

                    entries.push(entry);
                });

                console.log(entries);

                // You can now send this data to the server via AJAX
                $.ajax({
                    url: '{{ route("fixed-assets.store", $company->id) }}',
                    method: 'POST',
                    data: { 
                        _token: '{{ csrf_token() }}',
                        entries: entries 
                    },
                    success: function(response) {
                        alert('Entries saved successfully!');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('An error occurred while saving entries.');
                    }
                });
            });

            function parseCell($cell) {
                const text = $cell.text().replace(/,/g, '').trim();
                return parseFloat(text) || 0;
            }

            var lastEditedAddition = {}; // Track which row's addition was last edited
            var lastEditedDeletion = {}; // Track which row's deletion was last edited
            $(document).on('input', '.editable', function () {
                var colIndex = $(this).index();

                // Skip column 3 and column 5
                if (colIndex === 3 || colIndex === 5) {
                    return;
                }

                // Otherwise cell updateTotals()
                updateTotals();
            });
            $(document).on('input', 'td[contenteditable="true"]:nth-child(3)', function() {
                var $row = $(this).closest('tr');
                var rowIndex = $('.fixed-asset-table tbody tr').index($row);
                lastEditedAddition[rowIndex] = 'value';
                updateTotals();
            });

            $(document).on('change', 'input[name="additionNoOfDays[]"]', function() {
                var $row = $(this).closest('tr');
                var rowIndex = $('.fixed-asset-table tbody tr').index($row);
                lastEditedAddition[rowIndex] = 'date';
                updateTotals();
            });

            $(document).on('input', 'td[contenteditable="true"]:nth-child(5)', function() {
                var $row = $(this).closest('tr');
                var rowIndex = $('.fixed-asset-table tbody tr').index($row);
                lastEditedDeletion[rowIndex] = 'value';
                updateTotals();
            });

            $(document).on('change', 'input[name="deletionNoOfDays[]"]', function() {
                var $row = $(this).closest('tr');
                var rowIndex = $('.fixed-asset-table tbody tr').index($row);
                lastEditedDeletion[rowIndex] = 'date';
                updateTotals();
            });

            function updateTotals() {
                console.log(lastEditedAddition);
                var entries = [];
                var totalOpening = 0;
                var totalAddition = 0;
                var totalDeletion = 0;
                var totalClosing = 0;
                var totalDepreciationOpening = 0;
                var totalDepreciationAddition = 0;
                var totalDepreciationDeletion = 0;
                var totalDepreciationClosing = 0;
                var totalWDV = 0;

                $('.fixed-asset-table tbody tr').each(function() {
                    var $row = $(this);

                    // Get start and end dates from hidden inputs
                    var startDateValue = $row.find('.start_date').val();
                    var endDateValue = $row.find('.end_date').val();
                    var startDate = new Date(startDateValue);
                    var endDate = new Date(endDateValue);

                    // Calculate total days in the year (for leap year check)
                    var year = endDate.getFullYear();
                    var isLeepYear = (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
                    var daysInYear = isLeepYear ? 366 : 365;

                    var opening = parseCell($row.find('td').eq(1));
                    var addition = parseCell($row.find('td').eq(2));

                    // Get row index
                    var rowIndex = $('.fixed-asset-table tbody tr').index($row);

                    // Get addition date from input[type="date"]
                    var additionDateValue = $row.find('input[name="additionNoOfDays[]"]').val();
                    var additionNoOfDays = 1; // Default to 1 if no date selected

                    // Only use date calculation if date was last edited (or never edited)
                    if (additionDateValue && lastEditedAddition[rowIndex] != 'value') {
                        var additionDate = new Date(additionDateValue);
                        // Calculate days from addition date to end date + 1
                        var endDatePlusOne = new Date(endDate);
                        endDatePlusOne.setDate(endDatePlusOne.getDate() + 1);
                        var timeDiff = endDatePlusOne.getTime() - additionDate.getTime();
                        additionNoOfDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                        // Normalize to ratio
                        var additionNoOfDays_ = additionNoOfDays;
                        additionNoOfDays = additionNoOfDays / daysInYear;
                    }

                    var deletion = parseCell($row.find('td').eq(4));

                    // Get deletion date from input[type="date"]
                    var deletionDateValue = $row.find('input[name="deletionNoOfDays[]"]').val();
                    var deletionNoOfDays = 1; // Default to 1 if no date selected

                    // Only use date calculation if date was last edited (or never edited)
                    if (deletionDateValue && lastEditedDeletion[rowIndex] !== 'value') {
                        var deletionDate = new Date(deletionDateValue);
                        // Calculate days from start date + 1 to deletion date
                        var startDatePlusOne = new Date(startDate);
                        // startDatePlusOne.setDate(startDatePlusOne.getDate() + 1);
                        var timeDiff = deletionDate.getTime() - startDatePlusOne.getTime();
                        deletionNoOfDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                        // Normalize to ratio
                        var deletionNoOfDays_ = deletionNoOfDays;
                        deletionNoOfDays = deletionNoOfDays / daysInYear;
                    }

                    var closing = (opening + addition - deletion);
                    if (closing < 0) {
                        closing = 0;
                    }
                    var rate = parseCell($row.find('td').eq(7)) / 100;
                    var depreciationOpening = parseCell($row.find('td').eq(8));

                    function calculateDepreciationOpening(opening, rate, startDate, endDate) {
                        var start = new Date(startDate);
                        var end = new Date(endDate);
                        var numberOfDays = Math.ceil((end - start) / (1000 * 3600 * 24)) + 1; // +1 to include both start and end date
                        return (opening * rate * (numberOfDays / 365));
                    }

                    // Calculate depreciation opening based on company formation dates
                    // var depreciationOpening = calculateDepreciationOpening(opening, rate, startDateValue, endDateValue);

                    var depreciationAddition = ( calculateDepreciationOpening(opening, rate, startDateValue, endDateValue) + (addition * rate * additionNoOfDays) + (deletion * rate * deletionNoOfDays) );

                    var depreciationDeletion = parseCell($row.find('td').eq(10));
                    // var depreciationClosing = parseCell($row.find('td').eq(11));
                    var depreciationClosing = depreciationOpening + depreciationAddition - depreciationDeletion;
                    if (depreciationClosing < 0) {
                        depreciationClosing = 0;
                    }
                    var wdv = closing - depreciationClosing;
                    if ( wdv < 0 ) {
                        wdv = 0
                    }

                    $row.find('td').eq(6).text((closing.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $row.find('td').eq(8).text((depreciationOpening.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $row.find('td').eq(9).text((depreciationAddition.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $row.find('td').eq(11).text((depreciationClosing.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $row.find('td').eq(12).text((wdv.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                    totalOpening += opening;
                    totalAddition += addition;
                    totalDeletion += deletion;
                    totalClosing += closing;
                    totalDepreciationOpening += depreciationOpening;
                    totalDepreciationAddition += depreciationAddition;
                    totalDepreciationDeletion += depreciationDeletion;
                    totalDepreciationClosing += depreciationClosing;
                    totalWDV += wdv;
                });

                // Print all entries to console
                /*console.log('=== Fixed Assets Entries ===');
                console.table(entries);
                console.log('=== Totals ===');
                console.log({
                    totalOpening: totalOpening,
                    totalAddition: totalAddition,
                    totalDeletion: totalDeletion,
                    totalClosing: totalClosing,
                    totalDepreciationOpening: totalDepreciationOpening,
                    totalDepreciationAddition: totalDepreciationAddition,
                    totalDepreciationDeletion: totalDepreciationDeletion,
                    totalDepreciationClosing: totalDepreciationClosing,
                    totalWDV: totalWDV
                });*/

                $('#totalOpening strong').text((totalOpening.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#totalAddition strong').text((totalAddition.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#totalDeletion strong').text((totalDeletion.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#totalClosing strong').text((totalClosing.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#totalDepreciationOpening strong').text((totalDepreciationOpening.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#totalDepreciationAddition strong').text((totalDepreciationAddition.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#totalDepreciationDeletion strong').text((totalDepreciationDeletion.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#totalDepreciationClosing strong').text((totalDepreciationClosing.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#totalWDV strong').text((totalWDV.toFixed(2)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                
                // return entries;
            }

            // updateTotals();

            // Define accounts array
            var accounts = [
                {
                    accountCode: 'PPE-001',
                    depreciationCode: 'PPE-002',
                    costTitle: 'Plant / Machinery / Equipment / Furniture (Including Fittings)',
                    depreciationTitle: 'Acc. Dep. Plant / Machinery / Equipment / Furniture (Including Fittings)'
                },
                {
                    accountCode: 'PPE-003',
                    depreciationCode: 'PPE-004',
                    costTitle: 'Plant / Machinery (Not Otherwise Specified)',
                    depreciationTitle: 'Acc. Dep. Plant / Machinery (Not Otherwise Specified)'
                },
                {
                    accountCode: 'PPE-005',
                    depreciationCode: 'PPE-006',
                    costTitle: 'Plant / Machinery Eligible for Initial Allowance',
                    depreciationTitle: 'Acc. Dep. Plant / Machinery Eligible for Initial Allowance'
                },
                {
                    accountCode: 'PPE-007',
                    depreciationCode: 'PPE-008',
                    costTitle: 'Furniture (Including Fittings)',
                    depreciationTitle: 'Acc. Dep. Furniture (Including Fittings)'
                },
                {
                    accountCode: 'PPE-009',
                    depreciationCode: 'PPE-010',
                    costTitle: 'Technical / Professional Books',
                    depreciationTitle: 'Acc. Dep. Technical / Professional Books'
                },
                {
                    accountCode: 'PPE-011',
                    depreciationCode: 'PPE-012',
                    costTitle: 'Motor Vehicle (Plying for Hire)',
                    depreciationTitle: 'Acc. Dep. Motor Vehicle (Plying for Hire)'
                },
                {
                    accountCode: 'PPE-013',
                    depreciationCode: 'PPE-014',
                    costTitle: 'Ramp for Disabled Persons',
                    depreciationTitle: 'Acc. Dep. Ramp for Disabled Persons'
                },
                {
                    accountCode: 'PPE-015',
                    depreciationCode: 'PPE-016',
                    costTitle: 'Offshore Installations of Mineral Oil Concerns',
                    depreciationTitle: 'Acc. Dep. Offshore Installations of Mineral Oil Concerns'
                },
                {
                    accountCode: 'PPE-017',
                    depreciationCode: 'PPE-018',
                    costTitle: 'Ships',
                    depreciationTitle: 'Acc. Dep. Ships'
                },
                {
                    accountCode: 'PPE-019',
                    depreciationCode: 'PPE-020',
                    costTitle: 'Aircrafts / Aero Engines',
                    depreciationTitle: 'Acc. Dep. Aircrafts / Aero Engines'
                }
            ];

            // Function to get existing account codes in the table
            function getExistingAccountCodes() {
                var existingCodes = [];
                $('#fixed-asset-table tr').each(function() {
                    var accountCode = $(this).data('account-code');
                    if (accountCode) {
                        existingCodes.push(accountCode);
                    }
                });
                return existingCodes;
            }

            // Function to populate account select with available accounts only
            function populateAccountSelect() {
                var existingCodes = getExistingAccountCodes();
                var $accountSelect = $('#account');

                // Clear existing options except the first one
                $accountSelect.find('option:not(:first)').remove();

                // Add only available accounts
                accounts.forEach(function(account) {
                    if (!existingCodes.includes(account.accountCode)) {
                        var option = `<option value="${account.accountCode}"
                                        data-depreciation-code="${account.depreciationCode}"
                                        data-cost-title="${account.costTitle}"
                                        data-depreciation-title="${account.depreciationTitle}">
                                        ${account.costTitle}
                                      </option>`;
                        $accountSelect.append(option);
                    }
                });
            }

            // Populate select when modal is opened
            $('#add-account-modal').on('show.bs.modal', function() {
                populateAccountSelect();
            });

            // Add account to table
            $('#addAccountBtn').on('click', function(){
                var accountCode = $('#account').val();
                if (accountCode != '') {
                    var depreciationCode = $('#account option:selected').data('depreciation-code');
                    var costTitle = $('#account option:selected').data('cost-title');
                    var depreciationTitle = $('#account option:selected').data('depreciation-title');

                    /*$('#fixed-asset-table tbody').append(`
                        <tr data-account-code="${accountCode}">
                            <td class="text-center align-middle">${costTitle}</td>
                            <td class="text-center align-middle"><input type="text" name="${accountCode}-cost-opening-debit" id="${accountCode}-cost-opening-debit" value="0" class="editable form-control form-control-sm bg-transparent border-0 text-center"></td>
                            <td class="text-center align-middle"><input type="text" name="${accountCode}-cost-movement-debit" id="${accountCode}-cost-movement-debit" value="0" class="editable form-control form-control-sm bg-transparent border-0 text-center"></td>
                            <td class="text-center align-middle"><input type="text" name="${accountCode}-cost-movement-credit" id="${accountCode}-cost-movement-credit" value="0" class="editable form-control form-control-sm bg-transparent border-0 text-center"></td>
                            <td class="text-center align-middle">0</td>
                            <td class="text-center align-middle"><input type="text" name="${accountCode}-rate" id="${accountCode}-rate" value="0" class="editable form-control form-control-sm bg-transparent border-0 text-center"></td>
                            <td class="text-center align-middle"><input type="text" name="${accountCode}-number_of_days" id="${accountCode}-number_of_days" value="0" class="editable form-control form-control-sm bg-transparent border-0 text-center"></td>
                            <td class="text-center align-middle"><input type="text" name="${accountCode}-depreciation-opening-credit" id="${accountCode}-depreciation-opening-credit" value="0" class="editable form-control form-control-sm bg-transparent border-0 text-center"></td>
                            <td class="text-center align-middle"><input type="text" name="${accountCode}-depreciation-movement-credit" id="${accountCode}-depreciation-movement-credit" value="0" class="editable form-control form-control-sm bg-transparent border-0 text-center"></td>
                            <td class="text-center align-middle"><input type="text" name="${accountCode}-disposal" id="${accountCode}-disposal" value="0" class="editable form-control form-control-sm bg-transparent border-0 text-center"></td>
                            <td class="text-center align-middle">0</td>
                            <td class="text-center align-middle">0</td>
                            <td class="text-center align-middle"><button class="btn btn-danger btn-sm remove-row"><i class="fas fa-times"></i></button></td>
                        </tr>
                    `);*/

                    $('#fixed-asset-table tbody').append(`
                        <tr data-account-code="${accountCode}">
                            <td class="text-center align-middle">
                                ${costTitle}
                                <input type="hidden" value="${accountCode}" class="accountCode">
                                <input type="hidden" value="${costTitle}" class="accountHead">
                                <input type="hidden" value="${depreciationCode}" class="depreciationAccountCode">
                                <input type="hidden" value="${depreciationTitle}" class="depreciationAccountHead">
                                <input type="hidden" value="{{ $company->start_date }}" class="start_date">
                                <input type="hidden" value="{{ $company->end_date }}" class="end_date">
                            </td>
                            <td class="text-center align-middle editable" contenteditable="true">0</td>
                            <td class="text-center align-middle editable" contenteditable="true">0</td>
                            <td class="text-center align-middle"><input type="date" name="additionNoOfDays[]" class="editable form-control form-control-sm bg-transparent border-0 text-center" min="{{ \Carbon\Carbon::parse($company->start_date)->format('Y-m-d') }}" max="{{ \Carbon\Carbon::parse($company->end_date)->format('Y-m-d') }}"></td>
                            <td class="text-center align-middle editable" contenteditable="true">0</td>
                            <td class="text-center align-middle"><input type="date" name="deletionNoOfDays[]" class="editable form-control form-control-sm bg-transparent border-0 text-center" min="{{ \Carbon\Carbon::parse($company->start_date)->format('Y-m-d') }}" max="{{ \Carbon\Carbon::parse($company->end_date)->format('Y-m-d') }}"></td>
                            <td class="text-center align-middle">0</td>
                            <td class="text-center align-middle editable" contenteditable="true">0</td>
                            <td class="text-center align-middle editable" contenteditable="true">0</td>
                            <td class="text-center align-middle editable" contenteditable="true">0</td>
                            <td class="text-center align-middle editable" contenteditable="true">0</td>
                            <td class="text-center align-middle">0</td>
                            <td class="text-center align-middle">0</td>
                            <td class="text-center align-middle"><button class="btn btn-danger btn-sm remove-row"><i class="fas fa-times"></i></button></td>
                        </tr>
                    `);

                    // Close modal and reset form
                    $('#add-account-modal').modal('hide');
                    $('#account').val('');
                }
            });

            // Remove row functionality
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });

            $('.toggle-arrow').on('click', function(e) {
                e.preventDefault();
                var groupId = $(this).data('group-id');
                var subTableRow = $('.sub-table-row[data-group-id="' + groupId + '"]');
                var icon = $(this).find('i');

                if (subTableRow.is(':visible')) {
                    subTableRow.hide();
                    icon.removeClass('fa-chevron-up').addClass('fa-chevron-right');
                } else {
                    subTableRow.show();
                    icon.removeClass('fa-chevron-right').addClass('fa-chevron-up');
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .table tr th {
            vertical-align: middle;
        }

        .trail-balance > .table > thead tr:first-child th, 
        .trail-balance > .table > thead tr:nth-child(2) th {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #FFFFFF;
        }

        .trail-balance > .table > thead tr:nth-child(2) th {
            top: 32px;
        }

        .table tr td input {
            width: 100%;
        }
    </style>
@endpush
