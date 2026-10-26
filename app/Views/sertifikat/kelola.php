<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<style>
    .badge-theme-slate,
    span.badge.badge-theme-slate {
        background-color: #1e293b !important;
        background-image: none !important;
        color: #ffffff !important;
        box-shadow: 0 2px 5px rgba(30, 41, 59, 0.25) !important;
    }
    .btn-theme-slate {
        background-color: #1e293b !important;
        border: 1px solid #1e293b !important;
        color: #ffffff !important;
    }
    .btn-theme-slate:hover,
    .btn-theme-slate:focus,
    .btn-theme-slate:active {
        background-color: #0f172a !important;
        border-color: #0f172a !important;
        color: #ffffff !important;
    }
</style>
<div class="row mb-5">
    <div class="col-12">
        <div class="card card-body p-3">
            <div class="d-flex justify-content-between align-items-center px-2 mb-3 flex-wrap gap-2">
                <div>
                    <h6 class="mb-0 font-weight-bolder text-dark">Daftar Sertifikat Siswa Magang</h6>
                    <small class="text-xxs text-muted">Kelola pengunggahan berkas sertifikat resmi untuk peserta magang</small>
                </div>
                <div class="w-100 w-sm-auto col-12 col-md-5 mt-2 mt-sm-0">
                    <div class="input-group input-group-outline">
                        <input type="text" id="filsearch" class="form-control" placeholder="Cari Nama / NIM / Jurusan / Divisi...">
                    </div>
                </div>
            </div>

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-dismissible text-white fade show" role="alert" style="background-color: #1e293b; border-radius: 8px;">
                    <span class="alert-icon align-middle">
                        <span class="material-icons text-md">check_circle</span>
                    </span>
                    <span class="alert-text ms-2 text-xs font-weight-bold"><?= session()->getFlashdata('success') ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible text-white fade show" role="alert" style="border-radius: 8px;">
                    <span class="alert-icon align-middle">
                        <span class="material-icons text-md">error</span>
                    </span>
                    <span class="alert-text ms-2 text-xs font-weight-bold"><?= session()->getFlashdata('error') ?></span>
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
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jurusan & Instansi</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Divisi</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Persyaratan</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status Berkas</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php if (empty($siswa)) : ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-xs font-weight-bold text-secondary">
                                    Belum ada data siswa yang diterima atau menyelesaikan magang.
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($siswa as $s) : ?>
                                <?php
                                 $isSmk = $s->is_smk ?? false;
                                $hasCertFile = !empty($s->sertifikat_file);
                                $isSyaratLengkap = $s->is_syarat_lengkap ?? false;
                                ?>
                                <tr class="siswa-row">
                                    <td class="text-xs ps-3 font-weight-bold"><?= $no++; ?></td>
                                    <td>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm font-weight-bold student-name"><?= esc($s->nama) ?></h6>
                                            <p class="text-xs text-secondary mb-0 student-nim"><?= getIdentityLabel($s) ?>: <?= esc($s->nim) ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-xs font-weight-bold text-dark student-jurusan"><?= esc($s->jurusan) ?></span>
                                            <span class="text-xxs text-secondary"><?= esc($s->instansi ?? '-') ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-xs font-weight-bold student-divisi text-dark"><?= esc($s->tempat) ?></span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <div class="d-flex flex-wrap justify-content-center gap-1">
                                            <!-- Safety Check -->
                                            <span class="badge badge-sm <?= $s->is_safety_ready ? 'badge-theme-slate' : 'bg-gradient-danger' ?>" title="Safety & K3">
                                                <i class="fa <?= $s->is_safety_ready ? 'fa-check' : 'fa-times' ?> me-1"></i> Safety
                                            </span>
                                            <!-- Laporan Check -->
                                            <span class="badge badge-sm <?= ($isSmk || $s->is_laporan_approved) ? 'badge-theme-slate' : 'bg-gradient-danger' ?>" title="<?= $isSmk ? 'Laporan Tidak Wajib untuk Siswa SMK' : 'Laporan Akhir Disetujui' ?>">
                                                <i class="fa <?= ($isSmk || $s->is_laporan_approved) ? 'fa-check' : 'fa-times' ?> me-1"></i> Laporan <?= $isSmk ? '(SMK)' : '' ?>
                                            </span>
                                            <!-- Nilai Check -->
                                            <span class="badge badge-sm <?= $s->has_nilai ? 'badge-theme-slate' : 'bg-gradient-danger' ?>" title="Nilai Selesai Diinput">
                                                <i class="fa <?= $s->has_nilai ? 'fa-check' : 'fa-times' ?> me-1"></i> Nilai
                                            </span>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <?php if ($hasCertFile) : ?>
                                            <span class="badge badge-theme-slate text-xxs px-2 py-1 mb-1 d-inline-flex align-items-center">
                                                <i class="fa fa-check-circle me-1"></i> Sudah Diupload
                                            </span>
                                            <div>
                                                <a href="/assets/sertifikat/<?= $s->sertifikat_file ?>" target="_blank" class="text-xxs text-info font-weight-bold text-decoration-none">
                                                    <i class="fa fa-file-pdf me-1"></i> Lihat Berkas
                                                </a>
                                            </div>
                                        <?php elseif ($isSyaratLengkap) : ?>
                                            <span class="badge bg-gradient-warning text-xxs px-2 py-1 mb-1 d-inline-flex align-items-center">
                                                <i class="fa fa-clock me-1"></i> Menunggu Upload
                                            </span>
                                            <small class="text-xxs text-muted d-block">Syarat lengkap, siap diupload</small>
                                        <?php else : ?>
                                            <span class="badge bg-gradient-secondary text-xxs px-2 py-1 mb-1 d-inline-flex align-items-center">
                                                <i class="fa fa-hourglass-half me-1"></i> Belum Lengkap
                                            </span>
                                            <small class="text-xxs text-muted d-block">Selesaikan syarat magang</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-1 flex-wrap">
                                            <?php if ($hasCertFile) : ?>
                                                <!-- Tombol Unduh Sertifikat Resmi yang Sudah Diupload -->
                                                <a href="/sertifikat/download/<?= $s->id ?>" class="btn btn-theme-slate btn-sm mb-0 py-1 px-2 shadow-none d-inline-flex align-items-center gap-1" title="Unduh Sertifikat Resmi yang telah diunggah">
                                                    <i class="fa fa-download"></i> Unduh
                                                </a>

                                                <!-- Tombol Edit / Ganti Berkas Sertifikat (Ikon Edit di tengah) -->
                                                <button type="button" class="btn btn-outline-primary btn-sm mb-0 py-1 px-2 btn-upload-modal shadow-none" 
                                                        data-id="<?= $s->id ?>" 
                                                        data-nama="<?= esc($s->nama) ?>"
                                                        data-nim="<?= esc($s->nim) ?>"
                                                        data-hasfile="1"
                                                        title="Edit / Ganti Berkas Sertifikat">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <!-- Tombol Hapus Berkas -->
                                                <button type="button" class="btn btn-outline-danger btn-sm mb-0 py-1 px-2 shadow-none btn-delete-cert" data-id="<?= $s->id ?>" data-nama="<?= esc($s->nama) ?>" title="Hapus berkas sertifikat yang diunggah">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            <?php elseif ($isSyaratLengkap) : ?>
                                                <!-- Tombol Upload Sertifikat Manual (Aktif jika syarat lengkap) -->
                                                <button type="button" class="btn btn-primary btn-sm mb-0 py-1 px-2 btn-upload-modal shadow-none d-inline-flex align-items-center gap-1" 
                                                        data-id="<?= $s->id ?>" 
                                                        data-nama="<?= esc($s->nama) ?>"
                                                        data-nim="<?= esc($s->nim) ?>"
                                                        data-hasfile="0"
                                                        title="Upload Sertifikat Resmi Bernama">
                                                    <i class="fa fa-upload"></i> Upload
                                                </button>
                                            <?php else : ?>
                                                <!-- Tombol Upload Terkunci karena syarat belum lengkap -->
                                                <button type="button" class="btn btn-secondary btn-sm mb-0 py-1 px-2 shadow-none opacity-6" disabled style="cursor: not-allowed;" 
                                                        title="Sertifikat belum dapat diunggah: Persyaratan (Safety, Laporan, Nilai) belum lengkap">
                                                    <i class="fa fa-lock"></i> Upload
                                                </button>
                                            <?php endif; ?>
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

<!-- Modal Upload Sertifikat Resmi Bernama -->
<div class="modal fade" id="modalUploadSertifikat" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalUploadSertifikatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header border-bottom pb-3">
                <div>
                    <h6 class="modal-title font-weight-bolder text-dark mb-0" id="modalUploadSertifikatLabel">Upload Sertifikat Resmi Bernama</h6>
                    <small class="text-xxs text-muted">Unggah berkas sertifikat akhir yang sudah ditandatangani</small>
                </div>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/sertifikat/upload" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id_siswa" id="upload-siswa-id">
                <div class="modal-body py-3">
                    <div class="alert alert-info text-white text-xs py-2 px-3 mb-3" style="border-radius: 8px;">
                        <i class="fa fa-info-circle me-1"></i>
                        Siswa baru dapat mengunduh sertifikat ini melalui akun mereka setelah Anda mengunggah berkas sertifikat resmi berikut.
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-xs text-secondary font-weight-bold mb-1">Nama Mahasiswa / Siswa</label>
                        <input type="text" id="upload-siswa-nama" class="form-control bg-light" readonly style="padding: 8px 12px; font-weight: bold; border-radius: 6px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-xs text-secondary font-weight-bold mb-1">Pilih Berkas Sertifikat (Format: PDF)</label>
                        <input type="file" name="sertifikat_file" class="form-control" accept=".pdf" required style="border: 1px solid #d2d6da; padding: 8px 12px; border-radius: 6px;">
                        <small class="text-muted text-xxs mt-1 d-block">
                            <i class="fa fa-file-pdf text-danger me-1"></i> Pastikan file berformat <strong>PDF</strong> resmi yang telah memuat nama siswa dan tanda tangan/stempel.
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-top pt-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm mb-0" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm mb-0 shadow-sm d-inline-flex align-items-center gap-1">
                        <i class="fa fa-upload"></i> Unggah Sertifikat Resmi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Hapus Sertifikat Tersembunyi -->
<form id="form-delete-cert" action="/sertifikat/delete" method="post" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="id_siswa" id="delete-siswa-id">
</form>

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

    // Handle upload modal popup
    $('.btn-upload-modal').on('click', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const nim = $(this).data('nim');
        const hasfile = $(this).data('hasfile');

        $('#upload-siswa-id').val(id);
        $('#upload-siswa-nama').val(nama + ' (' + nim + ')');
        if (hasfile === 1) {
            $('#modalUploadSertifikatLabel').text('Ganti Berkas Sertifikat Resmi');
        } else {
            $('#modalUploadSertifikatLabel').text('Upload Sertifikat Resmi Bernama');
        }
        $('#modalUploadSertifikat').modal('show');
    });

    // Handle delete certificate
    $('.btn-delete-cert').on('click', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        if (confirm('Apakah Anda yakin ingin menghapus berkas sertifikat untuk ' + nama + '? Siswa tidak akan dapat mengunduh sertifikat hingga berkas baru diunggah.')) {
            $('#delete-siswa-id').val(id);
            $('#form-delete-cert').submit();
        }
    });
});
</script>
<?= $this->endSection(); ?>
