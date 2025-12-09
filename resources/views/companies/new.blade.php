@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Company</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
                        <li class="breadcrumb-item active">Company New</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
@endsection

@section('content')
    <div class="track mb-5">
        <div class="step active"> <span class="icon">1</span> <span class="text">Company Formation</span> </div>
        <div class="step"> <span class="icon">2</span> <span class="text">Fixed Assets Schedual</span> </div>
        <div class="step"> <span class="icon">3</span> <span class="text">Trail Balance</span> </div>
        <div class="step"> <span class="icon">4</span> <span class="text">Notes</span> </div>
        <div class="step"> <span class="icon">5</span> <span class="text">Statments</span> </div>
    </div>
    <div class="card card-body w-50 mx-auto">
        <form action="{{ route('companies.store') }}" method="POST" class="form" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name">Company Name: <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Company Nme">
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="start_date">Start Date: <span class="text-danger">*</span></label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="form-control @error('start_date') is-invalid @enderror">
                    @error('start_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col mb-3">
                    <label for="end_date">End Period: <span class="text-danger">*</span></label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="form-control @error('end_date') is-invalid @enderror">
                    @error('end_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="address">Address:</label>
                <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" placeholder="Enter Address">{{ old('address') }}</textarea>
                @error('address')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="required_statements" class="d-block">Select Require Statements:</label>
                <div class="form-inline">
                    <div class="custom-control custom-checkbox form-check-inline">
                        <input type="checkbox" id="sofp" value="SOFP" name="required_statements[]" class="custom-control-input" @if(is_array(old('required_statements')) && in_array('SOFP', old('required_statements'))) checked @endif>
                        <label for="sofp" class="custom-control-label">SOFP</label>
                    </div>
                    <div class="custom-control custom-checkbox form-check-inline">
                        <input type="checkbox" id="sopl" value="SOPL" name="required_statements[]" class="custom-control-input" @if(is_array(old('required_statements')) && in_array('SOPL', old('required_statements'))) checked @endif>
                        <label for="sopl" class="custom-control-label">SOPL</label>
                    </div>
                    <div class="custom-control custom-checkbox form-check-inline">
                        <input type="checkbox" id="soci" value="SOCI" name="required_statements[]" class="custom-control-input" @if(is_array(old('required_statements')) && in_array('SOCI', old('required_statements'))) checked @endif>
                        <label for="soci" class="custom-control-label">SOCI</label>
                    </div>
                    <div class="custom-control custom-checkbox form-check-inline">
                        <input type="checkbox" id="soce" value="SOCE" name="required_statements[]" class="custom-control-input" @if(is_array(old('required_statements')) && in_array('SOCE', old('required_statements'))) checked @endif>
                        <label for="soce" class="custom-control-label">SOCE</label>
                    </div>
                    <div class="custom-control custom-checkbox form-check-inline">
                        <input type="checkbox" id="socf" value="SOCF" name="required_statements[]" class="custom-control-input" @if(is_array(old('required_statements')) && in_array('SOCF', old('required_statements'))) checked @endif>
                        <label for="socf" class="custom-control-label">SOCF</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="report_type">Type of Report:</label>
                    <select name="report_type" id="report_type" class="form-control @error('report_type') is-invalid @enderror">
                        <option value="">Select Type of Report</option>
                        <option value="ISA 700" @if(old('report_type') === 'ISA 700') selected @endif>ISA 700</option>
                        <option value="ISA 800" @if(old('report_type') === 'ISA 7800') selected @endif>ISA 800</option>
                    </select>
                    @error('report_type')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col mb-3">
                    <label for="account_type">Type of Accounts:</label>
                    <select name="account_type" id="account_type" class="form-control @error('account_type') is-invalid @enderror">
                        <option value="">Type of Account</option>
                        <option value="Proprietor" @if(old('report_type') === 'Proprietor') selected @endif>Proprietor</option>
                        <option value="AOP" @if(old('report_type') === 'AOP') selected @endif>AOP</option>
                        <option value="Company" @if(old('report_type') === 'Company') selected @endif>Company</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col mb-3">
                    <label for="comparative_accounts">Comparative Accounts:</label>
                    <select name="comparative_accounts" id="comparative_accounts" class="form-control @error('comparative_accounts') is-invalid @enderror">
                        <option value="">Select Comparative Accounts</option>
                        <option value="Yes" @if(old('comparative_accounts') === 'Yes') selected @endif>Yes</option>
                        <option value="No" @if(old('comparative_accounts') === 'No') selected @endif>No</option>
                    </select>
                    @error('comparative_accounts')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Save</button>
        </form>
    </div>
@endsection
