@extends('layouts.app')

@section('content')
@include('partials.header')

<div class="app-body">
    @include('partials.left_sidebar')
    <main class="main-content">
        <div class="container-fluid">

            <div class="mt-4 mb-4 card-group no-shadow">
                <h1>Ashlar Data Scraper</h1>
            </div>

        </div>
    </main>
</div>

@include('partials.footer')
@endsection
