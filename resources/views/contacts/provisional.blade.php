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
                                        Make sure the <strong>Email</strong> format is correct and is not empty.
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
                                                    @if (isset($failures[array_key_first($failures)][0]['last_name']))
                                                    <th>Last Name</th>
                                                    @endif
                                                    @if (isset($failures[array_key_first($failures)][0]['company']))
                                                        <th>Company</th>
                                                    @endif
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($failures as $failure)
                                                    <tr>
                                                        <td> <input
                                                             class="form-control @if($failure[0]['first_name'] == null) is-invalid @endif"
                                                             type="text" name="fname[]" placeholder="Enter Firstname"
                                                             value="{{ $failure[0]['first_name'] }}">
                                                        </td>
                                                        @if (isset($failure[0]['last_name']))
                                                            <td> <input
                                                                 class="form-control"
                                                                 type="text" name="lname[]" placeholder="Enter Lastname"
                                                                 value="{{ $failure[0]['last_name'] }}">
                                                            </td>
                                                        @endif
                                                        @if (isset($failure[0]['company']))
                                                            <td> <input
                                                                 class="form-control"
                                                                 type="text" name="company[]" placeholder="Enter Company"
                                                                 value="{{ $failure[0]['company'] }}">
                                                            </td>
                                                        @endif
                                                        <td>
                                                             <input
                                                                class="form-control  @if($failure[0]['first_name'] != null || isset($failure[1])) is-invalid @endif"
                                                                type="email" name="email[]" placeholder="Enter E-mail"
                                                                value="{{ $failure[0]['email'] }}">
                                                        </td>
                                                        @if (isset($failure[0]['title']))
                                                            <td class="d-none"> <input
                                                                type="text" name="title[]" placeholder="Enter Title"
                                                                value="{{ $failure[0]['title'] }}">
                                                            </td>
                                                        @endif
                                                        @if (isset($failure[0]['country']))
                                                            <td class="d-none"> <input class="form-control d-none" type="hidden" name="country[]"
                                                                placeholder="Enter Country"
                                                                value="{{ $failure[0]['country'] }}"> </td>
                                                        @endif
                                                        @if (isset($failure[0]['state']))
                                                            <td class="d-none"> <input class="form-control d-none" type="hidden" name="state[]"
                                                                placeholder="Enter State"
                                                                value="{{ $failure[0]['state'] }}"> </td>
                                                        @endif
                                                        @if (isset($failure[0]['city']))
                                                            <td class="d-none"> <input class="form-control d-none" type="hidden" name="city[]"
                                                                placeholder="Enter City"
                                                                value="{{ $failure[0]['city'] }}"> </td>
                                                        @endif
                                                        @if (isset($failure[0]['phone']))
                                                            <td class="d-none"> <input type="hidden" name="phone[]"
                                                                placeholder="Enter Phone"
                                                                value="{{ $failure[0]['phone'] }}"> </td>
                                                        @endif
                                                        @if (isset($failure[0]['linkedin_profile']))
                                                            <td class="d-none"> <input type="hidden" name="linkedIn_profile[]"
                                                                placeholder="Enter LinkedIn Profile"
                                                                value="{{ $failure[0]['linkedin_profile'] }}"> </td>
                                                        @endif
                                                        @if (isset($failure[0]['industry']))
                                                            <td class="d-none"> <select name="industry_id[]">
                                                                <option value="{{ $failure[0]['industry'] }}" selected hidden>{{ $failure[0]['industry'] }}</option>
                                                            </select> </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                <input class="form-control" type="hidden" name="source"
                                                        value="{{ $source }}">
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
                                                    @if (isset($arr[0]['last_name']))
                                                        <th>Last Name</th>
                                                    @endif
                                                    @if (isset($arr[0]['company']))
                                                        <th>Company</th>
                                                    @endif
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($arr as $data )
                                                    <tr>
                                                        <td>
                                                            <input placeholder="Enter Firstname" class="form-control @if($data['first_name'] == null) is-invalid @endif" type="text" name="fname[]"
                                                            value="{{ $data['first_name'] }}">
                                                        </td>
                                                        @if (isset($data['last_name']))
                                                            <td>
                                                                <input placeholder="Enter Lastname" class="form-control" type="text" name="lname[]"
                                                                value="{{ $data['last_name'] }}">
                                                            </td>
                                                        @endif
                                                        @if (isset($data['company']))
                                                            <td>
                                                                <input placeholder="Enter Company" class="form-control" type="text" name="company[]"
                                                                value="{{ $data['company'] }}">
                                                            </td>
                                                        @endif
                                                        <td>
                                                            <input placeholder="Enter Email" class="form-control @if($data['email'] == null) is-invalid @endif" type="text" name="email[]"
                                                            value="{{ $data['email'] }}">
                                                        </td>
                                                        @if (isset($data['title']))
                                                            <td class="d-none">
                                                                <input type="hidden" name="title[]"
                                                                value="{{ $data['title'] }}">
                                                            </td>
                                                        @endif
                                                        @if (isset($data['country']))
                                                            <td class="d-none">
                                                                <input type="hidden" name="country[]"
                                                                value="{{ $data['country'] }}">
                                                            </td>
                                                        @endif
                                                        @if (isset($data['state']))
                                                            <td class="d-none">
                                                                <input type="hidden" name="state[]"
                                                                value="{{ $data['state'] }}">
                                                            </td>
                                                        @endif
                                                        @if (isset($data['city']))
                                                            <td class="d-none">
                                                                <input type="hidden" name="city[]"
                                                                value="{{ $data['city'] }}">
                                                            </td>
                                                        @endif
                                                        @if (isset($data['phone']))
                                                            <td class="d-none">
                                                                <input type="hidden" name="phone[]"
                                                                value="{{ $data['phone'] }}">
                                                            </td>
                                                        @endif
                                                        @if (isset($data['linkedin_profile']))
                                                            <td class="d-none">
                                                                <input type="hidden" name="linkedin_profile[]"
                                                                value="{{ $data['linkedin_profile'] }}">
                                                            </td>
                                                        @endif
                                                        @if (isset($data['industry_id']))
                                                        <td class="d-none">
                                                            <select name="industry_id[]">
                                                                <option value="{{ $data['industry_id'] }}">{{ $data['industry_id'] }}</option>
                                                            </select>
                                                        </td>
                                                        @endif
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
        </main>
    </div>

    @include('partials.footer')
@endsection
