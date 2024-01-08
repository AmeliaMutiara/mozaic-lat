
<td class="text-center">
    <a type="button" href="{{route('bank.edit',$model->bank_id)}}" class="btn btn-sm btn-primary btn-active-light-primary">
       Edit
    </a>
    <button type="button" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $model->bank_id }}" class="btn btn-sm btn-danger btn-active-light-danger">
        Hapus
    </button>
</td>

<div class="modal fade" tabindex="-1" id="modal_delete_{{ $model->bank_id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Hapus </h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="bi bi-x-lg"></span>
                </div>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin ingin menghapus?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tidak</button>
                <a type="button" href="{{route('bank.delete',$model->bank_id)}}" class="btn btn-danger">
                    Ya
                 </a>
            </div>
        </div>
    </div>
</div>