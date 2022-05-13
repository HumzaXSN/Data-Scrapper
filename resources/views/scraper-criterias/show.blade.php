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
                        <div>
                            <ol class="breadcrumb no-bg mb-0">
                                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('scraper-criterias.index') }}">Scraper Criteria</a></li>
                                <li class="breadcrumb-item active">Views</li>
                            </ol>
                        </div>
                        {{-- <div>
                            <a class="btn btn-primary" href="{{ route('contacts.index',['list' => $list->id]) }}" style="margin-bottom: 24px;">Show Contacts</a>
                        </div> --}}
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
                                            <strong>Status:</strong>
                                            {{ $scraperCriteria->status }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <strong>KeyWord:</strong>
                                            {{ $scraperCriteria->keyword }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <strong>Location:</strong>
                                            {{ $scraperCriteria->location }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <strong>Limit:</strong>
                                            {{ $scraperCriteria->limit }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <strong>Created At:</strong>
                                            {{ $scraperCriteria->created_at }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <strong>Updated At:</strong>
                                            {{ $scraperCriteria->updated_at }}
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
