@extends('layouts.app')

@section('header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 d-inline mr-2">Edit Accounting Policy</h1>
                    <a href="{{ route('accounting-policy.create') }}" class="btn btn-outline-primary btn-sm mb-3">Add Accounting Policy</a>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('accounting-policy.index') }}">Accounting Policy</a></li>
                        <li class="breadcrumb-item active">Edit Accounting Policy</li>
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
        <form action="{{ route('accounting-policy.update', $policy->id) }}" method="POST" class="form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title">Title: <span class="text-danger">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $policy->title) }}" class="form-control @error('title') is-invalid @enderror" placeholder="Enter Title" readonly="">
                @error('title')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="myeditorinstance">Content:</label>
                <textarea name="content" id="myeditorinstance" cols="30" rows="3" class="form-control @error('content') is-invalid @enderror">{{ old('content', $policy->content) }}</textarea>
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
            /* $('#policy_heading').on('change', function(){
                var policy_heading = $('#policy_heading').val();

                if (policy_heading) {
                    $.ajax({
                        url: '{{ route("accounting-policy.index") }}',
                        method: 'POST',
                        data: {
                            action: 'edit',
                            policy_id: {{ $policy->id }},
                            industry_id: industry,
                            policy_heading: policy_heading,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log('Response:', response);
                            if(response.success) {
                                $('#index').val(response.index);
                                // Clear existing options except the first one (placeholder)
                            } else {
                                $('#index').val('');
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Error saving note: ' + (xhr.responseJSON?.message || error));
                            console.error('Error:', xhr.responseJSON);
                        }
                    });
                } else {
                    $('#index').val('');
                }
            }); */
        });
    </script>
@endpush