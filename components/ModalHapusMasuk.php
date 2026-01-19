<div class="modal fade rounded-2" id="modalHapusMasuk<?= $dataIner['idmasuk'] ?>" tabindex="-1" role="dialog"
    aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">

            <!-- Header -->
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title" id="modalHapusLabel">
                    <i class="fa fa-trash mr-1"></i> Hapus Data
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    &times;
                </button>
            </div>

            <!-- Body -->
            <form action="proses/DataMasuk.php" method="post">
                <div class="modal-body text-center py-4">

                    <input type="hidden" name="idmasuk" value="<?= $dataIner['idmasuk'] ?>">

                    <i class="fa fa-exclamation-circle fa-3x text-danger mb-3"></i>

                    <p class="mb-1 font-weight-bold">
                        Apakah Anda yakin?
                    </p>
                    <small class="text-muted">
                        Ingin Menghapus Data Ini.
                    </small>

                </div>

                <!-- Footer -->
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button"
                        class="btn btn-light btn-sm px-4 rounded"
                        data-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                        name="btnHapusIn"
                        class="btn btn-danger btn-sm px-4 rounded">
                        <i class="fa fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>