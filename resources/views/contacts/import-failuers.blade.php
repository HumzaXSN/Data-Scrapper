@extends('layouts.app')

@section('content')
    @include('partials.header')
        <div class="app-body">
            @include('partials.left_sidebar')
            <main class="main-content">
                <div class="page-title"></div>
                <div class="container-fluid">

                    <div class="text-center">
                        <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">Failuers Found</h4>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        @if (!empty($checkSpace) || !empty($checkDash))
                                            <p>
                                                <strong>
                                                    @if (!empty($checkSpace))
                                                        <span class="text-danger">
                                                            <i class="fa fa-exclamation-triangle"></i>
                                                            <span>
                                                                The following fields have spaces, please remove them and try importing again:
                                                                @foreach ( $checkSpace as $checkSpaces )
                                                                   <br>
                                                                   {{ $checkSpaces }}
                                                                @endforeach
                                                            </span>
                                                        </span>
                                                    @endif
                                                    <br> <br>
                                                    @if (isset($checkDash))
                                                        <span class="text-danger">
                                                            <i class="fa fa-exclamation-triangle"></i>
                                                            <span>
                                                                The following fields have dashes, please remove them and try importing again:
                                                                @foreach ( $checkDash as $checkDashes )
                                                                    <br>
                                                                    {{ $checkDashes }}
                                                                @endforeach
                                                            </span>
                                                        </span>
                                                    @endif
                                                </strong>
                                            </p>
                                        @else
                                            <div class="alert alert-success">
                                                {{ $getRow }} row were successfully imported.
                                            </div>
                                            @foreach ($importFailures as $failure)
                                                @php
                                                    $reflection = new ReflectionClass($failure);
                                                    $property = $reflection->getProperty('row');
                                                    $errorProperty = $reflection->getProperty('errors');
                                                    $property->setAccessible(true);
                                                    $errorProperty->setAccessible(true);
                                                    $getRowNo = $property->getValue($failure);
                                                    $errorRow = $errorProperty->getValue($failure);
                                                @endphp
                                            <div class="alert alert-danger">
                                                <strong>Row: {{ $getRowNo }}</strong> - {{ $errorRow[0] }}
                                            </div>
                                            @endforeach
                                        @endif

                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer text-center">
                                        <a class="btn btn-success" href="{{ route('contacts.index') }}"> OK </a>
                                    </div>
                                </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    @include('partials.footer')
@endsection
