<?= $this->extend('main'); ?>
<?= $this->section('topsc') ;?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<?= $this->endSection() ;?>

<?= $this->section('content'); ?>
<div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <?php if (!$is_safety_ready && !empty(getApplicationSiswa(getSidByUid(user_id())))) : ?>
            <div class="card card-body text-center p-5">
                <i class="material-icons text-danger fs-1 mb-3">gpp_maybe</i>
                <h5 class="mb-2">Akses Terkunci: Prasyarat Keselamatan</h5>
                <p class="text-secondary px-lg-5">Anda wajib mempelajari dan mengonfirmasi pemahaman Materi Safety & K3 di dashboard sebelum dapat mengakses fitur presensi dan logbook ini.</p>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <a href="/dashboard" class="btn btn-sm btn-outline-dark">Kembali ke Dashboard</a>
                    <a href="/materi-k3" class="btn btn-sm bg-gradient-danger">Buka Materi K3</a>
                </div>
            </div>
        <?php elseif ($tempat || (isSiswaActive(getSidByUid(user_id())) && getStatusSiswa(getSidByUid(user_id())) !== 'selesai')) : ?>
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Form <?= isset($edit_logbook) ? 'Edit ' : ''  ?>Kehadiran</h5>
                    </div>
                    <form class="form-absen" method="post" enctype="multipart/form-data">
                        <?php if (isset($edit_logbook)) : ?>
                            <input type="hidden" name="lid" id="lid" value="<?= $edit_logbook->id ?>">
                            <input type="hidden" name="pid" value="<?= $edit_logbook->id_pembimbing ?>">
                            <input type="hidden" name="tid" value="<?= $edit_logbook->id_tempat ?>">
                        <?php elseif ($tempat) : ?>
                            <input type="hidden" name="pid" value="<?= $tempat->pid ?>">
                            <input type="hidden" name="tid" value="<?= $tempat->tid ?>">
                        <?php endif ?>
                        <input type="hidden" name="sid" value="<?= getSidByUid(user_id()) ?>">

                        <?php if (!$tempat && !isset($edit_logbook)) : ?>
                            <div class="alert alert-warning text-white border-0">
                                <h6 class="text-white">Penempatan Belum Tersedia</h6>
                                <p class="text-sm mb-0">Anda belum memiliki penempatan magang yang aktif. Silakan ajukan lamaran atau hubungi admin jika ini adalah kesalahan.</p>
                            </div>
                        <?php else : ?>
                            <div class="mb-3">
                                <label class="form-label mb-1" for="tanggal">Tanggal Kegiatan</label>
                                <div class="input-group input-group-outline">
                                    <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= isset($edit_logbook) ? $edit_logbook->tanggal : date('Y-m-d') ?>" max="<?= date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-1" for="keterangan">Keterangan</label>
                                <div class="input-group input-group-outline mb-3 is-filled">
                                    <select name="keterangan" id="keterangan" class="form-control">
                                        <option value="">-- Pilih Keterangan --</option>
                                        <option <?= !isset($edit_logbook) ? '' : ($edit_logbook->keterangan == 'hadir' ? 'selected' : '') ?> value="hadir">Hadir</option>
                                        <option <?= !isset($edit_logbook) ? '' : ($edit_logbook->keterangan == 'izin' ? 'selected' : '') ?> value="izin">izin</option>
                                        <option <?= !isset($edit_logbook) ? '' : ($edit_logbook->keterangan == 'sakit' ? 'selected' : '') ?> value="sakit">sakit</option>
                                        <option <?= !isset($edit_logbook) ? '' : ($edit_logbook->keterangan == 'alfa' ? 'selected' : '') ?> value="alfa">alfa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col">
                                    <label class="form-label mb-1" for="jam_masuk">Jam Masuk</label>
                                    <div class="input-group input-group-outline">
                                        <input type="text" class="form-control ps-2" name="jam_masuk" id="jam_masuk" value="<?= isset($edit_logbook) ? $edit_logbook->jam_masuk : ''  ?>" placeholder="Pilih jam masuk" readonly="readonly" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="form-label mb-1" for="jam_keluar">Jam Keluar</label>
                                    <div class="input-group input-group-outline">
                                        <input type="text" class="form-control ps-2" name="jam_keluar" id="jam_keluar" value="<?= isset($edit_logbook) ? $edit_logbook->jam_keluar : ''  ?>" placeholder="Pilih jam keluar" readonly="readonly" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-1" for="kegiatan">Kegiatan yang dilakukan</label>
                                <div class="input-group input-group-outline mb-3">
                                    <textarea name="kegiatan" id="kegiatan" rows="4" class="form-control" placeholder="kegiatan hari ini"><?= isset($edit_logbook) ? $edit_logbook->kegiatan : ''  ?></textarea>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-1" for="bukti">Bukti Kegiatan</label>
                                <div class="input-group input-group-outline">
                                    <input type="file" class="form-control" name="bukti" id="bukti" required accept="image/*" >
                                </div>
                            </div>
                            <!-- button with background gradient-dark -->
                            <button type="submit" class="btn bg-gradient-dark btn-block"><?= isset($edit_logbook) ? 'Perbarui' : '' ?> Absen</button>
                            <?php if (isset($edit_logbook)) : ?>
                                <button type="button" class="btn btn-secondary btn-block shadow me-2 btn-back">Kembali</button>
                            <?php endif ?>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        <?php endif ?>
    </div>
    <div class="col-12 col-md-8 mt-3">
        <?php if (isset($edit_logbook)) : ?>
            <div class="badge badge-info border border-1 border-info w-100 px-3 py-2" style="text-align: start !important; text-transform: none !important; white-space: inherit;">
                <h6 style="letter-spacing: 1px;">Perhatian !</h6>
                <p style="line-height: .6cm;">Pastikan detail dari <strong>absensi</strong> dan <strong>logbook</strong> anda <u>sudah benar</u>, agar tidak terjadi penolakan oleh pembimbing magang.</p>
            </div>
        <?php else : ?>
            <div class="alert alert-light border shadow-sm w-100 text-center py-3">
                <span class="text-dark">Untuk melihat daftar logbook Anda, silakan buka menu <a href="/logbookku" class="text-primary font-weight-bold text-decoration-underline">Laporan Logbook</a>.</span>
            </div>
        <?php endif ?>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="/assets/js/plugins/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {
        $('body').tooltip({ selector: '[data-bs-toggle="tooltip"]' });

        $("#jam_masuk, #jam_keluar").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            disableMobile: true
        });
    });
</script>

<?php if (isset($edit_logbook)) : ?>
    <script>
        $(document).ready(function() {
            $('.btn-back').on('click', function() {
                return window.history.back();
            });

            $('.form-absen').submit(function(e) {
                e.preventDefault();
                var data = new FormData(this);
                $.ajax({
                    url: '/kehadiran/store',
                    type: 'post',
                    data: data,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log(data);
                        data.success ? Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/kehadiran'
                            }
                        }) : Swal.fire({
                            title: 'Gagal!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }
                });
            });
        });
    </script>
<?php else : ?>
    <script>
        $(document).ready(function() {
            $('#keterangan').change(function() {
                if ($(this).val() == 'alfa') {
                    $('#jam_keluar').attr('disabled', true);
                    $('#jam_masuk').attr('disabled', true);
                    $('#kegiatan').attr('disabled', true);
                } else if ($(this).val() == 'sakit' || $(this).val() == 'izin') {
                    $('#jam_keluar').attr('disabled', true);
                    $('#jam_masuk').attr('disabled', true);
                } else {
                    $('#jam_keluar').attr('disabled', false);
                    $('#kegiatan').attr('disabled', false);
                    $('#jam_masuk').attr('disabled', false);
                }
            });

            $('.form-absen').submit(function(e) {
                e.preventDefault();
                // post with file
                var data = new FormData(this);
                $.ajax({
                    url: '/kehadiran/store',
                    type: 'post',
                    data: data,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log(data);
                        data.success ? Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/kehadiran'
                            }
                        }) : Swal.fire({
                            title: 'Gagal!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }
                });
            });
        });
    </script>
<?php endif ?>
<?= $this->endSection(); ?>