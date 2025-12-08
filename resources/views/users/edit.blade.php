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

    <div class="card card-body w-50 mx-auto">
        <form action="{{ route('users.update', $user->id) }}" id="form-edit-user" method="POST" class="form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="Name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Leave blank if you don't want to change password.</small>
                </div>

                <div class="col mb-3">
                    <label for="password-confirm">Retype Password:</label>
                    <input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="Retype Password">
                </div>
            </div>

            <div class="mb-3">
                <label for="type">Role:</label>
                <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                    <option value="">Select Role</option>
                    <option value="admin" @if (old('type', $user->type) == 'admin') selected @endif>Admin</option>
                    <option value="user" @if (old('type', $user->type) == 'user') selected @endif>User</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="profile_image">Profile Image:</label>
                <input type="file" id="profile_image" name="profile_image" class="d-block @error('profile_image') is-invalid @enderror">
                @error('profile_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Leave blank if you don't want to change profile image.</small>
            </div>
            <button class="btn btn-primary" type="submit">Save</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            // Form Validation
            $('#form-edit-user').on('submit', function(e) {
                let errors = [];

                // 1. User name is required
                const name = $('input[name="name"]').val().trim();
                if (!name) {
                    errors.push('User name is required');
                }

                // 2. User email is required
                const email = $('input[name="email"]').val().trim();
                if (!email) {
                    errors.push('Email is required');
                }

                // 3. User password is required
                const password = $('input[name="password"]').val().trim();

                if (password != '') {
                    const retype_password = $('input[name="password_confirmation"]').val().trim();
                    if (!retype_password) {
                        errors.push('Retype password is required');
                    }

                    if (password != retype_password) {
                        errors.push('The password field confirmation does not match.');
                    }
                }

                // 5. User role is required
                const type = $('select[name="type"]').val().trim();
                if (!type) {
                    errors.push('Role is required');
                }

                // Display errors
                if (errors.length > 0) {
                    e.preventDefault();

                    let errorHtml = '<div class="alert alert-danger mb-20"><ul>';
                    errors.forEach(function(error) {
                        errorHtml += '<li>' + error + '</li>';
                    });
                    errorHtml += '</ul></div>';

                    // Remove existing error messages
                    $('#form-edit-user .alert-danger').remove();

                    // Add new error messages at the top of the form
                    $('#form-edit-user').prepend(errorHtml);

                    // Scroll to top
                    $('html, body').animate({
                        scrollTop: $('#form-edit-user').offset().top - 100
                    }, 500);

                    // Focus on the first field with error
                    setTimeout(function() {
                        if (!name) {
                            $('input[name="name"]').focus();
                        } else if (!email) {
                            $('input[name="email"]').focus();
                        } else if (!password) {
                            $('input[name="password"]#password').focus();
                        } else if (!retype_password) {
                            $('input[name="password_confirmation"]').focus();
                        } else if (!type) {
                            $('select[name="type"]').focus();
                        }
                    }, 100);

                    return false;
                }

                return true;
            });
        });
    </script>
@endpush
