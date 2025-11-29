@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">View Company</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
                        <li class="breadcrumb-item active">Company View</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
@endsection

@section('content')
    <div class="track mb-5">
        <div class="step active"> <span class="icon">1</span> <span class="text"><a href="{{ route('companies.create') }}">Company Formation</a></span> </div>
        <div class="step active"> <span class="icon">2</span> <span class="text"><a href="{{ route('fixed-assets.index', $company->id) }}">Fixed Assets Schedual</a></span> </div>
        <div class="step active"> <span class="icon">3</span> <span class="text"><a href="{{ route('trail-balance.index', $company->id) }}">Trail Balance</a></span> </div>
        <div class="step active"> <span class="icon">4</span> <span class="text"><a href="{{ route('notes.index', $company->id) }}">Notes</a></span> </div>
        <div class="step active"> <span class="icon">5</span> <span class="text">
        @if (in_array('SOPL', explode(',', $company->required_statements)))
            <a href="{{ route('statements.sopl', $company->id) }}">Statments</a>
        @elseif (in_array('SOCI', explode(',', $company->required_statements)))
            <a href="{{ route('statements.soci', $company->id) }}">Statments</a>
        @elseif (in_array('SOCE', explode(',', $company->required_statements)))
            <a href="{{ route('statements.soce', $company->id) }}">Statments</a>
        @elseif (in_array('SOFP', explode(',', $company->required_statements)))
            <a href="{{ route('statements.sofp', $company->id) }}">Statments</a>
        @elseif (in_array('SOCF', explode(',', $company->required_statements)))
            <a href="{{ route('statements.socf', $company->id) }}">Statments</a>
        @endif
        </span> </div>
    </div>
    <div class="card card-body w-50 mx-auto">
        <form action="{{ route('companies.update', $company->id) }}" method="POST" class="form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name">Company Name: <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Company Nme" disabled>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="start_date">Start Date: <span class="text-danger">*</span></label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $company->start_date) }}" class="form-control @error('start_date') is-invalid @enderror" disabled>
                    @error('start_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col mb-3">
                    <label for="end_date">End Period: <span class="text-danger">*</span></label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $company->end_date) }}" class="form-control @error('end_date') is-invalid @enderror" disabled>
                    @error('end_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="address">Address:</label>
                <textarea name="address" id="address" class="form-control @error('address', $company->address) is-invalid @enderror" placeholder="Enter Address" disabled>{{ old('address', $company->address) }}</textarea>
                @error('address')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="required_statements" class="d-block">Select Require Statements:</label>
                <div class="form-inline">
                    <div class="custom-control custom-checkbox form-check-inline">
                        <input disabled type="checkbox" id="sofp" value="SOFP" name="required_statements[]" class="custom-control-input" @if((old('required_statements') && in_array('SOFP', old('required_statements'))) || (!old('required_statements') && in_array('SOFP', explode(',', $company->required_statements)))) checked @endif>
                        <label for="sofp" class="custom-control-label">SOFP</label>
                    </div>
                    <div class="custom-control custom-checkbox form-check-inline">
                        <input disabled type="checkbox" id="sopl" value="SOPL" name="required_statements[]" class="custom-control-input" @if((old('required_statements') && in_array('SOPL', old('required_statements'))) || (!old('required_statements') && in_array('SOPL', explode(',', $company->required_statements)))) checked @endif>
                        <label for="sopl" class="custom-control-label">SOPL</label>
                    </div>
                    <div class="custom-control custom-checkbox form-check-inline">
                        <input disabled type="checkbox" id="soci" value="SOCI" name="required_statements[]" class="custom-control-input" @if((old('required_statements') && in_array('SOCI', old('required_statements'))) || (!old('required_statements') && in_array('SOCI', explode(',', $company->required_statements)))) checked @endif>
                        <label for="soci" class="custom-control-label">SOCI</label>
                    </div>
                    <div class="custom-control custom-checkbox form-check-inline">
                        <input disabled type="checkbox" id="soce" value="SOCE" name="required_statements[]" class="custom-control-input" @if((old('required_statements') && in_array('SOCE', old('required_statements'))) || (!old('required_statements') && in_array('SOCE', explode(',', $company->required_statements)))) checked @endif>
                        <label for="soce" class="custom-control-label">SOCE</label>
                    </div>
                    <div class="custom-control custom-checkbox form-check-inline">
                        <input disabled type="checkbox" id="socf" value="SOCF" name="required_statements[]" class="custom-control-input" @if((old('required_statements') && in_array('SOCF', old('required_statements'))) || (!old('required_statements') && in_array('SOCF', explode(',', $company->required_statements)))) checked @endif>
                        <label for="socf" class="custom-control-label">SOCF</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="report_type">Type of Report:</label>
                    <select name="report_type" id="report_type" class="form-control @error('report_type') is-invalid @enderror" disabled>
                        <option value="">Select Type of Report</option>
                        <option value="IAS 700" @if(old('report_type', $company->report_type) === 'IAS 700') selected @endif>IAS 700</option>
                        <option value="IAS 800" @if(old('report_type', $company->report_type) === 'IAS 800') selected @endif>IAS 800</option>
                    </select>
                    @error('report_type')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col mb-3">
                    <label for="account_type">Type of Accounts:</label>
                    <select name="account_type" id="account_type" class="form-control @error('account_type') is-invalid @enderror" disabled>
                        <option value="">Type of Account</option>
                        <option value="proprietor" @if(old('account_type', $company->account_type) === 'proprietor') selected @endif>Proprietor</option>
                        <option value="aop" @if(old('account_type', $company->account_type) === 'aop') selected @endif>AOP</option>
                        <option value="company" @if(old('account_type', $company->account_type) === 'company') selected @endif>Company</option>
                    </select>
                </div>
            </div>
        </form>
        <a href="{{ route('fixed-assets.index', $company->id) }}" class="btn btn-primary">Fixed Assets Schedual</a>
    </div>
@endsection
