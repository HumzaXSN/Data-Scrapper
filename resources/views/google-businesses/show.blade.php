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
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <strong>URL:</strong>
                                            {{ $googleBusiness->url }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- state end-->

            {{-- Start Second State --}}
            <div class="row">
                <div class=" col-sm-12">
                    <div class="mb-4 card card-shadow">
                        <div class="card-header">
                            <div class="card-title">
                                Names Reterived from Google
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if (isset($googleBusiness->decisionMakers[0]->name))
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <strong>Name:</strong>
                                                {{ $googleBusiness->decisionMakers[0]->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (isset($googleBusiness->decisionMakers[0]->url))
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <strong>URL:</strong>
                                                {{ $googleBusiness->decisionMakers[0]->url }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (isset($googleBusiness->decisionMakers[1]->name))
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <strong>Name:</strong>
                                                {{ $googleBusiness->decisionMakers[1]->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (isset($googleBusiness->decisionMakers[1]->url))
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <strong>URL:</strong>
                                                {{ $googleBusiness->decisionMakers[1]->url }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (isset($googleBusiness->decisionMakers[2]->name))
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <strong>Name:</strong>
                                                {{ $googleBusiness->decisionMakers[2]->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (isset($googleBusiness->decisionMakers[2]->url))
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <strong>URL:</strong>
                                                {{ $googleBusiness->decisionMakers[2]->url }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (isset($googleBusiness->decisionMakers[3]->name))
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <strong>Name:</strong>
                                                {{ $googleBusiness->decisionMakers[3]->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (isset($googleBusiness->decisionMakers[3]->url))
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <strong>URL:</strong>
                                                {{ $googleBusiness->decisionMakers[3]->url }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (isset($googleBusiness->decisionMakers[4]->name))
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <strong>Name:</strong>
                                                {{ $googleBusiness->decisionMakers[4]->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (isset($googleBusiness->decisionMakers[4]->url))
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <strong>URL:</strong>
                                                {{ $googleBusiness->decisionMakers[4]->url }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Second State --}}

        </div>
    </main>
</div>

@include('partials.footer')
@endsection
