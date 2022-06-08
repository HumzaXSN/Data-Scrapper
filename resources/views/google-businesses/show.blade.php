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
                                <h4 class="mb-0">Google Business</h4>
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
                                        Google Business
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <strong>Company:</strong>
                                                    {{ $googleBusiness->company }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <strong>Phone:</strong>
                                                    {{ $googleBusiness->phone }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <strong>Address:</strong>
                                                    {{ $googleBusiness->address }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <strong>Website:</strong>
                                                    {{ $googleBusiness->website }}
                                                </div>
                                            </div>
                                        </div>
                                        @if (isset($googleBusiness->url))
                                            <div class="col-xs-6 col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <strong>URL:</strong>
                                                        <a href="{{ $googleBusiness->url }}" target="_blank">{{ $googleBusiness->url }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- state end-->
                    {{-- Start Second State --}}
                    @if (isset($googleBusiness->decisionMakers[0]->name))
                        <div class="row">
                            <div class=" col-sm-12">
                                <div class="mb-4 card card-shadow">
                                    <div class="card-header">
                                        <div class="card-title">
                                            Names Reterived from Google
                                        </div>
                                    </div>
                                    <div class="row p-3">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            @foreach ($googleBusiness->decisionMakers as $decisionMaker)
                                            @if (isset($decisionMaker->name))
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <strong>Name:</strong>
                                                    {{ $decisionMaker->name }}
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            @foreach ($googleBusiness->decisionMakers as $decisionMaker)
                                            @if (isset($decisionMaker->name))
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <strong>URL:</strong>
                                                    <a href="{{ $decisionMaker->url }}" target="_blank">{{ $decisionMaker->url }}</a>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- End Second State --}}
                </div>
            </main>
        </div>
    @include('partials.footer')
@endsection