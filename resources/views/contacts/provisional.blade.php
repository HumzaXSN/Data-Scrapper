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
                        <h4 class="mb-0">Check Contacts</h4>
                        @if (isset($failures))
                            <div class="alert alert-success">
                                {{ $success_row }} row were successfully imported.
                            </div>
                            <div class="alert alert-danger">
                                {{ count($failures) }} row were not imported.
                            </div>
                            @if (in_array('email', $errorsMsgs))
                                <div class="alert alert-danger">
                                    <strong>Email</strong> already exists in the database.
                                </div>
                            @endif
                            @if (in_array('first_name', $errorsMsgs))
                                <div class="alert alert-danger">
                                    <strong>First Name</strong> is empty.
                                </div>
                            @endif
                        @endif
                        @if (isset($arr))
                            <div class="alert alert-danger">
                                {{ count($arr) }} row were not imported.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="mb-4 card card-shadow">
                        <div class="card-header mb-3">
                            <div class="card-title">
                                Update Contact
                                <a class="btn btn-danger" style="float: right" href="{{ route('contacts.index') }}"> Skip </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form action="{{ route('update-contacts-page') }}" method="post">
                                        @csrf
                                        @if(isset($failures))
                                        <table id="editable" class="table table-bordered data-table">
                                            <thead>
                                                <tr>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Company</th>
                                                    <th>Title</th>
                                                    <th>Email</th>
                                                    <th>Country</th>
                                                    <th>State</th>
                                                    <th>City</th>
                                                    <th>Phone</th>
                                                    <th>LinkedIn Profile</th>
                                                    <th>Industry</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($failures as $failure)
                                                <tr>
                                                    <td>
                                                        <input
                                                            class="form-control @if($failure[0]['first_name'] == null) is-invalid @endif"
                                                            type="text" name="fname[]" placeholder="Enter Firstname"
                                                            value="{{ $failure[0]['first_name'] }}">
                                                    </td>
                                                    <td> <input class="form-control" type="text" name="lname[]"
                                                            placeholder="Enter Lastname"
                                                            value="{{ $failure[0]['last_name'] }}" required>
                                                    </td>
                                                    <td> <input class="form-control" type="text" name="company[]"
                                                            placeholder="Enter Company name"
                                                            value="{{ $failure[0]['company'] }}"> </td>
                                                    <td> <input class="form-control" type="text" name="title[]"
                                                            placeholder="Enter Title"
                                                            value="{{ $failure[0]['title'] }}"> </td>
                                                    <td>
                                                         <input
                                                            class="form-control @if($failure[0]['first_name'] != null || isset($failure[1])) is-invalid @endif"
                                                            type="email" name="email[]" placeholder="Enter E-mail"
                                                            value="{{ $failure[0]['email'] }}" required>
                                                    </td>
                                                    <td> <input class="form-control" type="text" name="country[]"
                                                            placeholder="Enter Country"
                                                            value="{{ $failure[0]['country'] }}"> </td>
                                                    <td> <input class="form-control" type="text" name="state[]"
                                                            placeholder="Enter State"
                                                            value="{{ $failure[0]['state'] }}" required> </td>
                                                    <td> <input class="form-control" type="text" name="city[]"
                                                            placeholder="Enter City"
                                                            value="{{ $failure[0]['city'] }}" required> </td>
                                                    <td> <input class="form-control" type="text" name="phone[]"
                                                            placeholder="Enter Phone"
                                                            value="{{ $failure[0]['phone'] }}"> </td>
                                                    <td> <input class="form-control" type="text"
                                                            name="linkedin_profile[]"
                                                            placeholder="Enter LinkedIn Profile"
                                                            value="{{ $failure[0]['linkedin_profile'] }}"> </td>
                                                    <td> <select class="form-control" name="industry_id[]">
                                                            @if ($failure[0]['industry'] == 'Healthcare')
                                                            <option value="2" selected hidden>Healthcare</option>
                                                            @endif
                                                            @if ($failure[0]['industry'] == 'Software House')
                                                            <option value="3" selected hidden>Software House</option>
                                                            @endif
                                                            @foreach($industry as $industries)
                                                            <option value="{{$industries->id}}">{{$industries->name}}
                                                            </option>
                                                            @endforeach
                                                        </select> </td>
                                                </tr>
                                                <input class="form-control" type="hidden" name="source[]"
                                                    value="{{ $source }}">
                                                @endforeach
                                                <input class="form-control" type="hidden" name="listId"
                                                    value="{{ $listId }}">
                                            </tbody>
                                        </table>
                                        @endif
                                        @if(isset($arr))
                                        <table class="table table-bordered data-table">
                                            <thead>
                                                <tr>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Company</th>
                                                    <th>Title</th>
                                                    <th>Email</th>
                                                    <th>Country</th>
                                                    <th>State</th>
                                                    <th>City</th>
                                                    <th>Phone</th>
                                                    <th>LinkedIn Profile</th>
                                                    <th>Industry</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($arr as $data )
                                                    <tr>
                                                        <td>
                                                            <input class="form-control @if($data['first_name'] == null) is-invalid @endif" type="text" name="fname[]"
                                                            value="{{ $data['first_name'] }}">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" name="lname[]"
                                                            value="{{ $data['last_name'] }}">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" name="company[]"
                                                            value="{{ $data['company'] }}">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" name="title[]"
                                                            value="{{ $data['title'] }}">
                                                        </td>
                                                        <td>
                                                            <input class="form-control @if(!empty($getEmail)) is-invalid @endif" type="text" name="email[]"
                                                            value="{{ $data['email'] }}">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" name="country[]"
                                                            value="{{ $data['country'] }}">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" name="state[]"
                                                            value="{{ $data['state'] }}">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" name="city[]"
                                                            value="{{ $data['city'] }}">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" name="phone[]"
                                                            value="{{ $data['phone'] }}">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" name="linkedin_profile[]"
                                                            value="{{ $data['linkedin_profile'] }}">
                                                        </td>
                                                        <td>
                                                            <select class="form-control" name="industry_id[]">
                                                                <option class="d-none" value="{{ $data['industry_id'] }}">Selected</option>
                                                                @foreach($industry as $industries)
                                                                    <option value="{{$industries->id}}">{{$industries->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <input class="form-control" type="hidden" name="source[]"
                                                    value="{{ $data['source'] }}">
                                                @endforeach
                                                <input class="form-control" type="hidden" name="listId"
                                                    value="{{ $listId }}">
                                            </tbody>
                                        </table>
                                        @endif
                                        <center>
                                        <button type="submit" class="btn btn-primary">Update Records</button>
                                        </center>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@include('partials.footer')
@endsection
