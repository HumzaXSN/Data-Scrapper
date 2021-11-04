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
                                            <input type="text" class="form-control  @error('first_name') is-invalid @enderror" name="first_name" value="{{ $contact->first_name }}" placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Last Name:</strong>
                                            <input type="text" class="form-control  @error('last_name') is-invalid @enderror" name="last_name" value="{{ $contact->last_name }}" placeholder="Last Name">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Title:</strong>
                                            <input type="text" class="form-control  @error('title') is-invalid @enderror" name="title" value="{{ $contact->title }}" placeholder="Title">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Company:</strong>
                                            <input type="text" class="form-control  @error('company') is-invalid @enderror" name="company" value="{{ $contact->company }}" placeholder="Company">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <strong>Email:</strong>
                                            <input type="email" class="form-control  @error('email') is-invalid @enderror" name="email" value="{{ $contact->email }}" placeholder="Email address">
                                        </div>
                                    </div>
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
