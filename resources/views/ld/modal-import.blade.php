<div class="modal fade" id="importModuleModal" tabindex="-1" role="dialog" aria-labelledby="importModuleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="importModuleModalLabel">Import New Module</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="importModuleForm" action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group text-left">
                        <a href="/download">Download Template</a>
                    </div>
                    <br>
                    <div class="form-group text-left">
                        <input type="file" name="excelFile" id="importFromExcel" accept=".xls,.xlsx" class="form-control">
                    </div>
                    <br>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary ml-2" id="submitBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .close {
        background-color: transparent; 
        padding: 0; 
        border-radius: 50%; 
        border: none; 
        width: 30px; 
        height: 30px; 
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


