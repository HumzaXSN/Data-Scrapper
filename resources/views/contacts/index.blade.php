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
                        <h4 class="mb-0">Contacts</h4>
                    </div>
                    <div class="col-4">
                        <div class="float-right ml-2 btn-group">
                            <a class="btn btn-success" href="{{ route('contacts.create') }}"> Import Contacts</a>
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
                        <div class="card-header mb-3">
                            <div class="card-title">
                                Contacts
                            </div>
                        </div>
                        <div class="row ml-1 mr-1">
                            <div class="col-sm-4">
                                <input type="text" id="fn" class="form-control filter-input" placeholder="Search Firstname"
                                     />
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control filter-input" placeholder="Search City"
                                    data-column="6" />
                            </div>
                            <div class="col-sm-4">
                                <select class="form-control filter-select" data-column="10">
                                    <option value="">Lead Status</option>
                                    @foreach ($leadstatuses as $contact )
                                    <option value="{{ $contact->id }}">{{ $contact->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr style=" border-color: #919191; border-width: 3px; ">

                        <form method="POST" action="{{ route('reached-count') }}">
                            @csrf
                            <div class="row ml-1 mr-1">
                                <div class="col-sm-4">
                                <input type="number" id="from" class="form-control @error('from')  is-invalid @enderror"
                                    name="from" placeholder="Enter Start of Record">
                                </div>
                                <div class="col-sm-4">
                                <input type="number" id="to" class="form-control @error('to')  is-invalid @enderror"
                                    name="to" placeholder="Enter End of Record">
                                </div>
                                <div class="col-sm-4">
                                <input type="number" id="reached_count"
                                    class="form-control @error('reached_count')  is-invalid @enderror" name="reached_count"
                                    placeholder="Ente amount to change">
                                </div>
                                <div class="col-sm-12 text-center mt-3">
                                <button type="submit" class="btn bg-success text-white border-0 waves-effect"
                                    aria-label="Close">Change Record
                                </button>
                                </div>
                            </div>
                        </form>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered data-table" style="width:100%" >
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Title</th>
                                            <th>Company</th>
                                            <th>Email</th>
                                            <th>Country</th>
                                            <th>City</th>
                                            <th>Phone</th>
                                            <th>Reached Platform</th>
                                            <th>Lead Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
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
@push('scripts')
@include('contacts.script')
@endpush
