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
                                        <strong>Email</strong> is empty.
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
                                                             value="{{ $failure[0]['first_name'] }}" required>
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
                                                                value="{{ $failure[0]['email'] }}" required>
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
                                                                @if ($failure[0]['industry'] == 'Healthcare')
                                                                <option value="2" selected hidden>Healthcare</option>
                                                                @endif
                                                                @if ($failure[0]['industry'] == 'Software House')
                                                                <option value="3" selected hidden>Software House</option>
                                                                @endif
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
