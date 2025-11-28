@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Edit Audit Report</h1>
                    <a href="{{ route('company-audit-reports.index') }}" class="btn btn-outline-primary btn-sm mb-3">Back</a>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('company-audit-reports.index') }}">Audit Report</a></li>
                        <li class="breadcrumb-item active">Edit Audit Report</li>
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
        <form action="{{ route('company-audit-reports.update', $auditReport->id) }}" method="POST" class="form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="myeditorinstance">Content:</label>
                <textarea name="content" id="myeditorinstance" cols="30" rows="10" class="form-control @error('content') is-invalid @enderror">{{ old('content', $auditReport->content) }}</textarea>
                @error('content')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">Save</button>
        </form>
    </div>
@endsection

@push('styles')
    <x-head.tinymce-config/>
@endpush

@push('scripts')
    <script>
        $(document).ready(function(){
            
        });
    </script>
@endpush