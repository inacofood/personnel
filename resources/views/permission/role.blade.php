@extends('layouts.main')

@section('content')

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4 d-flex justify-content-between align-items-center">
                    Tambah Data Role
                </h5>
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="department_name" class="form-label">Nama Department</label>
                        <input type="text" class="form-control" id="department_name" name="department_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4 d-flex justify-content-between align-items-center">
                    Data Roles
                </h5>
                <table id="dttable" class="table table-striped mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Nama Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                               <td>{{ $role->department_name }}</td>
                                <td>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this role?')">
                                        <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $('#roles').select2({
        placeholder: 'Choose',
        theme: 'bootstrap4'
    });

    $('.menu-item').on('click', function() {
        $('#sidebarCollapse').click();
});
</script>
@endsection
