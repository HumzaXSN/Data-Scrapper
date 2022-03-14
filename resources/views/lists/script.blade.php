<script type="text/javascript">

    $(function () {
        var table = $('.datatable').DataTable();
            processing: true,
            serverSide: true,
            ajax: "{{ route('lists.index') }}",
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'Type',
                    name: 'Type'
                },
                {
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
    });

</script>
