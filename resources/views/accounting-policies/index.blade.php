@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Accounting Policy</h1>
                    <a href="{{ route('accounting-policy.create') }}" class="btn btn-outline-primary btn-sm mb-3">Add Accounting Policy</a>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
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
    @if (Session::has('error'))
        <div class="mb-3">
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>{{ Session::get('error') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        </div>
    @endif

    @foreach ($policies as $groupName => $accounting_policies)
        <h5><strong>{{ $groupName }}</strong></h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th style="width: 3vw;">#</th>
                        <th style="width: 5vw;">Account Type</th>
                        <th style="width: 20vw;">Title</th>
                        <th>Content</th>
                        <th style="width: 8vw;">Created By</th>
                        <th class="actions" style="width: 3vw;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accounting_policies as $policy)
                        <tr>
                            <td>{{ ++$loop->index }}</td>
                            <td>{{ $policy->account_type }}</td>
                            <td>{{ $policy->title }}</td>
                            <td>{!! $policy->content !!}</td>
                            <td>{{ $policy->user->name }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('accounting-policy.edit', $policy->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    {{-- <a href="#" data-toggle="modal" data-target="#edit-policy-modal-{{ $policy->id }}" class="btn btn-sm btn-primary">Edit</a> --}}
                                    {{-- @if ($loop->last) --}}
                                        <form action="{{ route('accounting-policy.destroy', $policy->id) }}" id="delete-form-{{ $policy->id }}" method="POST">
                                            @csrf
                                            @method('delete')
                                        </form>
                                        <a href="#" data-target="{{ $policy->id }}" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></a>
                                    {{-- @endif --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No record found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
        });
    </script>
@endpush