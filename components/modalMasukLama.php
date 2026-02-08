<div class="modal fade" id="myModalInLama" tabindex="-1" role="dialog" aria-labelledby="modalInLamaLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content shadow">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalInLamaLabel">
                    <i class="fa fa-box"></i> Input Sparepart Masuk (Barang Lama)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <!-- Form -->
            <form action="proses/DataMasuk.php" method="post">
                <div class="modal-body">

                    <!-- Nama Barang -->
                    <div class="form-group">
                        <label for="idbarang">Nama Barang</label>
                        <select name="idbarang" id="idbarang" class="form-control select-barang" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php
                            $stok = mysqli_query($konek, "SELECT idbarang, namabarang FROM tb_stok");
                            while ($row = mysqli_fetch_assoc($stok)) {
                            ?>
                                <option value="<?= $row['idbarang']; ?>">
                                    <?= $row['namabarang']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Jumlah -->
                    <div class="form-group">
                        <label for="jumlah">Jumlah Masuk</label>
                        <input type="number" name="jumlah" id="jumlah"
                            class="form-control"
                            placeholder="Masukkan jumlah barang"
                            min="1"
                            required>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" name="btnInLama" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>