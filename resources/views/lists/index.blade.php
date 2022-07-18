@extends('layouts.app')

@section('content')
@include('partials.header')

<div class="app-body">
    @include('partials.left_sidebar')
    <main class="main-content main_content_styling">
        <div class="page-title">
            <div class="p-0 container-fluid">
                <div class="row">
                    <div class="col-8">
                        <h4 class="mb-0">Lists</h4>
                    </div>
                    <div class="col-12" style="display: flex; justify-content: space-between;">
                        <ol class="breadcrumb no-bg mb-0">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Lists</li>
                        </ol>
                        <button class="mb-4 btn btn-primary mx-a" data-toggle="modal" data-target="#myModal"> Add new</button>

                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('lists.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Enter List to Create</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <strong>Region:</strong>
                                            <input class="form-control" type="text" name="region"
                                                placeholder="Enter Title" required>
                                            <strong>Industry:</strong>
                                            <input class="form-control" type="text" name="industry"
                                                placeholder="Enter Industry" required>
                                            <strong>Title:</strong>
                                            <input class="form-control" type="text" name="title"
                                                placeholder="Enter Title" required>
                                            <strong>Created By:</strong>
                                            <input class="form-control" type="text" name="createdBy"
                                                placeholder="Created By" required>
                                            <strong>Description:</strong>
                                            <textarea class="form-control" type="text" name="description"
                                                placeholder="Enter description" required></textarea>
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class=" col-sm-12">
                    <div class="mb-4 card card-shadow">
                        <div class="card-header mb-3">
                            <div class="card-title">
                                Lists
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable">
                                    {{ $dataTable->table() }}
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@include('partials.footer')
@endsection
@push('scripts')
    {{$dataTable->scripts()}}
@endpush
