@if(Session::has('success'))
    <script>
        toastr.options = {
            "progressBar": true,
            "closeButton": true,
        }
        toastr.success("{!!Session::get('success') !!}");
    </script>
@endif

@if(Session::has('error'))
    <script>
        toastr.options = {
            "progressBar": true,
            "closeButton": true,
        }
        toastr.error("{!!Session::get('error') !!}");
    </script>
@endif

@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.error("{{ $error }}");
        </script>
    @endforeach
@endif
