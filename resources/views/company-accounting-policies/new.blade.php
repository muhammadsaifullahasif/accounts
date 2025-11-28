@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Add Accounting Policy</h1>
                    <a href="{{ route('company-accounting-policy.create') }}" class="btn btn-outline-primary btn-sm mb-3">Add Accounting Policy</a>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Company</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('company-accounting-policy.index') }}">Accounting Policy</a></li>
                        <li class="breadcrumb-item active">Add Accounting Policy</li>
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
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-body w-50 mx-auto">
        <form action="{{ route('company-accounting-policy.store', $company->id) }}" method="POST" class="form" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="index">Index:</label>
                <input type="text" value="{{ old('index') }}" class="form-control @error('index') is-invalid @enderror" id="index" name="index" readonly="">
                @error('index')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="policy_heading">Policy Heading:</label>
                <select name="policy_heading" id="policy_heading" class="form-control">
                    <option value="">Select Policy Heading</option>
                    <option value="COMPANY AND ITS OPERATIONS">1. COMPANY AND ITS OPERATIONS</option>
                    <option value="BASIS OF PREPARATION">2. BASIS OF PREPARATION</option>
                    <option value="SUMMARY OF SIGNIFICANT ACCOUNTING POLICIES">3. SUMMARY OF SIGNIFICANT ACCOUNTING POLICIES</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="title">Title:</label>
                <select name="title" id="title" class="form-control @error('title') is-invalid @enderror">
                    <option value="">Select Title</option>
                </select>
                @error('title')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="myeditorinstance">Content:</label>
                <textarea name="content" id="myeditorinstance" cols="30" rows="10" class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
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
            $('#policy_heading').on('change', function() {
                var policy_heading = $('#policy_heading').val();

                if(policy_heading) {
                    $.ajax({
                        url: '{{ route("company-accounting-policy.title", $company->id) }}',
                        method: 'POST',
                        data: {
                            policy_heading: policy_heading,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log('Response:', response);
                            if(response.success) {
                                $('#index').val(response.index);
                                var selectTitle = $('#title');
                                selectTitle.find('option:not(:first)').remove();
                                // Populate the select element
                                $.each(response.policy_titles, function(index, item) {
                                    selectTitle.append(
                                        $('<option>', {
                                            value: item.title,
                                            text: item.title
                                        })
                                    );
                                });

                                // Clear existing options except the first one (placeholder)

                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Error saving note: ' + (xhr.responseJSON?.message || error));
                            console.error('Error:', xhr.responseJSON);
                        }
                    });
                }
            });
        });
    </script>
@endpush