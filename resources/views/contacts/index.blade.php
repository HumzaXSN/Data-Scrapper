@extends('layouts.app')

@section('content')
@include('partials.header')

<div class="app-body">
    @include('partials.left_sidebar')
    <main class="main-content" style="width: 100%;overflow:hidden;">
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
                                Filters
                            </div>
                        </div>
                        <div class="row ml-1 mr-1">
                            <div class="col-sm-3">
                                <input type="text" id="fn" class="form-control filter-input" placeholder="Firstname"
                                     data-column="2" />
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="fn" class="form-control filter-input" placeholder="Lastname"
                                     data-column="3" />
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control filter-input" placeholder="Title"
                                    data-column="4" />
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control filter-input" placeholder="Company"
                                    data-column="5" />
                            </div>
                            <div class="col-sm-3 mt-2">
                                <input type="text" class="form-control filter-input" placeholder="Country"
                                    data-column="7" />
                            </div>
                            <div class="col-sm-3 mt-2">
                                <input type="text" class="form-control filter-input" placeholder="State"
                                    data-column="8" />
                            </div>
                            <div class="col-sm-3 mt-2">
                                <input type="text" class="form-control filter-input" placeholder="City"
                                    data-column="9" />
                            </div>
                            <div class="col-sm-3 mt-2">
                                <input type="text" class="form-control filter-input" placeholder="Platform Reached"
                                    data-column="11" />
                            </div>
                            <div class="col-sm-3 mt-2">
                                <input type="number" class="form-control filter-input" placeholder="Reach Count"
                                    data-column="12" />
                            </div>
                            <div class="col-sm-3 mt-2">
                                <select class="form-control filter-select" data-column="13">
                                    <option value="">Lead Status</option>
                                    @foreach ($leadstatuses as $contact )
                                    <option value="{{ $contact->id }}">{{ $contact->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <select class="form-control filter-select" data-column="14">
                                    <option value="">Industries</option>
                                    @foreach ($industries as $industry )
                                    <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr style=" border-color: #eceaea; border-width: 1px; ">

                        <div class="card-header mb-3 pt-0">
                            <div class="card-title">
                                Bulk Update
                            </div>
                        </div>
                        <form method="POST" action="{{ route('bulk-update') }}">
                            @csrf
                            <div class="row ml-1 mr-1">
                                <div class="col-sm-4">
                                    <select class="form-control" name="bulk_update_column">
                                        <option selected> Select Option </option>
                                        <option value="country"> Country </option>
                                        <option value="state"> State </option>
                                        <option value="city"> City </option>
                                        <option value="reached_platform"> Reached Platform </option>
                                        <option value="reached_count"> Times Reached </option>
                                        <option value="lead_status_id"> Lead status </option>
                                        <option value="industry_id"> Industry </option>
                                        <option value="delete"> Delete Record </option>
                                    </select>
                                    </div>
                                <div class="col-sm-4">
                                <input type="text" class="form-control @error('record_range')  is-invalid @enderror"
                                    name="record_range" placeholder="XX-XX">
                                </div>
                                <div class="col-sm-4">
                                <input type="text" id="reached_count"
                                    class="form-control @error('reached_count')  is-invalid @enderror" name="reached_count"
                                    placeholder="Enter your desired value">
                                </div>
                                <div class="col-sm-12 text-center mt-3">
                                <button type="submit" class="btn bg-success text-white border-0 waves-effect"
                                    aria-label="Close">Change Record
                                </button>
                                </div>
                            </div>
                        </form>

                        <hr style=" border-color: #eceaea; border-width: 1px; ">

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered data-table" id="contact-table" style="width:100%" >
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" name="main_checkbox"><label></label></th>
                                            <th>id</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Title</th>
                                            <th>Company</th>
                                            <th>Email</th>
                                            <th>Country</th>
                                            <th>State</th>
                                            <th>City</th>
                                            <th>Phone</th>
                                            <th>Reached Platform</th>
                                            <th>Times Reached</th>
                                            <th>Lead Status</th>
                                            <th>Industry</th>
                                            <th>Action <button class="btn btn-sm btn-danger d-none" id="deleteAllBtn">Delete Selected</button></th>
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
