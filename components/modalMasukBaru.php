<div class="modal fade" id="myModalInBaru" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalInLamaLabel">
                    <i class="fa fa-box"></i> Input Sparepart Masuk (Barang Baru)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <form action="proses/DataMasuk.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nomor Barang</label>
                        <input type="text" name="nomorbarang" class="form-control" placeholder="Masukkan nomor barang" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" name="namabarang" class="form-control" placeholder="Masukkan nama barang" required>
                    </div>

                    <div class="form-group">
                        <label>Mesin</label>
                        <input type="text" name="mesin" class="form-control" placeholder="Mesin" required>
                    </div>

                    <div class="form-group">
                        <label>Nomor Rak</label>
                        <input type="text" name="nomorrak" class="form-control" placeholder="Nomor Rak" required>
                    </div>

                    <div class="form-group">
                        <label>Pengirim</label>
                        <input type="text" name="pengirim" class="form-control" placeholder="Pengirim" required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" required>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" name="btnInBaru" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>