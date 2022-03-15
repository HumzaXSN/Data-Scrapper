@extends('layouts.app')

@section('content')
@include('partials.header')

<div class="app-body">
    @include('partials.left_sidebar')
    <main class="main-content">

        <!--page title start-->
        <div class="page-title">
            <div class="p-0 container-fluid">
                <div class="row">
                    <div class="col-8">
                        <h4 class="mb-0">Lists</h4>
                    </div>
                    <div class="col-12" style="display: flex; justify-content: space-between;">
                        <ol class="breadcrumb no-bg mb-0">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('lists.index') }}">List</a></li>
                            <li class="breadcrumb-item active">Views</li>
                        </ol>
                        <button class="mb-4 btn btn-success mx-a" data-toggle="modal" data-target="#myModal"> Add Contacts to List </button>

                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Add Contacts to List</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <form class="picker-form" method="POST" action="{{ route('lists.store') }}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label col-form-label-sm">Select CSV File</label>
                                                    <div class="col-sm-6">
                                                        <input type="file" class="form-control" name="csv_file" required>
                                                    </div>
                                                </div>
                                            </form>
                                            <center>
                                                <h5>OR</h5>
                                            </center>
                                            <hr class="border-bulk">
                                            <div>
                                                <div class="picker-form">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label col-form-label-sm">Choose from Contacts</label>
                                                        <a href="{{ route('contacts.index') }}">
                                                            <i class="fa fa-plus"></i>
                                                            Add Contacts
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--page title end-->

        <div class="container-fluid">

            <!-- state start-->
            <div class="row">
                <div class=" col-sm-12">
                    <div class="mb-4 card card-shadow">
                        <div class="card-header">
                            <div class="card-title">
                                View List
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <strong>Name:</strong>
                                            {{ $list->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <strong>Description:</strong>
                                            {{ $list->description }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <strong>Type:</strong>
                                            {{ $list->listType->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <strong>User:</strong>
                                            {{ $list->user->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- state end-->

        </div>
    </main>
</div>

@include('partials.footer')
@endsection
