@extends('layouts.main')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2-bootstrap4.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

<style>
    .form-control {
        border: 1px solid #007bff; 
        border-radius: 0.25rem;
    }

    .select2-container--bootstrap4 .select2-selection--multiple,
    .select2-container--bootstrap4 .select2-selection--single {
        border: 1px solid #007bff;
        border-radius: 0.25rem;
        box-shadow: none;
        height: 100%;
    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        color: white;
    }

    .text-center {
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Form User</h5>
                <form id="userForm" action="{{ route('users.store') }}" method="POST">
                    @csrf
                    @if(isset($user))
                        @method('PUT')
                    @endif
                    <input type="hidden" name="user_id" id="userId" value="{{ $user->id ?? '' }}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name ?? old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email ?? old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Department</label>
                        <select id="role" name="role" class="form-control select2" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ isset($user) && $user->role && $user->role->id == $role->id ? 'selected' : '' }}>
                                    {{ $role->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Update' : 'Submit' }}</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Data Users</h5>
                    <table id="dttable" class="table table-striped">
                        <thead class="text-center">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role ? $user->role->department_name : 'No Role' }}</td>
                                    <td style="width: 15%;" class="text-center">
                                        <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            data-user-email="{{ $user->email }}"
                                            data-user-role="{{ $user->role ? $user->role->id : '' }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
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

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="{{ route('users.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" id="editUserId">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Department</label>
                        <select id="editRole" name="role" class="form-control select2" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        $('#editRole').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#editModal') 
        });

        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var userId = button.data('user-id');
            var userName = button.data('user-name');
            var userEmail = button.data('user-email');
            var userRole = button.data('user-role');

            var modal = $(this);
            modal.find('#editUserId').val(userId);
            modal.find('#editName').val(userName);
            modal.find('#editEmail').val(userEmail);
            modal.find('#editRole').val(userRole).trigger('change');
        });
    });
</script>

<style>
   .modal-header {
    position: relative;
}

.close {
    background-color: transparent;
    padding: 0;
    border-radius: 50%;
    border: none; 
    width: 30px; 
    height: 30px; 
    position: absolute; 
    top: 50%; 
    right: 15px; 
    transform: translateY(-50%); 
    display: flex;
    justify-content: center;
    align-items: center;
}

.close span {
    color: black;
    font-size: 1.2rem; 
    line-height: 1; 
    display: inline-block; 
}
</style>



@endsection
