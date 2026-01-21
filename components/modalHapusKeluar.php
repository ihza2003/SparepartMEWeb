<div class="modal fade" id="modalHapuskeluar<?= $dataIner['idkeluar'] ?>" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="proses/DataKeluar.php" method="post">
                <div class="modal-body text-center px-4 py-4">
                    <input type="hidden" name="idkeluar" value="<?= $dataIner['idkeluar'] ?>">
                    <!-- Icon -->
                    <div class="mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger text-white"
                            style="width:60px;height:60px;">
                            <i class="fa fa-trash fa-lg"></i>
                        </span>
                    </div>

                    <!-- Text -->
                    <h6 class="font-weight-bold mb-1">
                        Hapus Aktivitas Data Barang Keluar?
                    </h6>
                    <p class="text-muted small mb-4">
                        Data yang dihapus tidak dapat dikembalikan.
                    </p>

                    <!-- Action -->
                    <div class="d-flex justify-content-center ">
                        <button type="button"
                            class="btn btn-light btn-sm px-4 rounded mr-2"
                            data-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit"
                            name="btnHapusOut"
                            class="btn btn-danger btn-sm px-4 rounded">
                            Hapus
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>