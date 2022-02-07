@extends('layouts.app')

@section('content')
@include('partials.header')

<div class="app-body">
    @include('partials.left_sidebar')
    <main class="main-content main_content_styling">
        <!--page title start-->
        <div class="page-title">
            <div class="p-0 container-fluid">
                <div class="row">
                    <div class="col-8">
                        <h4 class="mb-0">Contacts</h4>
                    </div>
                    <div class="col-12">
                        <div class="float-right ml-2 btn-group">
                            <a class="btn btn-success" href="{{ route('contacts.create') }}"> Import Contacts</a>
                        </div>
                        <button class="mb-4 btn btn-primary mx-a" data-toggle="modal" data-target="#myModal"> Add new
                        </button>
                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('add-contact') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Enter Contact to Create</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <strong>First Name:</strong>
                                            <input class="form-control" type="text" name="fname"
                                                placeholder="Enter Firstname" required>
                                            <strong class="mt-2">Last Name:</strong>
                                            <input class="form-control mt-2" type="text" name="lname"
                                                placeholder="Enter Lastname" required>
                                            <strong class="mt-2">Title:</strong>
                                            <input class="form-control mt-2" type="text" name="title"
                                                placeholder="Enter Title">
                                            <strong class="mt-2">Company:</strong>
                                            <input class="form-control mt-2" type="text" name="company"
                                                placeholder="Enter Company">
                                            <strong class="mt-2">Email:</strong>
                                            <input class="form-control mt-2" type="email" name="email"
                                                placeholder="Enter Email">
                                            <strong class="mt-2">Country:</strong>
                                            <input class="form-control mt-2" type="text" name="country"
                                                placeholder="Enter Country">
                                            <strong class="mt-2">State:</strong>
                                            <input class="form-control mt-2" type="text" name="state"
                                                placeholder="Enter State">
                                            <strong class="mt-2">City:</strong>
                                            <input class="form-control mt-2" type="text" name="city"
                                                placeholder="Enter City">
                                            <strong class="mt-2">Phone:</strong>
                                            <input class="form-control mt-2" type="number" name="phone"
                                                placeholder="Enter Phone">
                                            <strong class="mt-2">Reached Platform:</strong>
                                            <input class="form-control mt-2" type="text" name="reach_platform"
                                                placeholder="Enter Reached Platform">
                                            <strong class="mt-2">LinkedIn Profile:</strong>
                                            <input class="form-control mt-2" type="text" name="linkedin_profile"
                                                placeholder="Enter Reached Platform">
                                            <strong class="mt-2">Industry:</strong>
                                            <select class="form-control" name="industry_id">
                                                <option selected disabled>Select Industry</option>
                                                @foreach($industries as $industry)
                                                <option value="{{$industry->id}}">{{$industry->name}}</option>
                                                @endforeach
                                            </select>
                                            <strong class="mt-2">Lead Status:</strong>
                                            <select class="form-control" name="lead_status_id">
                                                <option selected disabled>Select Lead Status</option>
                                                @foreach($leadstatuses as $leadstatus)
                                                <option value="{{$leadstatus->id}}">{{$leadstatus->status}}</option>
                                                @endforeach
                                            </select>
                                            <strong class="mt-2">Source:</strong>
                                            <select class="form-control" name="source">
                                                <option selected disabled>Source</option>
                                                <option value="1">Internal</option>
                                                <option value="2">External</option>
                                            </select>
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
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
                            <div class="col-sm-3 mb-2 mb-md-0">
                                <input type="text" id="fn" class="form-control filter-input" placeholder="Firstname"
                                    data-column="2" />
                            </div>
                            <div class="col-sm-3 mb-2 mb-md-0">
                                <input type="text" id="fn" class="form-control filter-input" placeholder="Lastname"
                                    data-column="3" />
                            </div>
                            <div class="col-sm-3 mb-2 mb-md-0">
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

                        <hr class="border-bulk">

                        <div class="card-header mb-3 pt-0">
                            <div class="card-title">
                                Bulk Update
                            </div>
                        </div>
                        <form method="POST" action="{{ route('bulk-update') }}">
                            @csrf
                            <div class="row ml-1 mr-1">
                                <div class="col-sm-4 mb-2 mb-md-0">
                                    <select class="form-control" id="bulk_update_column" name="bulk_update_column">
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
                                <div class="col-sm-4 mb-2 mb-md-0">
                                    <input id="record_range" value="" type="text"
                                        class="form-control @error('record_range')  is-invalid @enderror"
                                        name="record_range" placeholder="XX-XX OR XX,XX">
                                </div>
                                <div class="col-sm-4 mb-2 mb-md-0">
                                    <input type="text" id="reached_count" class="form-control" name="reached_count"
                                        placeholder="Enter your desired value">
                                </div>
                                <div class="col-sm-4 mt-2 mb-md-0 d-none" id="lead_statuses">
                                    <select class="form-control" name="lead_status_id">
                                        <option selected> Lead Status </option>
                                        @foreach($leadstatuses as $leadstatus)
                                        <option value="{{$leadstatus->id}}">{{$leadstatus->status}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 mt-2 mb-md-0 d-none" id="industries">
                                    <select class="form-control" name="industry_id">
                                        <option selected> Industry </option>
                                        @foreach($industries as $industry)
                                        <option value="{{$industry->id}}">{{$industry->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-12 text-center mt-2">
                                    <button type="submit" class="btn bg-success text-white border-0 waves-effect"
                                        aria-label="Close">Update Record
                                    </button>
                                </div>
                            </div>
                        </form>

                        <hr class="border-bulk">

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered data-table" id="contact-table" style="width:100%">
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
                                            <th>Action <button class="btn btn-sm btn-danger d-none"
                                                    id="deleteAllBtn">Delete Selected</button></th>
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
