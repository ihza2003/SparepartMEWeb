<div class="modal fade" id="modalUpdateKeluar<?= $dataIner['idkeluar']; ?>" tabindex="-1" role="dialog"
    aria-labelledby="modalUpdateKeluarLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content shadow">

            <!-- Modal Header -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalUpdateKeluarLabel">
                    <i class="fa fa-edit"></i> Update Barang Keluar
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Form -->
            <form action="proses/DataKeluar.php" method="post">
                <div class="modal-body">
                    <!-- ID KELUAR (HIDDEN) -->
                    <input type="hidden" name="idkeluar" value="<?= $dataIner['idkeluar']; ?>">
                    <!-- Nama Barang -->
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <select name="idbarang" class="form-control" required>
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

                    <!-- Penerima -->
                    <div class="form-group">
                        <label>Penerima</label>
                        <input type="text" name="penerima"
                            class="form-control"
                            value="<?= $dataIner['penerima']; ?>"
                            required>
                    </div>

                    <!-- Jumlah -->
                    <div class="form-group">
                        <label>Jumlah Keluar</label>
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
                    <button type="submit" name="btnUpdateOut" class="btn btn-warning">
                        <i class="fa fa-save"></i> Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>