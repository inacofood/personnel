@extends('layouts.main')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2-bootstrap4.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

<style>
    .form-control {
        border: 1px solid #007bff; 
        border-radius: 0.25rem; 
        box-shadow: none; 
    }

    .select2-container--bootstrap4 .select2-selection--multiple,
    .select2-container--bootstrap4 .select2-selection--single {
        border: 1px solid #007bff;
        border-radius: 0.25rem; 
        box-shadow: none; 
        height: 100%;
    }

</style>

<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Create User Role</h5>
                <form action="{{ route('usersrole.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="createUser">User</label>
                        <select name="id_users" class="form-control select2" id="createUser" required style="width: 100%;">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="createRoles">Roles</label>
                        <select name="roles[]" class="form-control select2" id="createRoles" multiple required style="width: 100%;">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Data Users Roles</h5>
                <table id="dttable" class="table table-striped mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th>Nama</th>
                                <th>Role</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usersRoles as $group)
                                <tr>
                                    <td>{{ optional($group->user)->name }}</td>
                                    <td>{{ $group->roles }}</td>
                                    <td>
                                    <button class="btn btn-sm btn-primary edit-btn" 
                                            data-id="{{ $group->id_users_role }}" 
                                            data-user-id="{{ $group->id_users }}" 
                                            data-roles="{{ $group->roles }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('usersrole.destroy', $group->id_users) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user role?')">
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

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit User Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editUser">User</label>
                        <select name="id_users" class="form-control select2" id="editUser" required style="width: 100%;">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="rolesContainer">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script>
    $(document).ready(function() {
        $('#createUser, #createRoles').select2({
            theme: 'bootstrap4'
        });

        $('.edit-btn').on('click', function() {
            var id = $(this).data('id');
            var userId = $(this).data('user-id');

            $('#editUser').val(userId); 
            $('#rolesContainer').empty(); 

            $.ajax({
                url: '/usersrole/' + userId + '/edit',
                type: 'GET',
                success: function(response) {
                    if (response.roles) {
                        var selectHTML = '<label for="editRoles">Roles</label>' +
                            '<select id="editRoles" class="form-control select2" name="roles[]" multiple="multiple" required style="width: 100%;">';

                        @foreach($roles as $role)
                            selectHTML += '<option value="{{ $role->id }}"' + (response.roles.includes({{ $role->id }}) ? ' selected' : '') + '>{{ $role->department_name }}</option>';
                        @endforeach

                        selectHTML += '</select>';

                        $('#rolesContainer').append(selectHTML);

                        $('#editRoles').select2({
                            theme: 'bootstrap4',
                            dropdownParent: $('#editModal')
                        });

                        $('#edit-form').attr('action', '/usersrole/' + userId); 
                    } else {
                        alert('Role tidak ditemukan.');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Gagal mengambil data roles.');
                }
            });

            $('#editModal').modal('show');
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