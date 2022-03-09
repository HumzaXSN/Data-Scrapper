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

                        {{-- Add New Modal --}}
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
                                                placeholder="Enter Email" required>
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
                        {{-- Add New Modal End --}}

                        {{-- Name Modal --}}
                        <div class="modal" id="namemodal">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="/contacts" method="POST" id="editmodalname">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            {{-- First Name --}}
                                            <strong>First Name:</strong>
                                            <input type="text"
                                                class="form-control"
                                                name="first_name" id="first_name"
                                                placeholder="First Name">
                                            {{-- Last Name --}}
                                            <strong>Last Name:</strong>
                                            <input type="text"
                                                class="form-control"
                                                name="last_name" id="last_name"
                                                placeholder="Last Name">
                                        </div>
                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <a class="btn btn-danger" id="showmore" href="#"> Show More </a>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- Name Modal End --}}

                        {{-- Company Detail --}}
                        <div class="modal" id="companymodal">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="/contacts" method="POST" id="editmodalcompany">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            {{-- Company --}}
                                            <strong>Company:</strong>
                                            <input type="text"
                                                class="form-control"
                                                name="company" id="company"
                                                placeholder="Company">
                                            {{-- Title --}}
                                            <strong>Title:</strong>
                                            <input type="text"
                                                class="form-control"
                                                name="title" id="title"
                                                placeholder="title">
                                            {{-- Lead Status --}}
                                            <strong class="mt-2">Lead Status:</strong>
                                            <input type="text"
                                                class="form-control"
                                                name="leadstatus" id="leadstatus"
                                                placeholder="Lead Status" disabled>
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <a class="btn btn-danger" id="company_showmore" href="#"> Show More </a>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- Company Detail End --}}

                        {{-- Country --}}
                        <div class="modal" id="countrymodal">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="/contacts" method="POST" id="editmodalcountry">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            {{-- Country --}}
                                            <strong>Country:</strong>
                                            <input type="text"
                                                class="form-control"
                                                name="country" id="country"
                                                placeholder="Country">
                                            {{-- State --}}
                                            <strong>State:</strong>
                                            <input type="text"
                                                class="form-control"
                                                name="state" id="state"
                                                placeholder="State">
                                            {{-- City --}}
                                            <strong class="mt-2">City:</strong>
                                            <input type="text"
                                                class="form-control"
                                                name="city" id="city"
                                                placeholder="City">
                                            {{-- Phone --}}
                                            <strong class="mt-2">Phone:</strong>
                                            <input type="text"
                                                class="form-control"
                                                name="phone" id="phone"
                                                placeholder="Phone">
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <a class="btn btn-danger" id="country_showmore" href="#"> Show More </a>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- Country End --}}

                        {{-- Platform & Times --}}
                        <div class="modal" id="platformmodal">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="/contacts" method="POST" id="editmodalplatform">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            {{-- Platform --}}
                                            <strong>Platfrom:</strong>
                                            <input type="text"
                                                class="form-control"
                                                name="reached_platform" id="platfrom"
                                                placeholder="Platfrom">
                                            {{-- Times --}}
                                            <strong>Times Reached:</strong>
                                            <input type="number"
                                                class="form-control"
                                                name="reached_count" id="timesreached"
                                                placeholder="Times Reached">
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <a class="btn btn-danger" id="platform_showmore" href="#"> Show More </a>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- Platform & Times End --}}

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
                                <input type="text" class="form-control filter-input" placeholder="Company"
                                    data-column="4" />
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control filter-input" placeholder="Title"
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
                                <input type="number" class="form-control filter-input" placeholder="phone"
                                    data-column="10" />
                            </div>
                            <div class="col-sm-3 mt-2">
                                <input type="text" class="form-control filter-input" placeholder="Platform reached"
                                    data-column="11" />
                            </div>
                            <div class="col-sm-3 mt-2">
                                <input type="number" class="form-control filter-input" placeholder="Times reached"
                                    data-column="12" />
                            </div>
                            <div class="col-sm-3 mt-2">
                                <select class="form-control filter-select" data-column="13">
                                    <option value="">Lead Status</option>
                                    @foreach ($leadstatuses as $contact )
                                    <option value="{{ $contact->status }}">{{ $contact->status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <select class="form-control filter-select" data-column="14">
                                    <option value="">Industries</option>
                                    @foreach ($industries as $industry )
                                    <option value="{{ $industry->name }}">{{ $industry->name }}</option>
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
                                            <th>Name</th>
                                            <th class="d-none">Last Name</th>
                                            <th>Company Detail</th>
                                            <th class="d-none">Title</th>
                                            <th>Email</th>
                                            <th>Lead Country</th>
                                            <th class="d-none">State</th>
                                            <th class="d-none">City</th>
                                            <th class="d-none">Phone</th>
                                            <th>Platform & Times Reached</th>
                                            <th class="d-none">Times Reached</th>
                                            <th class="d-none">Lead Status</th>
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
