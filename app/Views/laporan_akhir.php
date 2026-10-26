<?= $this->extend('main'); ?>

<?= $this->section('topsc'); ?>
<style>
    .card-laporan-main {
        border: 1px solid #d2edd7 !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 20px rgba(15, 81, 50, 0.05) !important;
    }
    .card-laporan-info {
        background: linear-gradient(145deg, #eaf6ee, #dff2e5) !important;
        border: 1px solid #bce6ca !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 20px rgba(15, 81, 50, 0.06) !important;
    }
    .file-preview-card {
        background: #f0fdf4 !important;
        border: 1.5px solid #bbf7d0 !important;
        border-radius: 12px !important;
    }
    .file-icon-box {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #15803d, #16a34a);
        color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(21, 128, 61, 0.25);
    }
    .btn-lihat-file {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #15803d !important;
        border: 1.5px solid #86efac !important;
        background-color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        letter-spacing: 0.5px;
        border-radius: 8px !important;
        padding: 7px 16px !important;
        transition: all 0.2s ease;
    }
    .btn-lihat-file:hover {
        background-color: #dcfce7 !important;
        color: #166534 !important;
        border-color: #4ade80 !important;
    }
    .btn-unggah-laporan {
        background: linear-gradient(135deg, #15803d, #16a34a) !important;
        color: #fff !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: 700 !important;
        padding: 8px 18px !important;
        box-shadow: 0 4px 10px rgba(21, 128, 61, 0.25) !important;
        transition: all 0.2s ease;
    }
    .btn-unggah-laporan:hover {
        background: linear-gradient(135deg, #166534, #15803d) !important;
        color: #fff !important;
        transform: translateY(-1px);
    }
    .alert-green-soft {
        background-color: #ecfdf5 !important;
        border: 1px solid #a7f3d0 !important;
        color: #065f46 !important;
        border-radius: 12px !important;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.08) !important;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<?php 
$isSmk = false;
if (isset($siswa)) {
    $instansiLower = strtolower($siswa->instansi ?? '');
    $kelasLower = strtolower($siswa->kelas ?? '');
    if (strpos($instansiLower, 'smk') !== false || strpos($instansiLower, 'sekolah') !== false || in_array($kelasLower, ['x', 'xi', 'xii', '10', '11', '12'])) {
        $isSmk = true;
    }
}
?>
<div class="row mb-5">
    <div class="col-lg-8">
        <div class="card card-body p-4 mb-4 card-laporan-main">
            <div class="mb-4">
                <h4 class="m-0 p-0 font-weight-bold" style="color: #0f4423;">
                    <span class="material-icons align-middle me-1" style="color: #15803d; font-size: 1.6rem;">assignment_turned_in</span>
                    Laporan Akhir Magang
                </h4>
                <p class="text-sm text-secondary mt-1">Silahkan unggah laporan akhir kegiatan magang Anda di sini.</p>
            </div>

            <?php if (!$application) : ?>
                <div class="alert alert-warning text-white text-sm">
                    <i class="fa fa-exclamation-triangle me-2"></i>
                    Anda belum memiliki pendaftaran tempat magang yang aktif. Silahkan daftar terlebih dahulu di menu <b>Tempat Magang</b>.
                </div>
            <?php elseif ($application->status == 'pending') : ?>
                <div class="alert alert-green-soft text-sm">
                    <i class="fa fa-info-circle me-2" style="color: #10b981;"></i>
                    Pendaftaran Anda masih berstatus <b>Pending</b>. Anda baru dapat mengunggah laporan akhir setelah pendaftaran <b>Disetujui (Accepted)</b>.
                </div>
            <?php else : ?>
                <div class="row">
                    <div class="col-12">
                        <?php if ($siswa->laporan) : ?>
                            <div class="card file-preview-card mb-4">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="file-icon-box me-3">
                                            <i class="material-icons" style="font-size: 24px;">description</i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-sm font-weight-bold" style="color: #064e28;">Laporan Tersimpan</h6>
                                            <p class="text-xs text-secondary mb-0">File: <?= $siswa->laporan ?></p>
                                        </div>
                                        <div>
                                            <a href="<?= base_url('assets/laporan/' . $siswa->laporan) ?>" target="_blank" class="btn btn-sm btn-lihat-file mb-0">
                                                <i class="material-icons" style="font-size: 14px;">visibility</i> LIHAT FILE
                                            </a>
                                        </div>
                                    </div>
                                    <hr class="horizontal dark my-3" style="background-image: linear-gradient(90deg, transparent, rgba(13, 74, 41, 0.2), transparent) !important; height: 1px !important;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-xs font-weight-bold" style="color: #064e28;">Status: 
                                            <span class="badge" style="background-color: <?= $siswa->is_laporan_approved ? '#d1fae5' : '#fef3c7' ?>; color: <?= $siswa->is_laporan_approved ? '#065f46' : '#92400e' ?>; font-weight: 700; border-radius: 20px; padding: 5px 12px; letter-spacing: 0.5px;">
                                                <?= $siswa->is_laporan_approved ? 'DISETUJUI' : 'MENUNGGU APPROVAL' ?>
                                            </span>
                                        </span>
                                        <?php if (!$siswa->is_laporan_approved) : ?>
                                            <button class="btn btn-link text-xs mb-0 p-0" id="btnChangeReport" style="color: #15803d; font-weight: 600;"><i class="fa fa-edit me-1"></i> Ganti Laporan</button>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if ($application->status != 'selesai') : ?>
                            <div id="uploadSection" class="<?= $siswa->laporan ? 'd-none' : '' ?>">
                                <form id="formUploadLaporan" enctype="multipart/form-data">
                                    <div class="w-100 mb-4 p-3 text-start" style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; color: #065f46;">
                                        <p class="mb-1 font-weight-bold" style="color: #044e31;">Instruksi Pengunggahan:</p>
                                        <ul class="text-xs mb-0 ps-3" style="color: #065f46;">
                                            <li>File harus dalam format <b>PDF</b>, <b>DOC</b>, atau <b>DOCX</b>.</li>
                                            <li>Ukuran file maksimal <b>5MB</b>.</li>
                                            <li>Setelah mengunggah, status magang Anda akan berubah menjadi <b>Selesai</b>.</li>
                                            <?php if ($isSmk) : ?>
                                                <li><b style="color: #15803d;">Untuk Siswa SMK:</b> Mengunggah laporan magang bersifat opsional. Anda dapat menyelesaikan program magang tanpa laporan menggunakan tombol di bawah.</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>

                                    <div class="input-group input-group-outline mb-4">
                                        <input type="file" name="laporan" id="file_laporan" class="form-control" accept=".pdf,.doc,.docx" required>
                                    </div>

                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <?php if ($isSmk) : ?>
                                            <button type="button" id="btnFinishWithoutReport" class="btn btn-outline-success btn-sm mb-0 me-2" style="border-radius: 8px; font-weight: 600;">Selesaikan Magang Tanpa Laporan</button>
                                        <?php endif; ?>
                                        <button type="submit" class="btn btn-unggah-laporan btn-sm mb-0">Unggah Laporan Sekarang</button>
                                    </div>
                                </form>
                            </div>
                        <?php else : ?>
                            <?php if ($siswa->laporan) : ?>
                                <?php if ($siswa->is_laporan_approved) : ?>
                                   <div class="alert alert-green-soft text-sm py-3 px-3 d-flex align-items-center">
                                        <i class="fa fa-check-circle me-2" style="color: #10b981; font-size: 1.2rem;"></i>
                                        <span>Laporan Anda telah disetujui. Selamat, Anda telah menyelesaikan program magang!</span>
                                   </div>
                                <?php else : ?>
                                    <div class="alert alert-green-soft text-sm py-3 px-3 d-flex align-items-center">
                                        <i class="fa fa-info-circle me-2" style="color: #10b981; font-size: 1.2rem;"></i>
                                        <span>Laporan Anda berhasil disimpan dan sedang menunggu persetujuan pembimbing.</span>
                                    </div>
                                <?php endif; ?>
                            <?php else : ?>
                               <div class="alert alert-green-soft text-sm py-3 px-3 d-flex align-items-center">
                                    <i class="fa fa-check-circle me-2" style="color: #10b981; font-size: 1.2rem;"></i>
                                    <span>Selamat, Anda telah menyelesaikan program magang!</span>
                               </div>
                            <?php endif; ?>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-body p-4 border-radius-lg card-laporan-info">
            <div class="d-flex align-items-center mb-3">
                <div class="me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px; background-color: #bce6ca; color: #064e28;">
                    <i class="material-icons" style="font-size: 18px;">badge</i>
                </div>
                <h6 class="mb-0 font-weight-bold" style="color: #064e28; font-size: 1rem;">Informasi Magang</h6>
            </div>
            <?php if ($application) : ?>
                <div class="mb-3">
                    <span class="text-xxs font-weight-bold text-uppercase d-block" style="color: #4a775d; letter-spacing: 0.5px;">Instansi</span>
                    <p class="font-weight-bold text-sm mb-0" style="color: #043319;"><?= $application->instansi ?? '-' ?></p>
                </div>
                <div class="mb-3">
                    <span class="text-xxs font-weight-bold text-uppercase d-block" style="color: #4a775d; letter-spacing: 0.5px;">Pembimbing Lapangan</span>
                    <p class="font-weight-bold text-sm mb-0" style="color: #043319;"><?= $application->nama_pembimbing ?? '-' ?></p>
                </div>
                <div class="mb-3">
                    <span class="text-xxs font-weight-bold text-uppercase d-block" style="color: #4a775d; letter-spacing: 0.5px;">Dosen Pembimbing</span>
                    <!-- PERBAIKAN: Menggunakan dosen_nama dan operator ?? -->
                    <p class="font-weight-bold text-sm mb-0" style="color: #043319;"><?= $application->dosen_nama ?? 'Belum ditentukan' ?></p>
                </div>
                <hr class="horizontal dark my-3" style="background-image: linear-gradient(90deg, transparent, rgba(13, 74, 41, 0.25), transparent) !important; height: 1px !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-xxs font-weight-bold text-uppercase" style="color: #4a775d; letter-spacing: 0.5px;">Status Akhir</span>
                    <span class="badge" style="background-color: #15803d; color: #ffffff; font-weight: 800; font-size: 11px; padding: 6px 14px; border-radius: 8px; letter-spacing: 0.5px; box-shadow: 0 2px 6px rgba(21, 128, 61, 0.25);">
                        <?= strtoupper($application->status ?? '-') ?>
                    </span>
                </div>
            <?php else : ?>
                <p class="text-sm mb-0" style="color: #4a775d;">Data pendaftaran tidak ditemukan.</p>
            <?php endif ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#btnChangeReport').on('click', function() {
            $('#uploadSection').removeClass('d-none');
            $(this).hide();
        });

        $('#btnFinishWithoutReport').on('click', function() {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Anda akan menyelesaikan program magang tanpa mengunggah laporan.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#15803d",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, Selesaikan!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/siswa/finish_without_report",
                        type: "POST",
                        beforeSend: function() {
                            $('#btnFinishWithoutReport').attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...');
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Berhasil!",
                                    text: res.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire("Gagal", res.message, "error");
                                $('#btnFinishWithoutReport').attr('disabled', false).html('Selesaikan Magang Tanpa Laporan');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            Swal.fire("Error", "Terjadi kesalahan. Pastikan koneksi stabil.", "error");
                            $('#btnFinishWithoutReport').attr('disabled', false).html('Selesaikan Magang Tanpa Laporan');
                        }
                    });
                }
            });
        });

        $('#formUploadLaporan').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            $.ajax({
                url: "/siswa/upload/laporan",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#formUploadLaporan button[type=submit]').attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengunggah...');
                },
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: res.message,
                            showConfirmButton: true
                        }).then(() => {
                            $('#formUploadLaporan button[type=submit]').attr('disabled', false).html('Unggah Laporan Sekarang');
                        });
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire("Error", "Terjadi kesalahan saat mengunggah laporan. Pastikan koneksi stabil.", "error");
                    $('#formUploadLaporan button[type=submit]').attr('disabled', false).html('Unggah Laporan Sekarang');
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>