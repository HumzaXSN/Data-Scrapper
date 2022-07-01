<script https="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>

    $("#bulk_update_column").change(function () {
        var selected_option = $('#bulk_update_column').val();

        switch (selected_option) {
            case "lead_status_id":
                $("#reached_count").hide();
                $("#lead_statuses").removeClass('d-none');
                $("#industries").addClass('d-none');
                $("#lists").addClass('d-none');
            break;
            case "industry_id":
                $("#reached_count").hide();
                $("#lead_statuses").addClass('d-none');
                $("#industries").removeClass('d-none');
                $("#lists").addClass('d-none');
            break;
            case "delete":
                $("#reached_count").hide();
                $("#lead_statuses").addClass('d-none');
                $("#lists").addClass('d-none');
                $("#industries").addClass('d-none');
            break;
            case "list_id":
                $("#reached_count").hide();
                $("#lead_statuses").addClass('d-none');
                $("#lists").removeClass('d-none');
                $("#industries").addClass('d-none');
            break;
            default:
                $("#reached_count").show();
                $("#lead_statuses").addClass('d-none');
                $("#lists").addClass('d-none');
                $("#industries").addClass('d-none');
        }
    });

    toastr.options.preventDuplicates = true

    $.ajaxSetup({
             headers:{
                 'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
             }
         });

    $(function () {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            // order: [[1, "desc"]],
            ajax: {
                url: "{{ route('contacts.index') }}",
                data: {
                    'list': "{{ $getList }}",
                    'startDate': "{{ $startDate }}",
                    'endDate': "{{ $endDate }}",
                },
                type: 'GET',
            },
            columns: [
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'flp_name',
                    name: 'first_name',
                },
                {
                    data: 'last_name',
                    className: 'd-none',
                    name: 'last_name',
                },
                {
                    data: 'ctl_name',
                    name: 'company'
                },
                {
                    data: 'title',
                    className: 'd-none',
                    name: 'title'
                },
                {
                    data: 'emailLink',
                    name: 'email'
                },
                {
                    data: 'csc_name',
                    name: 'country'
                },
                {
                    data: 'state',
                    className: 'd-none',
                    name: 'state'
                },
                {
                    data: 'city',
                    className: 'd-none',
                    name: 'city'
                },
                {
                    data: 'phone',
                    className: 'd-none',
                    name: 'phone'
                },
                {
                    data: 'pc_name',
                    name: 'reached_platform'
                },
                {
                    data: 'reached_count',
                    className: 'd-none',
                    name: 'reached_count'
                },
                {
                    data: 'source',
                    className: 'd-none',
                    name: 'source_id'
                },
                {
                    data: 'lead_status.status',
                    className: 'd-none',
                    name: 'lead_status.status'
                },
                {
                    data: 'industry',
                    name: 'industry.name'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        }).on('draw', function(){
                    $('input[name="contact_checkbox"]').each(function(){this.checked = false;});
                    $('input[name="main_checkbox"]').prop('checked', false);
                });

        $('.filter-input').keyup(function () {
            table.column($(this).data('column'))
                .search($(this).val())
                .draw();
        });

        $('.filter-select').change(function () {
            table.column($(this).data('column'))
                .search($(this).val())
                .draw();
        });

        table.on('click', '.editname', function() {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent');
            }

            var data = table.row($tr).data();
            console.log(data);

            $('#first_name').val(data['first_name']);
            $('#last_name').val(data['last_name']);

            $('#editmodalname').attr('action', '/contacts/' + data['id']);
            $('#showmore').attr('href', '/contacts/' + data['id'] + '/edit');
            $('#namemodal').modal('show');
        });

        table.on('click', '.editcompany', function() {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent');
            }

            var data = table.row($tr).data();
            console.log(data);

            $('#company').val(data['company']);
            $('#title').val(data['title']);
            $('#leadstatus').val(data['lead_status']['status']);

            $('#editmodalcompany').attr('action', '/contacts/' + data['id']);
            $('#company_showmore').attr('href', '/contacts/' + data['id'] + '/edit');
            $('#companymodal').modal('show');
        });

        table.on('click', '.editcountry', function() {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent');
            }

            var data = table.row($tr).data();
            console.log(data);

            $('#country').val(data['country']);
            $('#state').val(data['state']);
            $('#city').val(data['city']);
            $('#phone').val(data['phone']);

            $('#editmodalcountry').attr('action', '/contacts/' + data['id']);
            $('#country_showmore').attr('href', '/contacts/' + data['id'] + '/edit');
            $('#countrymodal').modal('show');
        });

        table.on('click', '.editplatform', function() {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent');
            }

            var data = table.row($tr).data();
            console.log(data);

            $('#platfrom').val(data['reached_platform']);
            $('#timesreached').val(data['reached_count']);
            $('#businessPlatform').val(data['source']['name']);

            $('#editmodalplatform').attr('action', '/contacts/' + data['id']);
            $('#platform_showmore').attr('href', '/contacts/' + data['id'] + '/edit');
            $('#platformmodal').modal('show');
        });

        table.on('click', '.editindsutry', function() {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent');
            }

            var data = table.row($tr).data();
            console.log(data);

            $('#industry').val(data['industry']['name']);

            $('#editmodalindustry').attr('action', '/contacts/' + data['id']);
            $('#industry_showmore').attr('href', '/contacts/' + data['id'] + '/edit');
            $('#industrymmodal').modal('show');
        });

        $(document).on('click','input[name="main_checkbox"]', function(){
            if(this.checked){
                $('input[name="contact_checkbox"]').each(function(){
                    this.checked = true;
                });
            }else{
                $('input[name="contact_checkbox"]').each(function(){
                    this.checked = false;
                });
            }
        });

        $(document).on('change','input[name="contact_checkbox"]', function(){
            var checkedContacts = [];
               $('input[name="contact_checkbox"]:checked').each(function(){
                   checkedContacts.push($(this).data('id'));
               });
               $("#record_range").val(checkedContacts.join(','));
               if( $('input[name="contact_checkbox"]').length == $('input[name="contact_checkbox"]:checked').length ){
                   $('input[name="main_checkbox"]').prop('checked', true);
               }else{
                   $('input[name="main_checkbox"]').prop('checked', false);
               }
           });

        $(document).on('change','input[name="main_checkbox"]', function(){
            var checkedMain = [];
            if(this.checked) {
                $('input[name="contact_checkbox"]').each(function(){
                checkedMain.push($(this).data('id'));
                });
            }
            $("#record_range").val(checkedMain.join(','));
        });

    });

</script>
