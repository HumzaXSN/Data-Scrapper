@extends('layouts.app')

@section('content')
@include('partials.header')

<div class="app-body">
    @include('partials.left_sidebar')
    <main class="main-content main_content_styling">
        <div class="page-title">
            <div class="p-0 container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@include('partials.footer')
@endsection
