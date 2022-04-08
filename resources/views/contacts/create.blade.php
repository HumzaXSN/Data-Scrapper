@extends('layouts.app')

@section('content')
@include('partials.header')

<div class="app-body">
    @include('partials.left_sidebar')
    <main class="main-content">
        <!--page title start-->
        <div class="page-title">
            <h4 class="mb-0">
                Import CSV
            </h4>
        </div>
        <!--page title end-->


        <div class="container-fluid">

            <!-- state start-->
            <div class="row">
                <div class=" col-sm-12">
                    <div class="mb-4 card card-shadow">
                        <div class="card-header">
                            <div class="card-title">
                                Import CSV
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="picker-form" method="POST" action="{{ route('contacts.store') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Select CSV File</label>
                                    <div class="col-sm-4">
                                        <input type="file" class="form-control" name="csv_file" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Source</label>
                                    <div class="col-sm-4">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" id="internal" name="source" value="1" required>
                                                Internal
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" id="external" name="source" value="0" required>
                                                External
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @if (isset($list))
                                <input type="hidden" value={{ $list }} name="listId"/>
                                @endif
                                <center>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </center>
                            </form>
                            <div class="card-header border-0">
                                <div class="card-title">
                                Plese make sure that the Excel file is in the following format:
                                </div>
                                <center>
                                <a href="https://docs.google.com/spreadsheets/d/11w3F4xkK5Uo0Rg8XsXhHDULtxU9FUOicq5hgbCnBxjQ/edit?usp=sharing" class="btn btn-primary">Sample Sheet</a>
                                <center>
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
