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
                        <div class="card-header">
                            <div class="card-title">
                                Contacts
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('contacts.update',$contact->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>First Name:</strong>
                                            <input type="text"
                                                class="form-control  @error('first_name') is-invalid @enderror"
                                                name="first_name" value="{{ $contact->first_name }}"
                                                placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Last Name:</strong>
                                            <input type="text"
                                                class="form-control  @error('last_name') is-invalid @enderror"
                                                name="last_name" value="{{ $contact->last_name }}"
                                                placeholder="Last Name">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Title:</strong>
                                            <input type="text"
                                                class="form-control  @error('title') is-invalid @enderror" name="title"
                                                value="{{ $contact->title }}" placeholder="Title">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Company:</strong>
                                            <input type="text"
                                                class="form-control  @error('company') is-invalid @enderror"
                                                name="company" value="{{ $contact->company }}" placeholder="Company">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Email:</strong>
                                            <input type="email"
                                                class="form-control  @error('email') is-invalid @enderror" name="email"
                                                value="{{ $contact->email }}" placeholder="Email address">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Phone:</strong>
                                            <input type="number" id="edit_phone"
                                                class="form-control  @error('phone') is-invalid @enderror" name="phone"
                                                value="{{ $contact->phone }}" placeholder="Phone Number">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>City:</strong>
                                            <input type="text" class="form-control  @error('city') is-invalid @enderror"
                                                name="city" value="{{ $contact->city }}" placeholder="Enter City">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>State:</strong>
                                            <input type="text"
                                                class="form-control  @error('state') is-invalid @enderror" name="state"
                                                value="{{ $contact->state }}" placeholder="Enter State">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>LinkedIn Profile:</strong>
                                            <input type="text"
                                                class="form-control  @error('linkedin_profile') is-invalid @enderror"
                                                name="linkedin_profile" value="{{ $contact->linkedin_profile }}"
                                                placeholder="Enter Linkedin Profile">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Profile Reached:</strong>
                                            <input type="number"
                                                class="form-control  @error('times_reached') is-invalid @enderror"
                                                name="times_reached" value="{{ $contact->times_reached }}"
                                                placeholder="How many times the clinet is reached">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Reached from Platform:</strong>
                                            <input type="text"
                                                class="form-control  @error('reached_platform') is-invalid @enderror"
                                                name="reached_platform" value="{{ $contact->reached_platform }}"
                                                placeholder="Through which platform the Client is Reached">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong class="edit_form_status">Status:</strong>
                                            <div class="form-check edit_form_status_data">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" id="Hot Lead"
                                                        name="lead_status" value="Hot-Lead">
                                                    Hot Lead
                                                </label>
                                            </div>
                                            <div class="form-check edit_form_status_data">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" id="Cold Lead"
                                                        name="lead_status" value="Cold-Lead">
                                                    Cold Lead
                                                </label>
                                            </div>
                                            <div class="form-check edit_form_status_data">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" id="Follow Lead"
                                                        name="lead_status" value="Follow-Lead">
                                                    Follow Lead
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @if (count($industries) > 0)
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Industry:</strong>
                                            <select class="form-control" name="industry_id">
                                                @foreach($industries as $industry)
                                                <option value="{{$industry->id}}">{{$industry->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
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
