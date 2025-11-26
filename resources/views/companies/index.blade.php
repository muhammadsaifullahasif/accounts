@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Companies</h1>
                    <a href="{{ route('companies.create') }}" class="btn btn-outline-primary btn-sm mb-3">Add Company</a>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Companies</li>
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
    <div class="table-responsive">
        <table class="table table-striped table-hover table-sm">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Address</th>
                    <th>Statements</th>
                    <th>Report</th>
                    <th>Type</th>
                    <th>Createdy By</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($companies as $company)
                    <tr>
                        <td>
                            <a href="#" class="btn btn-sm toggle-arrow" data-company-id="{{ $company->id }}">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                        <td>{{ $company->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($company->start_date)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</td>
                        <td>{{ $company->address }}</td>
                        <td>{{ $company->required_statements }}</td>
                        <td>{{ $company->report_type }}</td>
                        <td>{{ ucwords($company->account_type) }}</td>
                        <td>{{ $company->user->name }}</td>
                        <td class="actions">
                            <div class="btn-group">
                                {{-- <a href="#" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a> --}}
                                <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('companies.destroy', $company->id) }}" method="POST" id="delete-company-{{ $company->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button class="btn btn-danger btn-sm delete" data-id="delete-company-{{ $company->id }}" type="button"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr class="sub-table-row" style="display: none;" data-company-id="1">
                        <td colspan="10" class="sub-table-cell">
                            <div class="sub-table-wrapper table-responsive" style="border: 1px solid; background: #ECECEC;">
                                <table class="table table-striped table-hover table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Last changed by</th>
                                            <th>Last updated</th>
                                            <th class="actions">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Trail Balance</td>
                                            <td>Admin</td>
                                            <td>2025-11-17 23:07:52</td>
                                            <td class="actions">
                                                <div class="btn-group">
                                                    <a href="{{ route('trail-balance.index', $company->id) }}" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Fixed Asset</td>
                                            <td>Admin</td>
                                            <td>2025-11-17 23:07:52</td>
                                            <td class="actions">
                                                <div class="btn-group">
                                                    <a href="{{ route('fixed-assets.index', $company->id) }}" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Notes</td>
                                            <td>Admin</td>
                                            <td>2025-11-17 23:07:52</td>
                                            <td class="actions">
                                                <div class="btn-group">
                                                    <a href="{{ route('notes.index', $company->id) }}" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @if (in_array('SOPL', explode(',', $company->required_statements)))
                                        <tr>
                                            <td>SOPL</td>
                                            <td>Admin</td>
                                            <td>2025-11-17 23:07:52</td>
                                            <td class="actions">
                                                <div class="btn-group">
                                                    <a href="{{ route('statements.sopl', $company->id) }}" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        @if (in_array('SOCI', explode(',', $company->required_statements)))
                                        <tr>
                                            <td>SOCI</td>
                                            <td>Admin</td>
                                            <td>2025-11-17 23:07:52</td>
                                            <td class="actions">
                                                <div class="btn-group">
                                                    <a href="{{ route('statements.soci', $company->id) }}" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        @if (in_array('SOCE', explode(',', $company->required_statements)))
                                        <tr>
                                            <td>SOCE</td>
                                            <td>Admin</td>
                                            <td>2025-11-17 23:07:52</td>
                                            <td class="actions">
                                                <div class="btn-group">
                                                    <a href="{{ route('statements.soce', $company->id) }}" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        @if (in_array('SOFP', explode(',', $company->required_statements)))
                                        <tr>
                                            <td>SOFP</td>
                                            <td>Admin</td>
                                            <td>2025-11-17 23:07:52</td>
                                            <td class="actions">
                                                <div class="btn-group">
                                                    <a href="{{ route('statements.sofp', $company->id) }}" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        @if (in_array('SOCF', explode(',', $company->required_statements)))
                                        <tr>
                                            <td>SOCF</td>
                                            <td>Admin</td>
                                            <td>2025-11-17 23:07:52</td>
                                            <td class="actions">
                                                <div class="btn-group">
                                                    <a href="{{ route('statements.socf', $company->id) }}" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                                    <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10">No record found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.delete').on('click', function (e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (confirm('Are you sure to delete company and it\'s related data?')) {
                    $('#' + id).submit();
                } else {
                    return false;
                }
            });
            
            $('.toggle-arrow').on('click', function(e) {
                e.preventDefault();
                var companyId = $(this).data('company-id');
                var subTableRow = $('.sub-table-row[data-company-id="' + companyId + '"]');
                var icon = $(this).find('i');

                if (subTableRow.is(':visible')) {
                    subTableRow.hide();
                    icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
                } else {
                    subTableRow.show();
                    icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
                }
            });
        });
    </script>
@endpush
