<div class="modal fade" id="modalUpdateStok<?= $dataIner['idbarang']; ?>" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content shadow">

            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fa fa-edit"></i> Update Data Barang
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="proses/DataStok.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- ID BARANG -->
                    <input type="hidden" name="idbarang" value="<?= $dataIner['idbarang']; ?>">
                    <!-- Nomor Barang -->
                    <div class="form-group">
                        <label>Nomor Barang</label>
                        <input type="text" name="nomorbarang"
                            class="form-control"
                            value="<?= $dataIner['nomorbarang']; ?>"
                            required>
                    </div>

                    <!-- Nama Barang -->
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" name="namabarang"
                            class="form-control"
                            value="<?= $dataIner['namabarang']; ?>"
                            required>
                    </div>

                    <!-- Mesin -->
                    <div class="form-group">
                        <label>Mesin</label>
                        <input type="text" name="mesin"
                            class="form-control"
                            value="<?= $dataIner['mesin']; ?>"
                            required>
                    </div>

                    <!-- Nomor Rak -->
                    <div class="form-group">
                        <label>Nomor Rak</label>
                        <input type="text" name="norak"
                            class="form-control"
                            value="<?= $dataIner['norak']; ?>"
                            required>
                    </div>

                    <!-- Gambar -->
                    <div class="form-group">
                        <div class="form-group">
                            <label>Foto Barang Saat Ini</label><br>
                            <img src="storage/<?= $dataIner['gambar']; ?>"
                                class="img-thumbnail mb-2"
                                width="120">
                        </div>
                        <input type="file" name="gambar"
                            class="form-control" accept="image/*">
                    </div>

                    <!-- Jumlah / Stok (TIDAK BOLEH DIUBAH) -->
                    <div class=" form-group">
                        <label>Jumlah (Otomatis)</label>
                        <input type="number"
                            class="form-control"
                            value="<?= $dataIner['stok']; ?>"
                            readonly>
                        <small class="text-muted">
                            Stok dihitung otomatis dari barang masuk & keluar
                        </small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" name="btnUpdateStok" class="btn btn-warning">
                        <i class="fa fa-save"></i> Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>