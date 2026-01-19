<div class="modal fade" id="myModalOut" tabindex="-1" role="dialog" aria-labelledby="modalInOutLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content shadow">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modaloutLabel">
                    <i class="fa fa-box"></i> Input Sparepart Keluar
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <!-- Form -->
            <form action="proses/DataKeluar.php" method="post">
                <div class="modal-body">

                    <!-- Nama Barang -->
                    <div class="form-group">
                        <label for="idbarang">Nama Barang</label>
                        <select name="idbarang" id="idbarang" class="form-control" required>
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

                    <!-- Penerima -->
                    <div class="form-group">
                        <label for="penerima">Penerima</label>
                        <input type="text" name="penerima" id="penerima"
                            class="form-control"
                            placeholder="Masukkan nama penerima"
                            required>
                    </div>

                    <!-- Jumlah Keluar  -->
                    <div class="form-group">
                        <label for="jumlah">Jumlah Keluar</label>
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
                    <button type="submit" name="btnOut" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>