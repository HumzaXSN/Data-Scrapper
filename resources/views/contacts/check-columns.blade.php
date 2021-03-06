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
                        <h4 class="mb-0">Compare Headings</h4>
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
                                Map Headings
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form action="{{ route('contacts.mapHeadings') }}" method="POST">
                                        @csrf
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th class="text-center "><h4>System Columns</h4></th>
                                                    <th class="text-center"><h4>Excel Columns</h4></th>
                                                </tr>
                                                @foreach ($check_columns as $key => $contact_heading)
                                                    @if(!is_null($contact_heading))
                                                        <tr>
                                                            <th scope="row">{{ strtolower(str_replace(' ','', str_replace('_','', $contact_heading))) }}</th>
                                                            <td>
                                                                <select class="form-control columns" id="column_{{$key}}" name="columns[]" required>
                                                                    <option value="">Please Select</option>
                                                                    @foreach ($get_contact_heading as $index => $columns)
                                                                        @if($loop->first)
                                                                            <option value="NULL" selected>Ignore This Column</option>
                                                                        @endif
                                                                        @if (!$loop->last)
                                                                            <option value="{{ $columns }}"
                                                                            @if(strtolower(str_replace(' ','', str_replace('_','', $contact_heading))) == strtolower(str_replace(' ','', str_replace('_','', $columns))))
                                                                                selected
                                                                            @endif
                                                                            >{{ ucfirst($columns) }}</option>
                                                                        @else
                                                                            <option value="{{ $columns }}"
                                                                            @if(strtolower(str_replace(' ','', str_replace(' _','', $contact_heading)))==strtolower(str_replace(' ','', str_replace(' _','', $columns))))
                                                                                selected
                                                                            @endif
                                                                            >{{ ucfirst($columns) }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <div style="float: right">
                                                            <button type="submit" class="btn btn-primary">Insert Records</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" value="{{ $file }}" name="file">
                                                        <input type="hidden" value="{{ $sourceId }}" name="sourceId">
                                                        <input type="hidden" value="{{ $listId }}" name="listId">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
