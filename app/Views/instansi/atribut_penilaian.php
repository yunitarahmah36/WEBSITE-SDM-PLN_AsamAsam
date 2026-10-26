<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<style>
    .badge-status-draft {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
        border-radius: 6px;
    }
    .badge-status-sent {
        background-color: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
        border-radius: 6px;
    }
    .dropzone-file {
        border: 2px dashed #bce1f5;
        border-radius: 12px;
        padding: 24px 20px;
        background: #fcfdfe;
        transition: all 0.2s ease;
    }
    .dropzone-file:hover {
        border-color: #1A73E8;
        background: #f0f7ff;
    }
</style>

<div class="row">
    <!-- Header Instansi & Status Banner -->
    <div class="col-12 mb-3">
        <div class="card card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="mb-1 font-weight-bolder">Pengelolaan Format & Atribut Penilaian Peserta PKL</h5>
                    <p class="text-sm text-secondary mb-0">
                        Instansi: <strong class="text-dark"><?= esc($instansi->nama_instansi) ?></strong>
                        <?php if (!empty($instansi->jenis_instansi)) : ?>
                            &bull; <span class="badge badge-sm bg-gradient-secondary"><?= esc($instansi->jenis_instansi) ?></span>
                        <?php endif; ?>
                    </p>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <?php if ($konfig->status === 'terkirim') : ?>
                        <span class="badge-status-sent">
                            <i class="fa fa-check-circle me-1"></i> Terkirim ke Pembimbing Lapangan
                        </span>
                        <small class="text-muted text-xs ms-1">
                            (<?= date('d/m/Y H:i', strtotime($konfig->tgl_kirim)) ?>)
                        </small>
                    <?php else : ?>
                        <span class="badge-status-draft">
                            <i class="fa fa-pencil-alt me-1"></i> Draf (Belum Dikirimkan)
                        </span>
                    <?php endif; ?>

                    <?php if (in_groups('admin') && !empty($allInstansi)) : ?>
                        <div class="ms-2">
                            <select class="form-select form-select-sm" onchange="location.href='?id_instansi=' + this.value">
                                <?php foreach ($allInstansi as $ins) : ?>
                                    <option value="<?= $ins->id ?>" <?= $ins->id == $instansi->id ? 'selected' : '' ?>>
                                        Pilih Instansi: <?= esc($ins->nama_instansi) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 1. BAGIAN UTAMA: UNGGAH LEMBAR / FILE FORMAT PENILAIAN INSTANSI -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header pb-2 bg-gradient-light border-bottom">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h6 class="font-weight-bolder text-dark mb-0">
                            <i class="fa fa-file-upload text-info me-2"></i>
                            File Lembar / Format Penilaian Resmi dari Instansi
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                            Instansi atau Admin dapat cukup mengunggah file lembar penilaian (PDF, Word, Excel, atau scan dokumen). Pembimbing lapangan akan membuka lembar ini dan menginput nilai sesuai atribut pada lembar tersebut.
                        </p>
                    </div>
                    <?php if (!empty($konfig->file_format)) : ?>
                        <span class="badge bg-success">
                            <i class="fa fa-check-circle me-1"></i> File Sudah Tersedia
                        </span>
                    <?php else : ?>
                        <span class="badge bg-warning text-dark">
                            <i class="fa fa-exclamation-circle me-1"></i> File Belum Diunggah
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($konfig->file_format)) : ?>
                    <!-- TAMPILAN FILE SUDAH ADA -->
                    <div class="p-3 bg-light rounded border d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-white rounded border shadow-xs text-center" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-file-pdf text-danger" style="font-size: 26px;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-sm font-weight-bold text-dark"><?= esc($konfig->nama_file_asli ?: $konfig->file_format) ?></h6>
                                <span class="text-xxs text-muted">Tersimpan di sistem &bull; Siap diunduh oleh pembimbing lapangan</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <a href="<?= base_url('assets/format_penilaian/' . $konfig->file_format) ?>" target="_blank" class="btn btn-sm bg-gradient-success mb-0 shadow-sm">
                                <i class="fa fa-eye me-1"></i> Lihat / Unduh File
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-info mb-0" onclick="$('#formGantiFileContainer').toggle(200)">
                                <i class="fa fa-sync-alt me-1"></i> Ganti File
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger mb-0" id="btnHapusFile">
                                <i class="fa fa-trash me-1"></i> Hapus File
                            </button>
                        </div>
                    </div>

                    <!-- FORM GANTI FILE (HIDDEN BY DEFAULT) -->
                    <div id="formGantiFileContainer" style="display: none;" class="mb-3 p-3 bg-gray-100 rounded border">
                        <form id="formUploadFileGanti" enctype="multipart/form-data">
                            <input type="hidden" name="id_instansi" value="<?= $instansi->id ?>">
                            <label class="form-label font-weight-bold text-xs text-dark mb-1">Pilih File Baru (PDF, DOC, DOCX, XLS, XLSX, JPG, PNG - Maks 15MB):</label>
                            <div class="d-flex flex-wrap gap-2">
                                <input type="file" name="file_format" class="form-control bg-white" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" required style="max-width: 450px;">
                                <button type="submit" class="btn btn-sm bg-gradient-info mb-0">
                                    <i class="fa fa-upload me-1"></i> Simpan File Baru
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary mb-0" onclick="$('#formGantiFileContainer').hide(200)">Batal</button>
                            </div>
                        </form>
                    </div>

                    <!-- STATUS PENGIRIMAN & TOMBOL AKSI -->
                    <div class="p-3 rounded border <?= $konfig->status === 'terkirim' ? 'bg-gradient-light border-success' : 'bg-light border-info' ?>">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <?php if ($konfig->status === 'terkirim') : ?>
                                    <h6 class="text-sm font-weight-bold text-success mb-0">
                                        <i class="fa fa-check-circle me-1"></i> Format Penilaian Telah Dikirimkan ke Pembimbing Lapangan
                                    </h6>
                                    <small class="text-xs text-secondary">
                                        Terkirim pada: <strong><?= date('d/m/Y H:i', strtotime($konfig->tgl_kirim)) ?></strong>. Pembimbing lapangan sudah dapat memberikan nilai pada peserta sesuai format ini.
                                    </small>
                                <?php else : ?>
                                    <h6 class="text-sm font-weight-bold text-dark mb-0">
                                        <i class="fa fa-pencil-alt text-warning me-1"></i> Status: Draf (Belum Dikirim ke Pembimbing)
                                    </h6>
                                    <small class="text-xs text-secondary">
                                        File lembar format penilaian sudah siap. Silakan klik tombol <strong>Kirim Format Penilaian</strong> di samping agar pembimbing dapat mulai menilai.
                                    </small>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <?php if ($konfig->status === 'terkirim') : ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-0 shadow-sm" id="btnBatalKirim">
                                        <i class="fa fa-undo me-1"></i> Ubah Kembali ke Draf
                                    </button>
                                <?php else : ?>
                                    <button type="button" class="btn btn-sm bg-gradient-info mb-0 shadow-sm" id="btnKirimAtribut">
                                        <i class="fa fa-paper-plane me-1"></i> Kirim Format Penilaian ke Pembimbing
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php else : ?>
                    <!-- FORM UNGGAH FILE BARU -->
                    <form id="formUploadFile" enctype="multipart/form-data" class="dropzone-file">
                        <input type="hidden" name="id_instansi" value="<?= $instansi->id ?>">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-7 mb-2 mb-md-0">
                                <label class="form-label font-weight-bold text-xs text-uppercase text-secondary mb-1">
                                    <i class="fa fa-upload me-1"></i> Pilih Lembar Format Penilaian (PDF, Word, Excel, Scan Gambar)
                                </label>
                                <div class="input-group">
                                    <input type="file" name="file_format" id="file_format_input" class="form-control border p-2 text-sm" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" required>
                                </div>
                                <small class="text-muted text-xxs">Maksimal ukuran file: 15MB.</small>
                            </div>
                            <div class="col-12 col-md-5 d-flex gap-2 align-items-end mt-2 mt-md-0">
                                <button type="submit" data-kirim="0" class="btn btn-sm bg-gradient-dark mb-0 shadow-sm flex-fill btn-do-upload">
                                    <i class="fa fa-upload me-1"></i> Unggah Saja
                                </button>
                                <button type="submit" data-kirim="1" class="btn btn-sm bg-gradient-info mb-0 shadow-sm flex-fill btn-do-upload">
                                    <i class="fa fa-paper-plane me-1"></i> Unggah & Langsung Kirim
                                </button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script src="/assets/js/plugins/sweetalert.min.js"></script>
<script>
$(document).ready(function() {
    // 1. Upload File Format Baru
    $('#formUploadFile').submit(function(e) {
        e.preventDefault();
        var clickedBtn = $(document.activeElement);
        var langsungKirim = clickedBtn.data('kirim') || '0';

        var formData = new FormData(this);
        formData.append('langsung_kirim', langsungKirim);

        Swal.fire({
            title: 'Mengunggah File...',
            text: 'Harap tunggu sebentar.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: '/instansi/atribut-penilaian/upload-file',
            type: 'POST',
            data: formData,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan sistem saat mengunggah file.', 'error');
            }
        });
    });

    // 2. Ganti File Format
    $('#formUploadFileGanti').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        Swal.fire({
            title: 'Mengunggah File Baru...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: '/instansi/atribut-penilaian/upload-file',
            type: 'POST',
            data: formData,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan saat mengganti file.', 'error');
            }
        });
    });

    // 3. Hapus File Format
    $('#btnHapusFile').click(function() {
        Swal.fire({
            title: 'Hapus File Format Penilaian?',
            text: 'File yang dihapus tidak dapat dipulihkan kembali.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/instansi/atribut-penilaian/hapus-file',
                    type: 'POST',
                    data: { id_instansi: '<?= $instansi->id ?>' },
                    dataType: 'JSON',
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: res.message,
                                showConfirmButton: false,
                                timer: 1200
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    }
                });
            }
        });
    });

    // 4. Kirim Format Penilaian ke Pembimbing
    $('#btnKirimAtribut').click(function() {
        Swal.fire({
            title: 'Kirim Format Penilaian?',
            text: 'Format penilaian ini akan dikirimkan kepada pembimbing lapangan di PLN untuk digunakan sebagai format penilaian peserta dari instansi Anda.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1A73E8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Kirim Sekarang',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/instansi/atribut-penilaian/kirim',
                    type: 'POST',
                    data: { id_instansi: '<?= $instansi->id ?>' },
                    dataType: 'JSON',
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terkirim!',
                                text: res.message,
                                showConfirmButton: true
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Perhatian', res.message, 'warning');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan saat mengirim format penilaian.', 'error');
                    }
                });
            }
        });
    });

    // 5. Batal Kirim (Kembali ke Draft)
    $('#btnBatalKirim').click(function() {
        Swal.fire({
            title: 'Kembalikan ke Draf?',
            text: 'Status pengiriman akan diubah kembali menjadi draf.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6c757d',
            cancelButtonColor: '#344767',
            confirmButtonText: 'Ya, Kembalikan Draf',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/instansi/atribut-penilaian/batal-kirim',
                    type: 'POST',
                    data: { id_instansi: '<?= $instansi->id ?>' },
                    dataType: 'JSON',
                    success: function(res) {
                        if (res.success) {
                            location.reload();
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection(); ?>
