<div class="modal fade" id="modalUpdateMasuk<?= $dataIner['idmasuk']; ?>" tabindex="-1" role="dialog"
    aria-labelledby="modalUpdateMasukLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content shadow">

            <!-- Modal Header -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalUpdateMasukLabel">
                    <i class="fa fa-edit"></i> Update Barang Masuk
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Form -->
            <form action="proses/DataMasuk.php" method="post">
                <div class="modal-body">

                    <!-- ID MASUK (HIDDEN) -->
                    <input type="hidden" name="idmasuk" value="<?= $dataIner['idmasuk']; ?>">

                    <!-- Nama Barang -->
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <select name="idbarang" class="form-control select-barang" required>
                            <?php
                            $stok = mysqli_query($konek, "SELECT idbarang, namabarang FROM tb_stok");
                            while ($s = mysqli_fetch_assoc($stok)) {
                                $selected = ($s['idbarang'] == $dataIner['idbarang']) ? 'selected' : '';
                            ?>
                                <option value="<?= $s['idbarang']; ?>" <?= $selected; ?>>
                                    <?= $s['namabarang']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Pengirim -->
                    <div class="form-group">
                        <label>Pengirim</label>
                        <input type="text" name="pengirim"
                            class="form-control"
                            value="<?= $dataIner['pengirim']; ?>"
                            required
                            disabled>
                    </div>

                    <!-- Jumlah -->
                    <div class="form-group">
                        <label>Jumlah Masuk</label>
                        <input type="number" name="jumlah"
                            class="form-control"
                            value="<?= $dataIner['jumlah']; ?>"
                            min="1"
                            required>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" name="btnUpdateIn" class="btn btn-warning">
                        <i class="fa fa-save"></i> Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>