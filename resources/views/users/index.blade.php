@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Users</h1>
                    <a href="{{ route('users.create') }}" class="btn btn-outline-primary btn-sm mb-3">Add User</a>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Users</li>
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
                    <th>Name</th>
                    <th>Email</th>
                    <th style="width:10%;" class="text-center">Role</th>
                    <th style="width:5%;" class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                        <td class="text-center">{{ ucwords($user->type) }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                @if ($user->type != 'admin')
                                    <form action="{{ route('users.destroy', $user->id) }}" id="delete-form-{{ $user->id }}" method="POST">
                                        @csrf
                                        @method('delete')
                                    </form>
                                    <a href="#" data-target="{{ $user->id }}" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td class="text-center" colspan="4">No record found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $(document).on('click', '.delete-btn', function(e){
                e.preventDefault();
                if(confirm('Are you sure to delete the user?')) {
                    $('#delete-form-' + $(this).data('target')).submit();
                }
            });
        });
    </script>
@endpush
