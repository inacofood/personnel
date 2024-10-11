@extends('layouts.main')

@section('content')
<section class="section" id="home">
    <div class="container text-center">
        <h2 style="font-size: 24px;" class="text-center">List e-Module</h2>
        <p class="has-line"></p>
        <div class="row mt-3">
            <div class="col-md-12 mt-3">
                <div class="float-right">
                    <button class="btn btn-primary ml-1" data-toggle="modal" data-target="#addModuleModal">Add New Module</button>
                    <a href="{{ route('export') }}" class="btn btn-primary ml-1">Export to Excel</a>
                    <button class="btn btn-primary ml-1" data-toggle="modal" data-target="#importModuleModal">Import New Module</button>
                </div>
            </div>
        </div>

        <br>
        <div class="container">
            <table id="dttable" class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="display: none;">Id</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Sub-category</th>
                        <th class="text-center">Courses</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Link</th>
                        <th class="text-center">Video</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listItems as $list)
                    <tr data-id="{{ $list->id }}">
                        <td style="display: none;">{{ $list->id }}</td>
                        <td>{{ $list->category }}</td>
                        <td>{{ $list->sub_cat }}</td>
                        <td>{{ $list->title }}</td>
                        <td>{{ $list->status }}</td>
                        <td><a href="{{ $list->link }}" target="_blank">Click Here</a></td>
                        <td>{{ $list->video }}</td>
                        <td>
                        <button class="btn btn-sm btn-primary btn-edit-module" 
                            data-id="{{ $list->id }}" 
                            data-title="{{ $list->title }}" 
                            data-category="{{ $list->category }}"
                            data-subcategory="{{ $list->sub_cat }}" 
                            data-link="{{ $list->link }}" 
                            data-video="{{ $list->video }}" 
                            data-status="{{ $list->status }}" 
                            data-toggle="modal" data-target="#editModuleModal">
                            <i class="bi bi-pencil"></i>
                        </button>

                            <form action="{{ route('destroy', $list->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this e-module?')">
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
</section>

 <!-- Modal for Adding New Module -->
<div class="modal fade" id="addModuleModal" tabindex="-1" role="dialog" aria-labelledby="addModuleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModuleModalLabel">Add New Module</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addModuleForm" action="{{ route('add') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="addTitle">Courses</label>
                        <input type="text" class="form-control" id="addTitle" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="addCategory">Category</label>
                        <select id="addCategory" name="category" class="form-control" required>
                            <option value="" selected disabled>Choose</option>
                            <option value="Basic Skills">Basic Skills</option>
                            <option value="Soft Skills">Soft Skills</option>
                            <option value="Technical Skills">Technical Skills</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="addSubcategory">Sub-category</label>
                        <input type="text" class="form-control" id="addSubcategory" name="subcategory" required>
                    </div>
                    <div class="form-group">
                        <label for="addLink">Link</label>
                        <input type="text" class="form-control" id="addLink" name="link" required>
                    </div>
                    <div class="form-group">
                        <label for="addVideo">Video</label>
                        <input type="number" class="form-control" id="addVideo" name="video" required>
                    </div>
                    <div class="form-group">
                        <label for="addStatus">Status</label>
                        <select id="addStatus" name="status" class="form-control" required>
                            <option value="" selected disabled>Choose</option>
                            <option value="Review">Review</option>
                            <option value="Published">Published</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Module</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('ld.modal-import')

<!-- Modal for Editing Module -->
<div class="modal fade" id="editModuleModal" tabindex="-1" role="dialog" aria-labelledby="editModuleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModuleModalLabel">Edit Module</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editModuleForm" action="{{ route('update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editItemId" name="id">
                    <div class="form-group">
                        <label for="editTitle">Courses</label>
                        <input type="text" class="form-control" id="editTitle" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="editCategory">Category</label>
                        <select id="editCategory" name="category" class="form-control" required>
                            <option value="" selected disabled>Choose</option>
                            <option value="Basic Skills">Basic Skills</option>
                            <option value="Soft Skills">Soft Skills</option>
                            <option value="Technical Skills">Technical Skills</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editSubcategory">Sub-category</label>
                        <input type="text" class="form-control" id="editSubcategory" name="subcategory" required>
                    </div>
                    <div class="form-group">
                        <label for="editLink">Link</label>
                        <input type="text" class="form-control" id="editLink" name="link" required>
                    </div>
                    <div class="form-group">
                        <label for="editVideo">Video</label>
                        <input type="number" class="form-control" id="editVideo" name="video" required>
                    </div>
                    <div class="form-group">
                        <label for="editStatus">Status</label>
                        <select id="editStatus" name="status" class="form-control" required>
                            <option value="" selected disabled>Choose</option>
                            <option value="Review">Review</option>
                            <option value="Published">Published</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Module</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script>
  $(document).ready(function() {
    $(document).on('click', '.btn-edit-module', function() {
        const id = $(this).data('id');
        const title = $(this).data('title');
        const category = $(this).data('category');
        const subcategory = $(this).data('subcategory');
        const link = $(this).data('link');
        const video = $(this).data('video');
        const status = $(this).data('status');
        console.log(category);

        $('#editItemId').val(id);
        $('#editTitle').val(title);
        $('#editCategory').val(category).trigger('change');
        $('#editSubcategory').val(subcategory);
        $('#editLink').val(link);
        $('#editVideo').val(video);
        $('#editStatus').val(status);

    });
});


</script>
@endsection
