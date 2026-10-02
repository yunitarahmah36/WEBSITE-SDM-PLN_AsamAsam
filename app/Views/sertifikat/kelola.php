<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row mb-5">
    <div class="col-12">
        <div class="card card-body p-3">
            <div class="d-flex justify-content-between align-items-center px-2 mb-3">
                <h6>Kelola Sertifikat Siswa</h6>
                <div class="w-100 w-sm-auto col-12 col-md-5 mt-2 mt-sm-0">
                    <div class="input-group input-group-outline">
                        <input type="text" id="filsearch" class="form-control" placeholder="Cari Nama / NIM / Jurusan...">
                    </div>
                </div>
            </div>

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible text-white fade show" role="alert">
                    <span class="alert-icon align-middle">
                        <span class="material-icons text-md">check_circle</span>
                    </span>
                    <span class="alert-text ms-2"><?= session()->getFlashdata('success') ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible text-white fade show" role="alert">
                    <span class="alert-icon align-middle">
                        <span class="material-icons text-md">error</span>
                    </span>
                    <span class="alert-text ms-2"><?= session()->getFlashdata('error') ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-items-center mb-0" id="tb-sertifikat">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Siswa</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jurusan</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Divisi</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Persyaratan</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Mode Sertifikat</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Berkas Manual</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php if (empty($siswa)) : ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-xs font-weight-bold text-secondary">
                                    Belum ada data siswa yang diterima atau menyelesaikan magang.
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($siswa as $s) : ?>
                                <?php
                                $isSmk = false;
                                $instansiLower = strtolower($s->instansi ?? '');
                                $kelasLower = strtolower($s->kelas ?? '');
                                if (strpos($instansiLower, 'smk') !== false || strpos($instansiLower, 'sekolah') !== false || in_array($kelasLower, ['x', 'xi', 'xii', '10', '11', '12'])) {
                                    $isSmk = true;
                                }
                                ?>
                                <tr class="siswa-row">
                                    <td class="text-xs ps-3 font-weight-bold"><?= $no++; ?></td>
                                    <td>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm student-name"><?= esc($s->nama) ?></h6>
                                            <p class="text-xs text-secondary mb-0 student-nim"><?= getIdentityLabel($s) ?>: <?= esc($s->nim) ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-xs font-weight-bold student-jurusan"><?= esc($s->jurusan) ?></span>
                                    </td>
                                    <td>
                                        <span class="text-xs font-weight-bold student-divisi"><?= esc($s->tempat) ?></span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <!-- Safety Check -->
                                        <span class="badge badge-sm <?= $s->is_safety_ready ? 'bg-gradient-success' : 'bg-gradient-danger' ?> mb-1" title="Safety & K3">
                                            <i class="fa <?= $s->is_safety_ready ? 'fa-check' : 'fa-times' ?> me-1"></i> Safety
                                        </span>
                                        <!-- Laporan Check -->
                                        <span class="badge badge-sm <?= ($isSmk || $s->is_laporan_approved) ? 'bg-gradient-success' : 'bg-gradient-danger' ?> mb-1" title="<?= $isSmk ? 'Laporan Tidak Wajib untuk Siswa SMK' : 'Laporan Akhir Approved' ?>">
                                            <i class="fa <?= ($isSmk || $s->is_laporan_approved) ? 'fa-check' : 'fa-times' ?> me-1"></i> Laporan <?= $isSmk ? '(Tdk Wajib)' : '' ?>
                                        </span>
                                        <!-- Nilai Check -->
                                        <span class="badge badge-sm <?= $s->has_nilai ? 'bg-gradient-success' : 'bg-gradient-danger' ?> mb-1" title="Nilai Selesai di-input">
                                            <i class="fa <?= $s->has_nilai ? 'fa-check' : 'fa-times' ?> me-1"></i> Nilai
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="input-group input-group-static justify-content-center">
                                            <select class="form-control text-xs w-75 select-mode" data-id="<?= $s->id ?>" style="padding: 4px 8px;">
                                                <option value="otomatis" <?= $s->sertifikat_mode === 'otomatis' ? 'selected' : '' ?>>Otomatis (Sistem)</option>
                                                <option value="manual" <?= $s->sertifikat_mode === 'manual' ? 'selected' : '' ?>>Manual (Upload)</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="manual-file-wrapper" id="file-wrapper-<?= $s->id ?>">
                                            <?php if (!empty($s->sertifikat_file)) : ?>
                                                <a href="/assets/sertifikat/<?= $s->sertifikat_file ?>" target="_blank" class="badge badge-sm bg-gradient-info d-inline-flex align-items-center">
                                                    <i class="fa fa-file-pdf me-1"></i> Lihat File
                                                </a>
                                            <?php else : ?>
                                                <span class="text-xxs text-secondary font-weight-bold">Belum Diupload</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-outline-primary btn-sm mb-0 py-1 px-2 btn-upload-modal" 
                                                    data-id="<?= $s->id ?>" 
                                                    data-nama="<?= esc($s->nama) ?>"
                                                    title="Upload Sertifikat Manual">
                                                <i class="fa fa-upload"></i> Upload
                                            </button>
                                            <a href="/sertifikat/download/<?= $s->id ?>" class="btn btn-info btn-sm mb-0 py-1 px-2" title="Unduh Sertifikat Aktif">
                                                <i class="fa fa-download"></i> Unduh
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Sertifikat Manual -->
<div class="modal fade" id="modalUploadSertifikat" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalUploadSertifikatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="modalUploadSertifikatLabel">Upload Sertifikat Manual</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/sertifikat/upload" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id_siswa" id="upload-siswa-id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-xs text-secondary mb-1">Nama Mahasiswa</label>
                        <input type="text" id="upload-siswa-nama" class="form-control bg-light" readonly style="padding: 6px 12px; font-weight: bold;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs text-secondary mb-1">Pilih File Sertifikat (Format: PDF)</label>
                        <input type="file" name="sertifikat_file" class="form-control" accept=".pdf" required style="border: 1px solid #d2d6da; padding: 6px 12px;">
                        <small class="text-muted text-xxs mt-1 d-block">Catatan: Mengupload sertifikat akan otomatis mengubah mode sertifikat siswa menjadi "Manual".</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Unggah Sertifikat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script>
$(document).ready(function() {
    $('#filsearch').on('keyup change input', function() {
        $(this).val() == "" ? $(this).parent().removeClass('is-filled') : $(this).parent().addClass('is-filled');
        const value = $(this).val().toLowerCase();
        $('.siswa-row').filter(function() {
            const name = $(this).find('.student-name').text().toLowerCase();
            const nim = $(this).find('.student-nim').text().toLowerCase();
            const divisi = $(this).find('.student-divisi').text().toLowerCase();
            const jurusan = $(this).find('.student-jurusan').text().toLowerCase();
            $(this).toggle(name.indexOf(value) > -1 || nim.indexOf(value) > -1 || divisi.indexOf(value) > -1 || jurusan.indexOf(value) > -1);
        });
    });

    // Handle mode selection change
    $('.select-mode').on('change', function() {
        const select = $(this);
        const idSiswa = select.data('id');
        const mode = select.val();

        // Add a visual loading state or disable select
        select.prop('disabled', true);

        $.ajax({
            url: '/sertifikat/update-mode',
            type: 'POST',
            data: {
                id_siswa: idSiswa,
                mode: mode
            },
            dataType: 'json',
            success: function(response) {
                select.prop('disabled', false);
                if (response.success) {
                    // Success feedback
                    const alert = $('<div class="position-fixed bottom-3 end-3 alert alert-success text-white py-2 px-3 fade show" style="z-index: 9999; border-radius: 8px;">' +
                        '<i class="fa fa-check-circle me-2"></i>' + response.message + '</div>');
                    $('body').append(alert);
                    setTimeout(() => alert.alert('close'), 3000);
                } else {
                    alert('Gagal: ' + response.message);
                    // Revert select
                    select.val(mode === 'otomatis' ? 'manual' : 'otomatis');
                }
            },
            error: function() {
                select.prop('disabled', false);
                alert('Terjadi kesalahan koneksi server.');
                select.val(mode === 'otomatis' ? 'manual' : 'otomatis');
            }
        });
    });

    // Handle upload modal popup
    $('.btn-upload-modal').on('click', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');

        $('#upload-siswa-id').val(id);
        $('#upload-siswa-nama').val(nama);
        $('#modalUploadSertifikat').modal('show');
    });
});
</script>
<?= $this->endSection(); ?>
