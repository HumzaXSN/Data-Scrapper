<div class="col">
    <div class="btn-group task-list-action">
        <div class="dropdown">
            <a href="#" class="p-0 p-2 border btn btn-transparent default-color dropdown-hover" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="icon-options"></i>
            </a>
            <div class="dropdown-menu" x-placement="bottom-start"
                style="position: absolute; transform: translate3d(0px, 39px, 0px); top: 0px; left: 0px; will-change: transform;">
                <a class="dropdown-item" href="{{ url('/google-businesses/'.$googleBusiness->id)}}">
                    <i class="pr-2 ti-eye text-primary"></i>
                    View
                </a>
                <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal"
                    data-target=".google-business-delete-modal-sm-{{$googleBusiness->id}}">
                    <i class="pr-2 icon-close text-danger"></i>
                    Delete
                </a>
                <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal"
                    data-target="#myModal-sm-{{$googleBusiness->id}}">
                    <i class="ti-layers-alt text-warning"></i>
                    Validate Data
                </a>
                <a class="dropdown-item" href="{{ route('scraper-criteria.exportBusiness', ['googleBusinessId' => $googleBusiness->id, 'googleBusinessCompany' => $googleBusiness->company]) }}" >
                    <i class="ti-export text-secondary"></i>
                    Export Business
                </a>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="myModal-sm-{{$googleBusiness->id}}">
    <div class="modal-dialog modal-dialog-centered validate-modal">
        <div class="modal-content">
            <form action="{{ route('insert-business-contact', ['googleBusinessId' => $googleBusiness->id]) }}" method="post">
                @csrf
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Check Data to Validate</h4>
                    <a class="btn btn-secondary ml-3" onclick="addDecisionMaker({{ $googleBusiness->id }})">Add Decision Maker</a>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row p-3">
                        <div class="col-sm-12">
                            @foreach ($googleBusiness->decisionMakers as $decisionMaker)
                                @if (isset($decisionMaker->name))
                                    <div class="modal fade decision-maker-delete-modal-sm-{{$decisionMaker->id}}" tabindex="-1" role="dialog"
                                        aria-labelledby="mySmallModalLabel-{{$decisionMaker->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="mySmallModalLabel">
                                                        Delete
                                                        <strong>{{ $decisionMaker->name }}</strong>
                                                    </h6>
                                                    <button type="button" class="close" onclick="closeModal({{ $decisionMaker->id }})" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6>Are you sure?</h6><br>
                                                    <div class="float-right">
                                                        <a onclick="deleteDecisionMaker({{ $decisionMaker->id }})" class="btn btn-primary mx-a">Yes
                                                        </a>
                                                        <button type="button" onclick="closeModal({{ $decisionMaker->id }})" class="btn btn-secondary mx-a">No
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center-{{ $decisionMaker->id }}">
                                        <div class="col-sm-5">
                                            <strong>Name:</strong>
                                            {{ $decisionMaker->name }}
                                        </div>
                                        <div class="col-sm-4">
                                            <strong>URL:</strong>
                                            <a href="{{ $decisionMaker->url }}" target="_blank">{{ $decisionMaker->url }}</a>
                                        </div>
                                        <div class="col-sm-1">
                                            @if (isset($decisionMaker->decisionMakerEmails->first()->email))
                                                <a class="fa-2x ti-email text-warning" data-toggle="collapse"
                                                    href="#collapseExample-{{ $decisionMaker->id }}" role="button" aria-expanded="false"
                                                    aria-controls="collapseExample"></a>
                                            @else
                                                <a href="#" onclick="showEmailInput({{ $decisionMaker->id }})"
                                                    class="fa-2x ti-email text-warning showEmail-{{ $decisionMaker->id }} fa-2x"></a>
                                            @endif
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#" onclick="deleteData({{ $decisionMaker->id }})"
                                                class="fa-2x icon-close text-danger fa-2x"></a>
                                        </div>
                                        @if ($decisionMaker->validate == 0)
                                            <div class="col-sm-1">
                                                <a href="#" onclick="validateContact({{ $decisionMaker->id }})"
                                                    class="fa-2x icon-check text-success-{{ $decisionMaker->id }} fa-2x"></a>
                                            </div>
                                        @else
                                            <div class="col-sm-1">
                                                <a href="#" class="fa-2x icon-check text-muted fa-2x"></a>
                                            </div>
                                        @endif
                                        <div class="collapse col-6 mx-auto" id="collapseExample-{{ $decisionMaker->id }}">
                                            <div class="card card-body">
                                                <div class="row d-none showData-{{ $decisionMaker->id }}">
                                                    <div
                                                        class="col-12 text-center d-flex justify-content-between align-items-center mb-2 removeData-{{ $decisionMaker->id }}">
                                                        <div class="d-flex align-items-center">
                                                            <strong class="form-control-label mr-2">Email:</strong>
                                                            <input type="email" name="email" class="form-control readAddEmail-{{ $decisionMaker->id }} edit-email-{{ $decisionMaker->id }}">
                                                        </div>
                                                        <div>
                                                            <a href="#" onclick="successShownEmailData({{ $decisionMaker->id }})"
                                                                class="pr-2 icon-check text-success fa-2x getSuccess-{{ $decisionMaker->id }}"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @foreach ($decisionMaker->decisionMakerEmails as $decisionEmail )
                                                    <div class="row">
                                                        <div class="col-12 text-center d-flex justify-content-between align-items-center mb-2 removeData-{{ $decisionEmail->id }}">
                                                            <div class="d-flex align-items-center">
                                                                <strong class="form-control-label mr-2">Email:</strong>
                                                                <input type="email" name="email" class="form-control edit-email-{{ $decisionEmail->id }}"
                                                                    value="{{ $decisionEmail->email }}" readonly>
                                                            </div>
                                                            <div>
                                                                <a href="#" onclick="successEmailData({{ $decisionEmail->id }})" class="pr-2 icon-check text-success fa-2x success-{{ $decisionEmail->id }}" hidden></a>
                                                                <a href="#" onclick="editEmailData({{ $decisionEmail->id }})" class="pr-2 ti-write text-primary edit-{{ $decisionEmail->id }} fa-2x"></a>
                                                                <a href="#" onclick="deleteEmailData({{ $decisionEmail->id }})" class="pr-2 icon-close text-danger fa-2x"></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <div class="appendRow"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add Validated Emails to Contact</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade google-business-delete-modal-sm-{{$googleBusiness->id}}" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel-{{$googleBusiness->id}}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="mySmallModalLabel">
                    Delete
                    <strong>{{ $googleBusiness->company }}</strong>
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>Are you sure?</h6><br>
                <form action="{{url('google-businesses/'.$googleBusiness->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <div class="float-right">
                        <button type="submit" class="btn btn-primary mx-a">Yes
                        </button>
                        <button type="button" class="btn btn-secondary mx-a" data-dismiss="modal">No
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteData(id) {
        $('.decision-maker-delete-modal-sm-' + id).modal('show');
    }

    function deleteDecisionMaker(id) {
        var url = "{{ route('delete-business-name') }}";
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                toastr.success('Business deleted successfully');
                toastr.options.closeButton = true;
                toastr.options.closeDuration = 100;
                $('.align-items-center-' + id).remove();
                $('.showRow').remove();
                $('.decision-maker-delete-modal-sm-' + id).modal('hide');
            }
        });
    }

    function closeModal(id){
        $('.decision-maker-delete-modal-sm-' + id).modal('hide');
    }

    function validateContact(id) {
        var url = "{{ route('validate-business-contact') }}";
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                if ($('.text-success-' + id).hasClass('fa-2x')) {
                    $('.text-success-' + id).addClass('fa-2x text-muted');
                    $('.text-success-' + id).removeAttr('onClick');
                }
                $('.validateEmailAfterAdding').removeAttr('onClick').removeClass('text-info').addClass('text-muted');
            },
            error: function(data) {
                $('#collapseExample-' + id).show();
                $('.showData-' + id).removeClass('d-none');
            }
        });
    }

    function showEmailInput(id) {
        if ($('.showData-'+ id).hasClass('d-none')){
            $('#collapseExample-' + id).show();
            $('.showData-' + id).removeClass('d-none');
        } else {
            $('#collapseExample-' + id).hide();
            $('.showData-' + id).addClass('d-none');
        }
    }

    function deleteEmailData(id) {
        var url = "{{ route('delete-business-email') }}";
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                $('.removeData-' + id).remove();
            }
        });
    }

    function editEmailData(id) {
        $('.edit-' + id).hide();
        $('.success-' + id).removeAttr('hidden');
        $('.edit-email-' + id).removeAttr('readonly');
    }

    function successEmailData(id) {
        var email = $('.edit-email-' + id).val();
        var url = "{{ route('success-business-email') }}";
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                id: id,
                email: email,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                $('.edit-' + id).show();
                $('.success-' + id).attr('hidden', 'true');
                $('.edit-email-' + id).attr('readonly', 'true');
            }
        });
    }

    function successShownEmailData(id) {
        var email = $('.edit-email-' + id).val();
        var url = "{{ route('success-new-business-email') }}";
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                id: id,
                email: email,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                if ($('.getSuccess-' + id).hasClass('fa-2x')) {
                    $('.getSuccess-' + id).removeClass('text-success');
                    $('.getSuccess-' + id).addClass('fa-2x text-muted');
                    $('.getSuccess-' + id).removeAttr('onClick');
                    $('.readAddEmail-' + id).attr('readonly', 'true');
                }
            },
            error: function(data) {
                alert('Email is Required');
            }
        });
    }

    function addDecisionMaker(id) {
        $('.appendRow').append(
            '<div class="row mb-2 align-items-center showRow">' +
                '<div class="col-sm-3">' +
                    '<strong>Name:</strong>' +
                    '<input type="text" class="form-control" name="name[]" placeholder="Name">' +
                '</div>' +
                '<div class="col-sm-4">' +
                    '<strong>URL:</strong>' +
                    '<input type="text" class="form-control" name="url[]" placeholder="URL">' +
                '</div>' +
                '<div class="col-sm-3">' +
                    '<strong>e-mail:</strong>' +
                    '<input type="email" class="form-control" name="email[]" placeholder="E-mail">' +
                '</div>' +
                '<div class="col-sm-1 finishFinishRow">' +
                    '<a href="#" class="fa-2x icon-close text-danger fa-2x finishRow" onclick="deleteRow(this)">' + '</a>' +
                '</div>' +
                '<div class="col-sm-1 validateFinishRow">' +
                    '<a href="#" class="pr-2 icon-check text-info fa-2x getRow" onclick="validateRow(this,' + id + ')">' + '</a>' +
                '</div>' +
            '</div>'
        );
    }

    function deleteRow(element) {
        $(element).parent().parent().remove();
    }

    function validateRow(element, id) {
        var url = "{{ route('add-decision-maker') }}";
        var name = $(element).parent().parent().find('input[name="name[]"]').val();
        var getUrl = $(element).parent().parent().find('input[name="url[]"]').val();
        var email = $(element).parent().parent().find('input[name="email[]"]').val();
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                id: id,
                name: name,
                email: email,
                url: getUrl,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                $(element).parent().parent().find('input[name="name[]"]').attr('readonly',true);
                $(element).parent().parent().find('input[name="url[]"]').attr('readonly',true);
                $(element).parent().parent().find('input[name="email[]"]').attr('readonly',true);
                $(element).parent().parent().find('.finishRow').hide();
                $(element).parent().parent().find('.getRow').hide();
                toastr.success('Successfully Added');
                toastr.options.closeButton = true;
                toastr.options.closeDuration = 100;
                $('.finishFinishRow').append(
                    '<a href="#" class="fa-2x icon-close text-danger" onclick="deleteDecisionMaker(' + data.decision_maker_id + ')">' + '</a>'
                );
                $('.validateFinishRow').append(
                    '<a href="#" class="fa-2x icon-check text-info validateEmailAfterAdding" onclick="validateContact(' + data.decision_maker_id + ')">' + '</a>'
                );
            },
            error: function(data) {
                alert('Name and URL both are Required');
            }
        });
    }
</script>
