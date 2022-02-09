<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script https="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>

    $("#bulk_update_column").change(function () {
        var selected_option = $('#bulk_update_column').val();

        switch (selected_option) {
            case "lead_status_id":
                $("#reached_count").hide();
                $("#lead_statuses").removeClass('d-none');
                $("#industries").addClass('d-none');
            break;
            case "industry_id":
                $("#reached_count").hide();
                $("#lead_statuses").addClass('d-none');
                $("#industries").removeClass('d-none');
            break;
            case "delete":
                $("#reached_count").hide();
                $("#lead_statuses").addClass('d-none');
                $("#industries").addClass('d-none');
            break;
            default:
                $("#reached_count").show();
                $("#lead_statuses").addClass('d-none');
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
            ajax: "{{ route('contacts.index') }}",
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
                    data: 'email',
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
                    data: 'lead_status.status',
                    className: 'd-none',
                    name: 'lead_status.status'
                },
                {
                    data: 'industry.name',
                    name: 'industry.name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        }).on('draw', function(){
                    $('input[name="contact_checkbox"]').each(function(){this.checked = false;});
                    $('input[name="main_checkbox"]').prop('checked', false);
                    $('button#deleteAllBtn').addClass('d-none');
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
            toggledeleteAllBtn();
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
               toggledeleteAllBtn();
           });

        function toggledeleteAllBtn(){
               if( $('input[name="contact_checkbox"]:checked').length > 0 ){
                   $('button#deleteAllBtn').text('Delete ('+$('input[name="contact_checkbox"]:checked').length+')').removeClass('d-none');
               }else{
                   $('button#deleteAllBtn').addClass('d-none');
               }
           }

        $(document).on('click','button#deleteAllBtn', function(){
               var checkedContacts = [];
               $('input[name="contact_checkbox"]:checked').each(function(){
                   checkedContacts.push($(this).data('id'));
               });

               var url = '{{ route("delete.selected.contacts") }}';
               if(checkedContacts.length > 0){
                   swal.fire({
                       title:'Are you sure?',
                       html:'You want to delete <b>('+checkedContacts.length+')</b> contacts',
                       showCancelButton:true,
                       showCloseButton:true,
                       confirmButtonText:'Yes, Delete',
                       cancelButtonText:'Cancel',
                       confirmButtonColor:'#556ee6',
                       cancelButtonColor:'#d33',
                       width:300,
                       allowOutsideClick:false
                   }).then(function(result){
                       if(result.value){
                           $.post(url,{contacts_ids:checkedContacts},function(data){
                              if(data.code == 1){
                                  $('#contact-table').DataTable().ajax.reload(null, true);
                                  toastr.success(data.msg);
                              }
                           },'json');
                       }
                   })
               }
           });
    });

</script>
