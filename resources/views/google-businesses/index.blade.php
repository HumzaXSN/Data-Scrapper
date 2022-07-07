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
                        <div class="card-header">
                            <div class="card-title">
                                Businesses
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('scraper-criteria.exportBusiness') }}" method="GET" >
                                @csrf
                                {{-- <button type="submit" class="btn btn-success">Export Selected Business</button> --}}
                                <div class="table-responsive">
                                    {{ $dataTable->table() }}
                                </div>
                            </form>
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
@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
