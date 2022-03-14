@extends('layouts.app')

@section('content')
@include('partials.header')

<div class="app-body">
    @include('partials.left_sidebar')
    <main class="main-content main_content_styling">
        <div class="page-title">
            <div class="p-0 container-fluid">
                <div class="row">
                    <div class="col-8">
                        <h4 class="mb-0">Lists</h4>
                    </div>
                    <div class="col-12" style="display: flex; justify-content: space-between;">
                        <ol class="breadcrumb no-bg mb-0">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">Lists</li>
                        </ol>
                        <button class="mb-4 btn btn-primary mx-a" data-toggle="modal" data-target="#myModal"> Add new</button>

                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('lists.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Enter List to Create</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <strong>Name:</strong>
                                            <input class="form-control" type="text" name="name"
                                                placeholder="Enter Name" required>
                                            <strong>Description:</strong>
                                            <input class="form-control" type="text" name="description"
                                                placeholder="Enter description" required>
                                            <strong>Type:</strong>
                                            <select class="form-control" name="type" required>
                                                <option></option>
                                                @foreach ($list_type as $type) )
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class=" col-sm-12">
                    <div class="mb-4 card card-shadow">
                        <div class="card-header mb-3">
                            <div class="card-title">
                                Lists
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th>Created By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lists as $list)
                                        <tr>
                                            <td>{{ $list->id }}</td>
                                            <td>{{ $list->name }}</td>
                                            <td>{{ $list->description }}</td>
                                            <td>{{ $list->list_type->name}}</td>
                                            <td>{{ $list->user->name }}</td>
                                            <td>
                                                <a class="btn btn-success" data-toggle="modal" data-target="#myEdit{{$list->id}}">Edit</a>
                                                {{-- Update Data --}}
                                                <div class="modal fade" id="myEdit{{$list->id}}">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <form action="{{ route('lists.update',$list->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-content">
                                                                <!-- Modal Header -->
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Update List</h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>

                                                                <!-- Modal body -->
                                                                <div class="modal-body">
                                                                    <strong>Name:</strong>
                                                                    <input class="form-control" type="text" name="name"
                                                                        placeholder="Enter Name" value="{{ $list->name }}" required>
                                                                    <strong>Description:</strong>
                                                                    <textarea class="form-control" type="text" name="description"
                                                                        placeholder="Enter description" required>{{ $list->description }}</textarea>
                                                                    <strong>Type:</strong>
                                                                    <select class="form-control" name="type">
                                                                        <option value="{{ $list->list_type->id }}" selected hidden>{{ $list->list_type->name }}</option>
                                                                        @foreach ($list_type as $type) )
                                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <a class="btn btn-danger" data-toggle="modal" data-target="#myDelete{{$list->id}}">Delete</a>
                                                {{-- Delete Data --}}
                                                <div class="modal" id="myDelete{{$list->id}}">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <form action="{{ route('lists.destroy',$list->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="modal-content">
                                                                <!-- Modal Header -->
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Delete {{ $list->name }}</h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>

                                                                <!-- Modal body -->
                                                                <div class="modal-body">
                                                                    <strong>Are you sure you want to delete this list?</strong>
                                                                </div>

                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                    <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
@push('scripts')
@include('lists.script')
@endpush
