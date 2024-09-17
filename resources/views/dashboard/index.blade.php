@extends('layouts.main')

@section('content')
    <section class="section" id="home">
        <div class="container2 text-center" >

            <div class="container2" style="width: 100%;">

            </div>
        </div>
    </section>

@endsection



@section('script')

    <script>
        $(document).ready(function() {
            var successMessage = "{{ session('success') }}";
            var errorMessage = "{{ session('error') }}";

            if (successMessage) {
                toastr.success(successMessage);
            }

            if (errorMessage) {
                toastr.error(errorMessage);
            }

            $(".tanggal").flatpickr({
                allowInput: true,
                dateFormat: "d-m-Y"
            });

            var table = $('#dttable').DataTable({
                "order": [[0, 'desc']],
                "columnDefs": [
                    { "targets": 0, "visible": false } // Menyembunyikan kolom pertama
                ],
            });

            /
        });

    </script>
@endsection
