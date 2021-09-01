@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card">
                    <div class="card-title">
                        <h2>CSV Import</h2>
                    </div>
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <div class="card-body">
                        <form class="form-horizontal" method="POST" action="{{ route('import') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
                                <label for="csv_file" class="col-md-4 card-subtitle text-muted">CSV file to import</label>

                                <div class="col-md-6">
                                    <input type="file" class="form-control" name="csv_file" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="source" class="col-md-4 card-subtitle text-muted">Source</label>
                                <div class="col-md-6">
                                    <label><input type="radio" id="internal" name="source" value="1">Internal</label>
                                </div>
                                <div class="col-md-6">
                                    <label><input type="radio" id="external" name="source" value="0">External</label>
                                </div>
                             </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Import
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
