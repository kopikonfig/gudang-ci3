<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Form Laporan
                </h4>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open('laporan/print'); ?>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="transaksi">Laporan</label>
                    <div class="col-md-9">
                        <select name="transaksi" id="transaksi" class="custom-select">
                            <option value="" selected disabled>Pilih Jenis Laporan</option>
                            <option value="barang_masuk" class="custom-control-input">Barang Masuk</option>
                            <option value="barang_keluar" class="custom-control-input">Barang Keluar</option>

                        </select>
                        <?= form_error('transaksi', '<span class="text-danger small">', '</span>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-lg-3 text-lg-right" for="tanggal">Tanggal</label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <input value="<?= set_value('tanggal'); ?>" name="tanggal" id="tanggal" type="text" class="form-control" placeholder="Periode Tanggal">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
                            </div>
                        </div>
                        <?= form_error('tanggal', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-lg-9 offset-lg-3">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-print"></i>
                            </span>
                            <span class="text">
                                Cetak
                            </span>
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>