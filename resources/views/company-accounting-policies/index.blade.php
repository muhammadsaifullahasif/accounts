@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Accounting Policy</h1>
                    <a href="{{ route('company-accounting-policy.create') }}" class="btn btn-outline-primary btn-sm mb-3">Add Accounting Policy</a>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Company</a></li>
                        <li class="breadcrumb-item active">Accounting Policy</li>
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

    <div class="note-main-container">
        <div class="note-main-header">
            <div class="note-header-company-name">{{ $company->name }}</div>
            <div class="note-document-title">Notes to the financial statements</div>
            <div class="note-period">For the year ended {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</div>
        </div>
    </div>

    @foreach ($policies as $groupName => $accounting_policies)
        <h5 class="d-inline mb-3"><strong>{{ $groupName }}</strong></h5>
        <a href="#" data-toggle="modal" data-target="#add-policy-modal-{{ str_replace(' ', '-', $groupName) }}" class="btn btn-primary btn-sm float-right mb-3">Add Policy</a>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th style="width: 3vw;">Index</th>
                        <th style="width: 20vw;">Title</th>
                        <th>Content</th>
                        <th style="width: 8vw;">Created By</th>
                        <th style="width: 3vw;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accounting_policies as $policy)
                        <tr>
                            <td>
                                {{ $policy->index }}
                                @if ( $policy->policy_heading === 'COMPANY AND ITS OPERATIONS' )
                                    {{ 1 . '.' . ($loop->index + 1) }}
                                @elseif ( $policy->policy_heading === 'BASIS OF PREPARATION' )
                                    {{ 2 . '.' . ($loop->index + 1) }}
                                @elseif ( $policy->policy_heading === 'SUMMARY OF SIGNIFICANT ACCOUNTING POLICIES' )
                                    {{ 3 . '.' . ($loop->index + 1) }}
                                @endif
                            </td>
                            <td>{{ $policy->title }}</td>
                            <td>{!! $policy->content !!}</td>
                            <td>{{ $policy->user->name }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('company-accounting-policy.edit', [$company->id, $policy->id]) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    {{-- @if ($loop->last) --}}
                                        <form action="{{ route('company-accounting-policy.destroy', [$company->id, $policy->id]) }}" id="delete-form-{{ $policy->id }}" method="POST">
                                            @csrf
                                            @method('delete')
                                        </form>
                                        <a href="#" data-target="{{ $policy->id }}" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></a>
                                    {{-- @endif --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No record found.</td></tr>
                    @endforelse
                </tbody>
                
            </table>
        </div>
        <div class="modal fade" id="add-policy-modal-{{ str_replace(' ', '-', $groupName) }}">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Policy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <input type="hidden" value="{{ $groupName }}" name="policy_heading" class="policy_heading">
                            @php
                                $existingTitles = $accounting_policies->pluck('title')->toArray();
                                $new_policies = \App\Models\AccountingPolicy::where('industry_id', $company->industry_id)
                                    ->where('size', $company->size)
                                    ->where('policy_heading', $groupName)
                                    ->whereNotIn('title', $existingTitles)
                                    ->orderBy('id', 'ASC')
                                    ->get();
                            @endphp
                            @forelse ($new_policies as $new_policy)
                                <div class="form-check">
                                    <input type="checkbox" id="{{ $new_policy->id }}" value="{{ $new_policy->id }}" name="new_policy[]" class="form-check-input">
                                    <label for="{{ $new_policy->id }}" class="form-check-label">{{ $new_policy->title }}</label>
                                </div>
                            @empty
                                <p>No new accounting policy found.</p>
                            @endforelse
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-sm add-policy-form-btn">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p>No Policy Found.</p>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $(document).on('click', '.delete-btn', function(e){
                e.preventDefault();
                if(confirm('Are you sure to delete the policy?')) {
                    $('#delete-form-' + $(this).data('target')).submit();
                }
            });

            $(document).on('click', '.add-policy-form-btn', function(e){
                e.preventDefault();

                // Store button reference
                const $button = $(this);

                // Find the closest modal
                const modal = $button.closest('.modal');

                // Find the form inside that modal
                const form = modal.find('form');

                // Get checked checkboxes only from this form
                const checkedBoxes = form.find('input[name="new_policy[]"]:checked');

                // Check if at least one checkbox is checked
                if (checkedBoxes.length === 0) {
                    alert('Please select at least one policy to add.');
                    return false;
                }

                // Gather all selected policy IDs
                const selectedPolicyIds = [];
                checkedBoxes.each(function () {
                    selectedPolicyIds.push($(this).val());
                });

                // Get company ID and policy heading from this form
                const companyId = '{{ $company->id }}';
                const policyHeading = form.find('.policy_heading').val();

                // Create form data object
                const formData = {
                    policy_ids: selectedPolicyIds,
                    policy_heading: policyHeading,
                    _token: '{{ csrf_token() }}'
                };

                // console.log('Form Data: ', formData);

                // Disable button and show loading state
                $button.prop('disabled', true).text('Saving...');

                // Send data via AJAX
                $.ajax({
                    url: '/companies/' + companyId + '/policies/add-bulk',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            alert('Policies added successfully!');
                            location.reload();
                        } else {
                            // Re-enable button if success is false
                            $button.prop('disabled', false).text('Save changes');
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        // Re-enable button on error
                        $button.prop('disabled', false).text('Save changes');
                        alert('Error adding policies: ' + (xhr.responseJSON?.message || xhr.responseText));
                    }
                });
            });
        });
    </script>
@endpush