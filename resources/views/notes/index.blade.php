@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Notes</h1>
                    <a href="{{ route('notes.regenerate', $company->id) }}" class="btn btn-primary btn-sm mb-3 regenerate-notes">Regenerate Notes</a>
                    {{-- <a href="{{ route('companies.create') }}" class="btn btn-outline-primary btn-sm mb-3">Add Company</a> --}}
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
                        <li class="breadcrumb-item active">Notes</li>
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
        <div class="step active"> <span class="icon">2</span> <span class="text"><a href="{{ route('fixed-assets.index', $company->id) }}">Fixed Assets Schedual</a></span> </div>
        <div class="step active"> <span class="icon">3</span> <span class="text"><a href="{{ route('trail-balance.index', $company->id) }}">Trail Balance</a></span> </div>
        <div class="step active"> <span class="icon">4</span> <span class="text">Notes</span> </div>
        <div class="step"> <span class="icon">5</span> <span class="text">Statments</span> </div>
    </div>

    <div class="note-main-container">
        <div class="note-main-header">
            <div class="note-header-company-name">{{ $company->name }}</div>
            <div class="note-document-title">Notes to the financial statements</div>
            <div class="note-period">For the year ended {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</div>
        </div>

        @php
            // $index = 4;
        @endphp
        @foreach ($notes as $index => $accounts)
            @php
                $totalCurrentYear = 0;
                $totalPreviousYear = 0;

                $isLastGroupChild = \App\Models\Note::where('company_id', $company->id)
                    ->where('parent_index', $accounts[0]->parent_index)
                    ->orderBy('index', 'DESC')
                    ->first();

                // Check if any account in this group has a child note
                $groupHasChildNote = false;
                foreach ($accounts as $acc) {
                    $hasChild = \App\Models\Note::where('company_id', $acc->company_id)
                        ->where('account_code', $acc->account_code)
                        ->where('account_head', $acc->account_head)
                        ->whereNotNull('parent_index')
                        ->exists();

                    if ($hasChild) {
                        $groupHasChildNote = true;
                        break;
                    }
                }
            @endphp
            {{-- Group Section Start --}}
            <div class="note-section" id="note-{{ $index }}" data-current-index="{{ $index }}">
                <div class="note-header">
                    <div class="note-header-left">
                        <small><strong>{{ $index }}</strong></small>
                        <div class="note-code">{{ $accounts[0]['group_code'] }}</div>
                        <div class="note-title">{{ $accounts[0]['group_name'] }}</div>
                    </div>
                    <div class="btn-group">
                        @if(is_null($accounts[0]->parent_index))
                            <button class="btn btn-primary btn-sm" data-target="#note-{{ str_replace(' ', '-', str_replace('.', '-', $index)) }}-modal" data-toggle="modal"><i class="fas fa-plus"></i></button>
                        @else
                            <button class="btn btn-primary btn-sm" data-target="#note-update-{{ str_replace('.', '-', $index) }}-modal" data-toggle="modal"><i class="fas fa-edit"></i></button>
                        @endif
                        @if ($isLastGroupChild->index === $index)
                            <button class="btn btn-danger btn-sm delete-note-btn" data-company-id="{{ $accounts[0]->company_id }}" data-index="{{ $index }}" data-parent-index="{{ $accounts[0]->parent_index }}"><i class="fas fa-trash"></i></button>
                        @endif
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                @if (!is_null($accounts[0]->current_year) && !is_null($accounts[0]->previous_year))
                                    <th style="width: 30vw;">Description</th>
                                    @if (is_null($accounts[0]->parent_index))
                                        <th style="width: 5vw;" class="text-center">Note</th>
                                    @endif
                                    <th style="width: 5vw;" class="note-amount text-center">{{ \Carbon\Carbon::parse($company->end_date)->format('Y') }}<br>(Rupees)</th>
                                    @if ($company->company_meta['comparative_accounts'] == 'Yes')
                                        <th style="width: 5vw;" class="note-amount text-center">{{ \Carbon\Carbon::parse($company->end_date)->subYear()->format('Y') }}<br>(Rupees)</th>
                                    @endif
                                    <th style="width: 3vw;"></th>
                                @else
                                    <th style="width: 100%;">Description</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                @php
                                    $current_year = $account->current_year;
                                    $previous_year = $account->previous_year;
                                    foreach ($accounts as $merge_account) {
                                        if ($merge_account->merge_id === $account->account_code) {
                                            $current_year += $merge_account->current_year;
                                            $previous_year += $merge_account->previous_year;
                                        }
                                    }
                                    $totalCurrentYear += $account->current_year;
                                    $totalPreviousYear += $account->previous_year;
                                    if (!is_null($account->merge_id)) {
                                        continue;
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        {{ $account->account_code }} - {{ $account->account_head }}
                                        @php
                                            $child_note = \App\Models\Note::where('company_id', $account->company_id)
                                                ->where('account_code', $account->account_code)
                                                ->where('parent_index', $index)
                                                ->first();
                                        @endphp
                                    </td>
                                    @if (!is_null($accounts[0]->current_year) && !is_null($accounts[0]->previous_year))
                                        @if (is_null($accounts[0]->parent_index))
                                            <td class="text-center"><strong>{{ ($child_note) ? $child_note->index : '' }}</strong></td>
                                        @endif
                                        <td class="note-amount text-center">{{ ($current_year < 0) ? '('. number_format(abs(round($current_year)), 0, '.', ',') .')' : number_format(abs(round($current_year)), 0, '.', ',') }}</td>
                                        @if ($company->company_meta['comparative_accounts'] == 'Yes')
                                            <td class="note-amount text-center">{{ ($previous_year < 0) ? '('. number_format(abs(round($previous_year)), 0, '.', ',') .')' : number_format(abs(round($previous_year)), 0, '.', ',') }}</td>
                                        @endif
                                        {{-- <td class="note-amount text-center">{{ $previous_year }}</td> --}}
                                        <td>
                                            @if (count($accounts) > 1 && !$groupHasChildNote)
                                                <button class="btn btn-link btn-sm merge-account-btn" data-toggle="modal" data-target="#note-merge-{{ str_replace('.', '-', $account->account_code) }}-modal" title="merge"><i class="fas fa-object-group"></i></button>
                                                {{-- Account Merge Modal Start --}}
                                                <div class="modal fade" id="note-merge-{{ str_replace('.', '-', $account->account_code) }}-modal">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5>Merge Accounts with - ({{ $account->account_code }}) - {{ $account->account_head }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form>
                                                                <input type="hidden" class="main_account_code" value="{{ $account->account_code }}">
                                                                @foreach ($accounts as $mergeAccount)
                                                                    @if ($mergeAccount->account_code != $account->account_code && 
                                                                        (is_null($mergeAccount->merge_id) || $mergeAccount->merge_id === $account->account_code))
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input merge-account-code" id="{{ $account->account_code }}-{{ $mergeAccount->account_code }}" value="{{ $mergeAccount->account_code }}" @if($mergeAccount->merge_id === $account->account_code) checked @endif>
                                                                            <label for="{{ $account->account_code }}-{{ $mergeAccount->account_code }}" class="custom-control-label">{{ $mergeAccount->account_code }}-{{ $mergeAccount->account_head }}</label>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-primary btn-sm MergeAccountBtn">Merge Account</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                                {{-- Account Merge Modal End --}}
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            @if (!is_null($accounts[0]->current_year) && !is_null($accounts[0]->previous_year))
                                <tr class="group-footer">
                                    <td class="text-right" colspan="@if(is_null($accounts[0]->parent_index)) 2 @endif"><strong>Total:</strong></td>
                                    <td class="note-amount text-center"><strong>{{ ($totalCurrentYear < 0) ? '('. number_format(abs(round($totalCurrentYear)), 0, '.', ',') .')' : number_format(abs(round($totalCurrentYear)), 0, '.', ',') }}</strong></td>
                                    @if ($company->company_meta['comparative_accounts'] == 'Yes')
                                        <td class="note-amount text-center"><strong>{{ ($totalPreviousYear < 0) ? '('. number_format(abs(round($totalPreviousYear)), 0, '.', ',') .')' : number_format(abs(round($totalPreviousYear)), 0, '.', ',') }}</strong></td>
                                    @endif
                                    {{-- <td class="note-amount text-center"><strong>{{ $totalPreviousYear }}</strong></td> --}}
                                    <td></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{-- Add Child Note Group Start --}}
                <div class="modal fade" id="note-{{ str_replace(' ', '-', str_replace('.', '-', $index)) }}-modal">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>Notes</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <input type="hidden" value="{{ $index }}" name="index">
                                    <div class="mb-3">
                                        <label for="account_code">Select Accounts</label>
                                        <select name="account_code" id="account_code" class="form-control form-control-sm account_code">
                                            <option value="">Select Account</option>
                                            @php
                                                // Find the last account that has a child note with this parent_index
                                                $lastAccountWithChild = null;
                                                $lastAccountIndex = -1;

                                                foreach ($accounts as $key => $acc) {
                                                    $hasChild = \App\Models\Note::where('company_id', $acc->company_id)
                                                        ->where('account_code', $acc->account_code)
                                                        ->where('parent_index', $index)
                                                        ->exists();

                                                    if ($hasChild) {
                                                        $lastAccountWithChild = $acc;
                                                        $lastAccountIndex = $key;
                                                    }
                                                }
                                            @endphp
                                            @foreach ($accounts as $key => $account)
                                                @php
                                                    // Check if this account already has child notes
                                                    $hasChildNote = \App\Models\Note::where('company_id', $account->company_id)
                                                        ->where('account_code', $account->account_code)
                                                        ->where('parent_index', $index)
                                                        ->exists();

                                                    // Exclude this account if:
                                                    // 1. It already has a child note, OR
                                                    // 2. It comes before the last account that has a child note
                                                    $shouldExclude = $hasChildNote || ($lastAccountIndex >= 0 && $key < $lastAccountIndex);
                                                @endphp
                                                @if (!$shouldExclude)
                                                    <option value="{{ $account->account_code }}" data-current-year="{{ $account->current_year }}" data-previous-year="{{ $account->previous_year }}">{{ $account->account_head }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <ul class="nav nav-tabs nav-pills" id="note-type-tab-{{ $index }}">
                                            <li class="nav-item"><button class="nav-link btn-sm active" id="detail-note-tab-{{ $index }}" data-toggle="tab" data-target="#detail-note-{{ $index }}-tab" type="button" role="tab" aria-controls="detail-note-{{ $index }}" aria-selected="true">Detail Note</button></li>
                                            <li class="nav-item"><button class="nav-link btn-sm" id="descriptive-note-tab-{{ $index }}" data-toggle="tab" data-target="#descriptive-note-{{ $index }}-tab" type="button" role="tab" aria-controls="descriptive-note-{{ $index }}" aria-selected="true">Descriptive Note</button></li>
                                        </ul>
                                        <div class="tab-content" id="note-type-tab-{{ $index }}-content">
                                            <div class="tab-pane fade show active" id="detail-note-{{ $index }}-tab" role="tabpanel" aria-labelledby="detail-note-{{ $index }}-tab">
                                                <div class="p-3 table-responsive">
                                                    <table class="table table-striped table-hover table-sm" id="detail-note-table-{{ $index }}">
                                                        <thead>
                                                            <tr>
                                                                <th>Description</th>
                                                                <th class="text-center">{{ \Carbon\Carbon::parse($company->end_date)->format('Y') }}<br>(Rupees)</th>
                                                                @if ($company->company_meta['comparative_accounts'] == 'Yes')
                                                                    <th class="text-center">{{ \Carbon\Carbon::parse($company->end_date)->subYear()->format('Y') }}<br>(Rupees)</th>
                                                                @endif
                                                                <th><button class="btn btn-primary btn-sm detail-note-add-btn" data-target="detail-note-table-{{ $index }}" type="button" role="button"><i class="fas fa-plus"></i></button></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td contenteditable="true"></td>
                                                                <td contenteditable="true" class="editable text-center"></td>
                                                                @if ($company->company_meta['comparative_accounts'] == 'Yes')
                                                                    <td contenteditable="true" class="editable text-center"></td>
                                                                @endif
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td class="text-right"><strong>Total:</strong></td>
                                                                <td class="text-center">0</td>
                                                                @if ($company->company_meta['comparative_accounts'] == 'Yes')
                                                                    <td class="text-center">0</td>
                                                                @endif
                                                                <td></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="descriptive-note-{{ $index }}-tab" role="tabpanel" aria-labelledby="descriptive-note-{{ $index }}-tab">
                                                <div class="p-3"><textarea name="descriptive-note-{{ $index }}" id="descriptive-note-{{ $index }}" class="form-control form-control-sm" cols="30" rows="3"></textarea></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary btn-sm SaveNoteBtn">Save Note</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Add Child Note Group End --}}
                @if (!is_null($accounts[0]->parent_index))
                    {{-- Update Child Note Group Start --}}
                    <div class="modal fade" id="note-update-{{ str_replace('.', '-', $index) }}-modal">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Notes Update</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <input type="hidden" value="{{ $company->id }}" name="company_id" class="company_id">
                                        <input type="hidden" value="{{ $index }}" name="index" class="index">
                                        <input type="hidden" value="{{ $accounts[0]->parent_index }}" name="parent_index">
                                        <input type="hidden" value="{{ $accounts[0]->group_code }}" name="group_code">
                                        <input type="hidden" value="{{ $accounts[0]->group_name }}" name="group_name">
                                        <input type="hidden" value="{{ $accounts[0]->account_code }}" name="account_code">
                                        @if (!is_null($accounts[0]->current_year) && !is_null($accounts[0]->previous_year))
                                            <input type="hidden" value="detail" name="note_type">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover table-sm" id="detail-note-table-{{ str_replace('.', '-', $index) }}">
                                                    <thead>
                                                        <tr>
                                                            <th>Description</th>
                                                            <th class="text-center">{{ \Carbon\Carbon::parse($company->end_date)->format('Y') }}<br>(Rupees)</th>
                                                            @if ($company->company_meta['comparative_accounts'] == 'Yes')
                                                                <th class="text-center">{{ \Carbon\Carbon::parse($company->end_date)->subYear()->format('Y') }}<br>(Rupees)</th>
                                                            @endif
                                                            <th><button class="btn btn-primary btn-sm detail-note-add-btn" data-target="detail-note-table-{{ str_replace('.', '-', $index) }}" type="button" role="button"><i class="fas fa-plus"></i></button></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $i = 0;
                                                        @endphp
                                                        @foreach ($accounts as $account)
                                                            <tr>
                                                                <td contenteditable="true">{{ $account->account_head }}</td>
                                                                <td contenteditable="true" class="editable text-center">{{ $account->current_year }}</td>
                                                                @if ($company->company_meta['comparative_accounts'] == 'Yes')
                                                                    <td contenteditable="true" class="editable text-center">{{ $account->previous_year }}</td>
                                                                @endif
                                                                <td>
                                                                    <input type="hidden" value="{{ $account->id }}" name="id" class="id">
                                                                    @if ($i > 0)
                                                                        <button class="btn btn-danger btn-sm detail-note-remove-btn" type="button" role="button"><i class="fas fa-times"></i></button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $i++;
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            @php
                                                                $total = \App\Models\Note::where('company_id', $company->id)->where('account_code', $accounts[0]->account_code)->where('index', $accounts[0]->parent_index)->first();
                                                            @endphp
                                                            <td class="text-right"><strong>Total:</strong></td>
                                                            <td data-current-year="{{ $total->current_year }}" class="text-center">{{ $accounts->sum('current_year') }}</td>
                                                            @if ($company->company_meta['comparative_accounts'] == 'Yes')
                                                                <td data-previous-year="{{ $total->previous_year }}" class="text-center">{{ $accounts->sum('previous_year') }}</td>
                                                            @endif
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @else
                                            <input type="hidden" value="descriptive" name="note_type">
                                            <input type="hidden" value="{{ $accounts[0]->id }}" name="id" class="id">
                                            <textarea name="descriptive-note" id="descriptive-note" class="form-control form-control-sm" cols="30" rows="3">{{ $accounts[0]->account_head }}</textarea>
                                        @endif
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-primary btn-sm UpdateNoteBtn">Save Note</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Update Child Note Group End --}}
                @endif
            </div>
            {{-- Group Section End --}}
        @endforeach

        @if (in_array('SOPL', explode(',', $company->required_statements)))
            <a href="{{ route('statements.sopl', $company->id) }}" class="btn btn-primary">Generate Statements</a>
        @elseif (in_array('SOCI', explode(',', $company->required_statements)))
            <a href="{{ route('statements.soci', $company->id) }}" class="btn btn-primary">Generate Statements</a>
        @elseif (in_array('SOCE', explode(',', $company->required_statements)))
            <a href="{{ route('statements.soce', $company->id) }}" class="btn btn-primary">Generate Statements</a>
        @elseif (in_array('SOFP', explode(',', $company->required_statements)))
            <a href="{{ route('statements.sofp', $company->id) }}" class="btn btn-primary">Generate Statements</a>
        @elseif (in_array('SOCF', explode(',', $company->required_statements)))
            <a href="{{ route('statements.socf', $company->id) }}" class="btn btn-primary">Generate Statements</a>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){

            $(document).on('click', '.regenerate-notes', function(e){
                // e.preventDefault();
                if (confirm('Are you sure to re-generate notes. \nWarning it will delete all the descriptive and detail notes?')) {
                    return true;
                } else {
                    return false;
                }
            });

            function parseCell($cell) {
                const text = $cell.text().replace(/,/g, '').trim();
                return parseFloat(text) || 0;
            }

            $(document).on('input', '.editable', updateTable);

            function updateTable() {
                var entries = [];
                var totalCurrentYear = 0;
                @if ($company->company_meta['comparative_accounts'] == 'Yes')
                    var totalPreviousYear = 0;
                @endif
                // if(table_id == '') {
                    var table_id = $(this).parents('table').attr('id');
                // }
                
                $('#' + table_id + ' tbody tr').each(function(){
                    var $row = $(this);
                    var current_year = parseCell($row.find('td').eq(1));
                    totalCurrentYear += current_year;

                    @if ($company->company_meta['comparative_accounts'] == 'Yes')
                        var previous_year = parseCell($row.find('td').eq(2));
                        totalPreviousYear += previous_year;
                    @endif
                });

                $('#' + table_id + ' tfoot tr td:nth-child(2)').html(totalCurrentYear);
                @if ($company->company_meta['comparative_accounts'] == 'Yes')
                    $('#' + table_id + ' tfoot tr td:nth-child(3)').html(totalPreviousYear);
                @endif
            }

            function recalculateTotals(table_id) {
                var totalCurrentYear = 0;
                @if ($company->company_meta['comparative_accounts'] == 'Yes')
                    var totalPreviousYear = 0;
                @endif

                $('#' + table_id + ' tbody tr').each(function(){
                    var $row = $(this);
                    var current_year = parseCell($row.find('td').eq(1));
                    totalCurrentYear += current_year;

                    @if ($company->company_meta['comparative_accounts'] == 'Yes')
                        var previous_year = parseCell($row.find('td').eq(2));
                        totalPreviousYear += previous_year;
                    @endif
                });

                $('#' + table_id + ' tfoot tr td:nth-child(2)').html(totalCurrentYear);
                @if ($company->company_meta['comparative_accounts'] == 'Yes')
                    $('#' + table_id + ' tfoot tr td:nth-child(3)').html(totalPreviousYear);
                @endif
            }

            $(document).on('click', '.detail-note-add-btn', function(){
                var target = $(this).data('target');
                var html = `
                    <tr>
                        <td contenteditable="true"></td>
                        <td contenteditable="true" class="editable text-center"></td>
                `;
                @if ($company->company_meta['comparative_accounts'] == 'Yes')
                    html += `<td contenteditable="true" class="editable text-center"></td>`;
                @endif
                html += `
                        <td><button class="btn btn-danger btn-sm detail-note-remove-btn" type="button" role="button"><i class="fas fa-times"></i></button></td>
                    </tr>
                `;
                $('#' + target + ' tbody').append(html);
                updateTable();
                recalculateTotals(target);
            });

            $(document).on('click', '.detail-note-remove-btn', function(){
                // Recalculate totals after removing a row
                var table = $(this).parents('table');
                var table_id = table.attr('id');
                $(this).parents('tr').remove();
                updateTable();
                recalculateTotals(table_id);
            });

            $(document).on('click', '.SaveNoteBtn', function(){
                var modal = $(this).closest('.modal');
                var selectedAccount = modal.find('#account_code option:selected');

                // Check if an account is selected
                if (!selectedAccount.val()) {
                    alert('Please select an account first.');
                    return;
                }

                // Get data attributes from selected account
                var expectedCurrentYear = parseFloat(selectedAccount.data('current-year')) || 0;
                @if ($company->company_meta['comparative_accounts'] == 'Yes')
                    var expectedPreviousYear = parseFloat(selectedAccount.data('previous-year')) || 0;
                @endif

                // Get the index form the note section
                var noteSection = modal.closest('.note-section');
                var index = noteSection.data('current-index');
                var accountCode = selectedAccount.val();

                // Find the active tab (detail note or descriptive note)
                var activeTab = modal.find('.tab-pane.active');

                // Prepare data for AJAX request
                var ajaxData = {
                    index: index,
                    account_code: accountCode,
                    account_head: [],
                    current_year: 0,
                    previous_year: 0
                };

                if (activeTab.attr('id') && activeTab.attr('id').includes('detail-note')) {
                    // validate if it's a detail note tab
                    var detailTable = activeTab.find('table');
                    var table_id = detailTable.attr('id');

                    // Validate that all description fields are not empty
                    var hasEmptyDescription = false;
                    var emptyRowNumbers = [];

                    detailTable.find('tbody tr').each(function(index){
                        var $row = $(this);
                        var description = $row.find('td').eq(0).text().trim();

                        if (description === '') {
                            hasEmptyDescription = true;
                            emptyRowNumbers.push(index + 1);
                        }
                    });

                    if (hasEmptyDescription) {
                        alert('Please fill in all description fields.\n\nEmpty descriptions found in row(s): ' + emptyRowNumbers.join(', '));
                        return;
                    }

                    // Get calculated totals from the table footer
                    var calculatedCurrentYear = parseFloat(detailTable.find('tfoot tr td:nth-child(2)').text().replace(/,/g, '').trim()) || 0;
                    @if ($company->company_meta['comparative_accounts'] == 'Yes')
                        var calculatedPreviousYear = parseFloat(detailTable.find('tfoot tr td:nth-child(3)').text().replace(/,/g, '').trim()) || 0;
                        // Validate totals match
                        if (calculatedCurrentYear !== expectedCurrentYear || calculatedPreviousYear !== expectedPreviousYear) {
                            alert('The total of current year (' + calculatedCurrentYear + ') and previous year (' + calculatedPreviousYear + ') does not match the account totals.\n\n' +
                                'Expected:\n' +
                                'Current Year: ' + expectedCurrentYear + '\n' +
                                'Previous Year: ' + expectedPreviousYear + '\n\n' +
                                'Please adjust the detail note entries to match the account totals.');
                            return;
                        }
                    @endif


                    // Collect detail note data (array of description, current_year, previous_year)
                    var accountHeadArray = [];
                    var currentYearArray = [];
                    @if ($company->company_meta['comparative_accounts'] == 'Yes')
                        var previousYearArray = [];
                    @endif

                    detailTable.find('tbody tr').each(function(){
                        var $row = $(this);
                        var description = $row.find('td').eq(0).text().trim();
                        var currentYear = parseCell($row.find('td').eq(1));
                        @if ($company->company_meta['comparative_accounts'] == 'Yes')
                            var previousYear = parseCell($row.find('td').eq(2));
                        @endif

                        accountHeadArray.push(description);
                        currentYearArray.push(currentYear);
                        @if ($company->company_meta['comparative_accounts'] == 'Yes')
                            previousYearArray.push(previousYear);
                        @endif

                    });

                    // Update ajaxData for detail note
                    ajaxData.account_head = accountHeadArray;
                    ajaxData.current_year = currentYearArray;
                    @if ($company->company_meta['comparative_accounts'] == 'Yes')
                        ajaxData.previous_year = previousYearArray;
                    @endif

                } else if (activeTab.attr('id') && activeTab.attr('id').includes('descriptive-note')) {
                    // validate if it's a descriptive note tab
                    var textarea = activeTab.find('textarea');
                    var descriptiveNoteText = textarea.val().trim();

                    if (descriptiveNoteText === '') {
                        alert('Please enter a descriptive note. The descriptive note field cannot be empty.');
                        textarea.focus();
                        return;
                    }

                    // Update ajaxData for descriptive note
                    ajaxData.account_head = descriptiveNoteText;
                    ajaxData.current_year = 0;
                    ajaxData.previous_year = 0;
                }

                // If validation passes, proceed with saving via AJAX
                $.ajax({
                    url: '{{ route("notes.save", $company->id) }}',
                    method: 'POST',
                    data: ajaxData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Note saved successfully!');
                        console.log('Response:', response);
                        modal.modal('hide');
                        // Optionally reload the page or update the table
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('Error saving note: ' + (xhr.responseJSON?.message || error));
                        console.error('Error:', xhr.responseJSON);
                    }
                });
            });

            $(document).on('click', '.UpdateNoteBtn', function(){
                var modal = $(this).closest('.modal');

                var company_id = modal.find('input[name="company_id"]').val();
                var index = modal.find('input[name="index"]').val();
                var parent_index = modal.find('input[name="parent_index"]').val();
                var group_code = modal.find('input[name="group_code"]').val();
                var group_name = modal.find('input[name="group_name"]').val();
                var account_code = modal.find('input[name="account_code"]').val();
                var note_type = modal.find('input[name="note_type"]').val();

                // Prepare data for AJAX request
                var ajaxData = {
                    company_id: company_id,
                    index: index,
                    parent_index: parent_index,
                    group_code: group_code,
                    group_name: group_name,
                    note_type: note_type,
                    account_code: account_code,
                    account_head: [],
                    current_year: 0,
                    previous_year: 0
                };

                if (note_type === 'detail') {
                    // Get data attributes from selected account
                    var detailTable = modal.find('table');
                    var table_id = detailTable.attr('id');
                    // var expectedCurrentYear = parseFloat($('#' + table_id + ' tfoot tr td:nth-child(2)').attr('current-year')) || 0;
                    var expectedCurrentYear = parseFloat(detailTable.find('tfoot tr td:nth-child(2)').attr('data-current-year').replace(/,/g, '').trim()) || 0;
                    
                    @if ($company->company_meta['comparative_accounts'] == 'Yes')
                        // var expectedPreviousYear = parseFloat($('#' + table_id + ' tfoot tr td:nth-child(3)').attr('previous-year')) || 0;
                        var expectedPreviousYear = parseFloat(detailTable.find('tfoot tr td:nth-child(3)').attr('data-previous-year').replace(/,/g, '').trim()) || 0;
                    @endif

                    // Validate that all description fields are not empty
                    var hasEmptyDescription = false;
                    var emptyRowNumbers = [];
                    
                    detailTable.find('tbody tr').each(function(index) {
                        var $row = $(this);
                        var description = $row.find('td').eq(0).text().trim();

                        if (description === '') {
                            hasEmptyDescription = true;
                            emptyRowNumbers.push(index + 1);
                        }
                    });

                    if (hasEmptyDescription) {
                        alert('Please fill in all description fields.\n\nEmpty descriptions found in row(s): ' + emptyRowNumbers.join(','));
                        return;
                    }

                    // Get calculated totals from the table footer
                    var calculatedCurrentYear = parseFloat(detailTable.find('tfoot tr td:nth-child(2)').text().replace(/,/g, '').trim()) || 0;

                    @if ($company->company_meta['comparative_accounts'] == 'Yes')
                        var calculatedPreviousYear = parseFloat(detailTable.find('tfoot tr td:nth-child(3)').text().replace(/,/g, '').trim()) || 0;
                        // Validate totals match
                        if (calculatedCurrentYear !== expectedCurrentYear || calculatedPreviousYear !== expectedPreviousYear) {
                            alert('The total of current year (' + calculatedCurrentYear + ') and previous year (' + calculatedPreviousYear + ') does not match the account totals.\n\n' +
                                'Expected:\n' +
                                'Current Year: ' + expectedCurrentYear + '\n' +
                                'Previous Year: ' + expectedPreviousYear + '\n\n' +
                                'Please adjust the detail note entries to match the account totals.');
                            return;
                        }
                    @endif

                    // Collect detail note data (array of description, current_year, previous_year)
                    var account_id = [];
                    var accountHeadArray = [];
                    var currentYearArray = [];
                    
                    @if ($company->company_meta['comparative_accounts'] == 'Yes')
                        var previousYearArray = [];
                    @endif

                    detailTable.find('tbody tr').each(function() {
                        var $row = $(this);
                        var description = $row.find('td').eq(0).text().trim();
                        var currentYear = parseCell($row.find('td').eq(1));
                        @if ($company->company_meta['comparative_accounts'] == 'Yes')
                            var previousYear = parseCell($row.find('td').eq(2));
                        @endif
                        var id = $row.find('td').eq(3);
                        var acc_id = id.find('input[name="id"]').val();

                        if(acc_id) {
                            account_id.push(acc_id);
                        }
                        accountHeadArray.push(description);
                        currentYearArray.push(currentYear);
                        @if ($company->company_meta['comparative_accounts'] == 'Yes')
                            previousYearArray.push(previousYear);
                        @endif
                    });

                    // Update ajaxData for detail note
                    ajaxData.account_id = account_id;
                    ajaxData.account_head = accountHeadArray;
                    ajaxData.current_year = currentYearArray;
                    @if ($company->company_meta['comparative_accounts'] == 'Yes')
                        ajaxData.previous_year = previousYearArray;
                    @endif
                } else if (note_type === 'descriptive') {
                    var account_id = [];
                    account_id.push(modal.find('input[name="id"]').val());
                    var textarea = modal.find('textarea');
                    var descriptiveNoteText = textarea.val().trim();

                    if (descriptiveNoteText === '') {
                        alert('Please enter a descriptive note. The descriptive note field cannot be empty.');
                        textarea.focus();
                        return;
                    }

                    // Update ajaxData for descriptive note
                    ajaxData.account_id = account_id;
                    ajaxData.account_head = descriptiveNoteText;
                }

                // If validation passes, proceed with saving via AJAX
                $.ajax({
                    url: '{{ route("notes.update_child_notes") }}', // notes.update_child_notes
                    method: 'POST',
                    data: ajaxData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Note saved successfully!');
                        console.log('Response:', response);
                        modal.modal('hide');
                        // Optionally reload the page or update the table
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('Error saving note: ' + (xhr.responseJSON?.message || error));
                        console.error('Error:', xhr.responseJSON);
                    }
                });
            });

            $(document).on('click', '.delete-note-btn', function(){
                var modal = $(this).closest('.modal');
                var companyId = $(this).data('company-id');
                var index = $(this).data('index');
                var parent_index = $(this).data('parent-index');

                if (confirm('Are you sure to delete note ' + index)) {
                    $.ajax({
                        url: '{{ route("notes.delete") }}', // notes.delete
                        method: 'DELETE',
                        data: {
                            company_id: companyId,
                            index: index,
                            parent_index: parent_index,
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert('Note deleted successfully!');
                            console.log('Response:', response);
                            modal.modal('hide');
                            // Optionally reload the page or update the table
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            alert('Error saving note: ' + (xhr.responseJSON?.message || error));
                            console.error('Error:', xhr.responseJSON);
                        }
                    });
                }
            });

            $(document).on('click', '.MergeAccountBtn', function(){
                var modal = $(this).closest('.modal');
                var main_account_code = modal.find('input[type="hidden"].main_account_code').val();
                var account_codes = [];

                modal.find('form input.merge-account-code:checkbox:checked').each(function(index){
                    account_codes.push($(this).val());
                });

                // console.log('Main account code: ' + main_account_code);
                // console.log('Account code: ' + account_codes);

                $.ajax({
                    url: '{{ route("notes.accounts-merge", $company->id) }}', // notes.accounts-merge
                    method: 'POST',
                    data: {
                        company_id: {{ $company->id }},
                        main_account_code: main_account_code,
                        account_codes: account_codes,
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Accounts merge successfully!');
                        console.log('Response:', response);
                        modal.modal('hide');
                        // Optionally reload the page or update the table
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('Error saving note: ' + (xhr.responseJSON?.message || error));
                        console.error('Error:', xhr.responseJSON);
                    }
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        /* ==========================Financial Notes============================ */
        .note-main-container {
            min-width: 950px;
            margin: 0 auto;
            background: white;
            padding: 50px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .note-main-header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 20px;
        }

        .note-header-company-name {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .note-document-title {
            font-size: 16px;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .note-period {
            font-size: 13px;
            color: #666;
            font-weight: 500;
        }

        .note-section {
            margin-bottom: 40px;
            page-break-inside: avoid;
            border: 1px solid #e0e0e0;
            padding: 15px;
            border-radius: 5px;
        }

        .note-header {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            align-items: flex-start;
            justify-content: space-between;
        }

        .note-header-left {
            display: flex;
            gap: 15px;
            align-items: flex-start;
        }

        .note-code {
            font-size: 12px;
            font-weight: 700;
            color: #1a1a1a;
            background-color: #f0f0f0;
            padding: 4px 8px;
            border-radius: 3px;
            white-space: nowrap;
        }

        .note-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .note-actions {
            display: flex;
            gap: 8px;
        }

        /* Added styles for detail notes section */
        .detail-notes-section {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }

        .detail-notes-title {
            font-size: 12px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .detail-note-item {
            background-color: #f9fafb;
            border-left: 3px solid #2563eb;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 3px;
            font-size: 12px;
            line-height: 1.5;
        }

        .detail-note-item.empty {
            color: #999;
            font-style: italic;
        }

        .detail-note-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .detail-note-label {
            font-weight: 600;
            color: #2563eb;
        }

        .detail-note-actions {
            display: flex;
            gap: 5px;
        }
    </style>
@endpush