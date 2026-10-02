<?= $this->extend('main'); ?>

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
        <div class="card card-body p-4 mb-4">
            <div class="mb-4">
                <h4 class="m-0 p-0">Laporan Akhir Magang</h4>
                <p class="text-sm text-secondary">Silahkan unggah laporan akhir kegiatan magang Anda di sini.</p>
            </div>

            <?php if (!$application) : ?>
                <div class="alert alert-warning text-white text-sm">
                    <i class="fa fa-exclamation-triangle me-2"></i>
                    Anda belum memiliki pendaftaran tempat magang yang aktif. Silahkan daftar terlebih dahulu di menu <b>Tempat Magang</b>.
                </div>
            <?php elseif ($application->status == 'pending') : ?>
                <div class="alert alert-info text-white text-sm">
                    <i class="fa fa-info-circle me-2"></i>
                    Pendaftaran Anda masih berstatus <b>Pending</b>. Anda baru dapat mengunggah laporan akhir setelah pendaftaran <b>Disetujui (Accepted)</b>.
                </div>
            <?php else : ?>
                <div class="row">
                    <div class="col-12">
                        <?php if ($siswa->laporan) : ?>
                            <div class="card border border-info bg-gray-100 mb-4">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape bg-gradient-info shadow-info text-center border-radius-md me-3">
                                            <i class="material-icons opacity-10">description</i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-sm">Laporan Tersimpan</h6>
                                            <p class="text-xs text-secondary mb-0">File: <?= $siswa->laporan ?></p>
                                        </div>
                                        <div>
                                            <a href="<?= base_url('assets/laporan/' . $siswa->laporan) ?>" target="_blank" class="btn btn-sm btn-outline-info mb-0">Lihat File</a>
                                        </div>
                                    </div>
                                    <hr class="horizontal dark my-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-xs font-weight-bold">Status: <span class="badge badge-sm <?= $siswa->is_laporan_approved ? 'bg-gradient-success' : 'bg-gradient-secondary' ?>"><?= $siswa->is_laporan_approved ? 'Disetujui' : 'Menunggu Approval' ?></span></span>
                                        <?php if (!$siswa->is_laporan_approved) : ?>
                                            <button class="btn btn-link text-dark text-xs mb-0 p-0" id="btnChangeReport"><i class="fa fa-edit me-1"></i> Ganti Laporan</button>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if ($application->status != 'selesai') : ?>
                            <div id="uploadSection" class="<?= $siswa->laporan ? 'd-none' : '' ?>">
                                <form id="formUploadLaporan" enctype="multipart/form-data">
                                    <div class="badge badge-light text-dark w-100 mb-4 p-3 text-start border" style="white-space: normal;">
                                        <p class="mb-1 font-weight-bold">Instruksi Pengunggahan:</p>
                                        <ul class="text-xs mb-0 ps-3">
                                            <li>File harus dalam format <b>PDF</b>, <b>DOC</b>, atau <b>DOCX</b>.</li>
                                            <li>Ukuran file maksimal <b>5MB</b>.</li>
                                            <li>Setelah mengunggah, status magang Anda akan berubah menjadi <b>Selesai</b>.</li>
                                            <?php if ($isSmk) : ?>
                                                <li><b class="text-info">Untuk Siswa SMK:</b> Mengunggah laporan magang bersifat opsional. Anda dapat menyelesaikan program magang tanpa laporan menggunakan tombol di bawah.</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>

                                    <div class="input-group input-group-outline mb-4">
                                        <input type="file" name="laporan" id="file_laporan" class="form-control" accept=".pdf,.doc,.docx" required>
                                    </div>

                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <?php if ($isSmk) : ?>
                                            <button type="button" id="btnFinishWithoutReport" class="btn btn-outline-info btn-sm mb-0 me-2">Selesaikan Magang Tanpa Laporan</button>
                                        <?php endif; ?>
                                        <button type="submit" class="btn bg-gradient-dark btn-sm mb-0">Unggah Laporan Sekarang</button>
                                    </div>
                                </form>
                            </div>
                        <?php else : ?>
                            <?php if ($siswa->laporan) : ?>
                                <?php if ($siswa->is_laporan_approved) : ?>
                                   <div class="alert alert-success text-white text-sm">
                                        <i class="fa fa-check-circle me-2"></i>
                                        Laporan Anda telah disetujui. Selamat, Anda telah menyelesaikan program magang!
                                   </div>
                                <?php else : ?>
                                    <div class="alert alert-info text-white text-sm">
                                        <i class="fa fa-info-circle me-2"></i>
                                        Laporan Anda berhasil disimpan dan sedang menunggu persetujuan pembimbing.
                                    </div>
                                <?php endif; ?>
                            <?php else : ?>
                               <div class="alert alert-success text-white text-sm">
                                    <i class="fa fa-check-circle me-2"></i>
                                    Selamat, Anda telah menyelesaikan program magang!
                               </div>
                            <?php endif; ?>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-body p-4 border-radius-lg bg-gradient-dark text-white">
            <h6 class="text-white mb-3">Informasi Magang</h6>
            <?php if ($application) : ?>
                <div class="mb-3">
                    <span class="text-xs opacity-7">Instansi</span>
                    <p class="font-weight-bold text-sm mb-0"><?= $application->instansi ?></p>
                </div>
                <div class="mb-3">
                    <span class="text-xs opacity-7">Pembimbing Lapangan</span>
                    <p class="font-weight-bold text-sm mb-0"><?= $application->nama_pembimbing ?></p>
                </div>
                <div class="mb-3">
                    <span class="text-xs opacity-7">Dosen Pembimbing</span>
                    <p class="font-weight-bold text-sm mb-0"><?= $application->nama_dosen ?: 'Belum ditentukan' ?></p>
                </div>
                <hr class="horizontal light my-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-xs opacity-7">Status Akhir</span>
                    <span class="text-uppercase font-weight-bold text-xs"><?= $application->status ?></span>
                </div>
            <?php else : ?>
                <p class="text-sm opacity-7 mb-0">Data pendaftaran tidak ditemukan.</p>
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
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
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
