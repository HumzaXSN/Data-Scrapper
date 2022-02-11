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
                        <div class="alert alert-danger">
                            @foreach ($failures as $failure)
                            {{ $failure->row() }}
                            <br>
                            {{ $failure->attribute() }}
                            <br>
                            @foreach ($failure->errors() as $error )
                            {{ $error }}
                            @endforeach
                            @endforeach
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
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="" class="insert" data-name="fname" data-type="text" data-title="Enter First Name">{{ $failure->values()['first_name'] }}</a>
                                                </td>
                                                <td>{{ $failure->values()['last_name'] }}</td>
                                                <td>{{ $failure->values()['company'] }}</td>
                                                <td>{{ $failure->values()['title'] }}</td>
                                                <td>{{ $failure->values()['email'] }}</td>
                                                <td>{{ $failure->values()['country'] }}</td>
                                                <td>{{ $failure->values()['state'] }}</td>
                                                <td>{{ $failure->values()['city'] }}</td>
                                                <td>{{ $failure->values()['phone'] }}</td>
                                                <td>{{ $failure->values()['linkedin_profile'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>$.fn.poshytip={defaults:null}</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/js/jquery-editable-poshytip.min.js"></script>
<script>
    $.fn.editable.defaults.mode = 'inline';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
    });

    $('.insert').editable({
           url: "{{ route('update-contacts-page') }}",
           type: 'text',
           name: 'name',
           title: 'Enter name'
    });

</script>

@endsection
