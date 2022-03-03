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
                            {{ $failures->count() }} row were not imported.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if (isset($failures))
        @if ($failures->count() > 0)
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="mb-4 card card-shadow">
                        <div class="card-header mb-3">
                            <div class="card-title">
                                Update Contact
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form action="{{ route('update-contacts-page') }}" method="post">
                                        @csrf
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
                                                    <td>@foreach ($failure->errors() as $error )
                                                        @php
                                                        $isError = true;
                                                        @endphp
                                                        @endforeach <input
                                                            class="form-control @if(isset($isError)) is-invalid @endif"
                                                            type="text" name="fname[]" placeholder="Enter Firstname"
                                                            value="{{ $failure->values()['first_name'] }}">
                                                    </td>
                                                    <td> <input class="form-control" type="text" name="lname[]"
                                                            placeholder="Enter Lastname"
                                                            value="{{ $failure->values()['last_name'] }}" required>
                                                    </td>
                                                    <td> <input class="form-control" type="text" name="company[]"
                                                            placeholder="Enter Company name"
                                                            value="{{ $failure->values()['company'] }}"> </td>
                                                    <td> <input class="form-control" type="text" name="title[]"
                                                            placeholder="Enter Title"
                                                            value="{{ $failure->values()['title'] }}"> </td>
                                                    <td>@foreach ($failure->errors() as $error )
                                                        @php
                                                        $isError = true;
                                                        @endphp
                                                        @endforeach <input
                                                            class="form-control @if(isset($isError)) is-invalid @endif"
                                                            type="email" name="email[]" placeholder="Enter E-mail"
                                                            value="{{ $failure->values()['email'] }}" required>
                                                    </td>
                                                    <td> <input class="form-control" type="text" name="country[]"
                                                            placeholder="Enter Country"
                                                            value="{{ $failure->values()['country'] }}"> </td>
                                                    <td> <input class="form-control" type="text" name="state[]"
                                                            placeholder="Enter State"
                                                            value="{{ $failure->values()['state'] }}" required> </td>
                                                    <td> <input class="form-control" type="text" name="city[]"
                                                            placeholder="Enter City"
                                                            value="{{ $failure->values()['city'] }}" required> </td>
                                                    <td> <input class="form-control" type="text" name="phone[]"
                                                            placeholder="Enter Phone"
                                                            value="{{ $failure->values()['phone'] }}"> </td>
                                                    <td> <input class="form-control" type="text"
                                                            name="linkedin_profile[]"
                                                            placeholder="Enter LinkedIn Profile"
                                                            value="{{ $failure->values()['linkedin_profile'] }}"> </td>
                                                    <td> <select class="form-control" name="industry_id[]">
                                                            @if ($failure->values(['industry'] == 'Healthcare'))
                                                            <option value="2" selected hidden>Healthcare</option>
                                                            @endif
                                                            @if ($failure->values(['industry'] == 'Software House'))
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
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-between">
                                            <button type="submit" class="btn btn-primary">Update Records</button>
                                            <button type="submit" class="btn btn-danger">Skip Records</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <center>
            <a class="btn btn-primary" href="{{ route('contacts.index') }}">Go to Contacts Page</a>
        </center>
        @endif
        @endif
    </main>
</div>

@include('partials.footer')
@endsection
