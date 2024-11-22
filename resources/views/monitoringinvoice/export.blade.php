<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="modal">Ekspor Data Monitoring Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('exportexcel') }}" method="post" id="exportFormModal">
                    @csrf
                    <div class="form-group">
                        <label for="">Dari Tanggal</label>
                        <input type="date" class="form-control" name="start_date" placeholder="Pilih Tanggal">
                    </div>
                    <div class="form-group">
                        <label for="">Sampai</label>
                        <input type="date" class="form-control" name="end_date" placeholder="Pilih Tanggal">
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary"><i class="fa fa-save"></i> Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

