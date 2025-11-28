@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Audit Report</h1>
                    <a href="{{ route('audit-reports.create') }}" class="btn btn-outline-primary btn-sm mb-3">Add Audit Report</a>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Audit Report</li>
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
                    <th style="width:5%">Sr No.</th>
                    <th>Type</th>
                    <th style="width: 10%; text-align: center;">Size</th>
                    <th style="width:10%; text-align: center;">Created By</th>
                    <th style="width:5%;" class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($auditReports as $auditReport)
                    <tr>
                        <td>{{ $auditReport->id }}</td>
                        <td>{{ $auditReport->type }}</td>
                        <td style="text-align: center;">{{ $auditReport->size }}</td>
                        <td style="text-align: center;">{{ $auditReport->user->name }}</td>
                        <td style="text-align: center; display: flex; justify-content: center;" class="actions-cell">
                            <div class="btn-group">
                                {{-- <a href="#" data-toggle="modal" data-target="#addAccountingPolicyModal" class="btn btn-primary btn-sm" title="Add Accounting Policy">Add Policy</a> --}}
                                <a href="{{ route('audit-reports.edit', $auditReport->id) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                {{-- <form action="{{ route('audit-reports.destroy', $auditReport->id) }}" id="delete-form-{{ $auditReport->id }}" method="POST">
                                    @csrf
                                    @method('delete')
                                </form>
                                <a href="#" data-target="{{ $auditReport->id }}" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></a> --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">No report found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection