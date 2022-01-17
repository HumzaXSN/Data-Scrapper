<script>
    $(function () {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('contacts.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'last_name',
                    name: 'last_name'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'company',
                    name: 'company'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'country',
                    name: 'country'
                },
                {
                    data: 'city',
                    name: 'city'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'reached_platform',
                    name: 'reached_platform'
                },
                {
                    data: 'lead_status_id',
                    name: 'lead_status_id'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
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

    })

</script>
{{-- <script>
    $(document).ready(function () {
                $('#fn').on('keyup',function() {
                    // alert('good');
            var query = $(this).val();
                    // var from = $('#from').val();
                    $.ajax({
                        type: "GET",
                        url: "{{ route('contacts.index') }}",
                        data: {
                            'to': query
                        },
                        success: function (data) {
                            // $('#country_list').html(data);
                        }
                    })
                });
            });

</script> --}}
