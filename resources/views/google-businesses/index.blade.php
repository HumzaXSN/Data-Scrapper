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
                        <h4 class="mb-0">Businesses</h4>
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
                        <form action="{{ route('scraper-criteria.exportBusiness') }}" method="GET">
                            @csrf
                            <div class="card-header showButtons">
                                <div class="card-title">
                                    Businesses
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success">Export Selected Business</button>
                                    <button type="submit" onclick="pickValue()" class="btn btn-primary">Validate Selected Business</button>
                                    <button type="submit" onclick="getValue()" class="btn btn-danger">Un-Validate Selected Business</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    {{ $dataTable->table() }}
                                </div>
                            </div>
                            <input type="hidden" name="getVal" value=""/>
                        </form>
                    </div>
                </div>
            </div>
            <!-- state end-->

        </div>
    </main>
</div>

@include('partials.footer')
@endsection
@push('scripts')
    {{ $dataTable->scripts() }}
    <script>
        function pickValue() {
            var input = $('input[name="getVal"]');
            input.val('1');
        }

        function getValue() {
            var input = $('input[name="getVal"]');
            input.val('2');
        }
    </script>
@endpush
