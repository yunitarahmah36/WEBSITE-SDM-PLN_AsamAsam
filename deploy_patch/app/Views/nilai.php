<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <?php if (getApplicationSiswa(getSidByUid(user_id())) && isLaporanUploaded(getSidByUid(user_id()))) : ?>
            <div class="card card-body mb-3 bg-success text-white border-success">
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <div>
                        <span class="text-sm">Anda sudah melakukan upload laporan akhir magang. Silahkan tunggu beberapa hari kedepan untuk melihat capaian hasil kegiatan magang anda.</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="/assets/laporan/<?= $siswa->laporan ?>" target="_blank" class="btn btn-sm bg-gradient-success mb-0 shadow">LIHAT</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (getApplicationSiswa(getSidByUid(user_id())) && !isLaporanUploaded(getSidByUid(user_id()))) : ?>
            <?php if (isEventFinished(user_id()) || date('Y-m-d') > $siswa->tgl_selesai) : ?> <!-- jika status magang selesai-->
                <div class="card card-body mb-3 bg-warning text-white border-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>
                            <i class="fa fa-info-circle ml-2"></i> Perhatian
                            </strong> <br>
                            <span class="text-sm">
                                Anda belum melakukan upload laporan akhir magang. Silahkan upload laporan akhir magang anda. 
                                <br> Hal ini diperlukan untuk kalkulasi nilai.
                                <br> <b class="text-dark">laporan yang sudah di upload tidak dapat diubah, pastikan laporan yang anda upload sudah benar.</b>
                        </span>
                        </div>
                        <div class="d-flex mb-0 pb-0 mt-2">
                            <button class="btn btn-sm bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#malaporan">Upload</button>
                        </div>
                    </div>

                    <div class="modal fade" id="malaporan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="malaporan" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="card card-plain">
                                        <div class="card-header pb-0 text-left">
                                            <h5 class="">Tambah Laporan Akhir Magang</h5>
                                        </div>
                                        <div class="card-body">
                                            <form method="post" action="/siswa/upload/laporan" class="falaporan" enctype="multipart/form-data">
                                                <?= csrf_field() ?>
                                                <div class="mb-3">
                                                    <label for="laporan" class="form-label">Laporan Akhir Magang</label>
                                                    <div class="input-group input-group-outline">
                                                        <input type="file" class="form-control" id="laporan" name="laporan" accept=".pdf" required>
                                                    </div>
                                                </div>
                                                <div class="mt-4 d-flex gap-2 mb-0">
                                                    <button type="button" class="btn bg-gradient-light w-100" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn bg-gradient-dark w-100">Upload</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        <?php endif ?>
    </div>
    <div class="col-12 col-md-7">
        <div class="card">
            <div class="card-header pb-0 mb-0">
                <h6>Capaian Hasil Kegiatan Magang</h6>
            </div>
            <div class="card-body pt-0 mt-2">
                <?php if ($nilai) : ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-normal mb-0 pb-0">Kedisiplinan</h6>
                        <h6 class="mb-0 pb-0"><?= $nilai->n_disiplin ?></h6>
                    </div>
                    <hr class="dark horizontal my-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-normal mb-0 pb-0">Kemauan Kerja / Motivasi</h6>
                        <h6 class="mb-0 pb-0"><?= $nilai->n_motivasi ?></h6>
                    </div>
                    <hr class="dark horizontal my-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-normal mb-0 pb-0">Kehadiran</h6>
                        <h6 class="mb-0 pb-0"><?= $nilai->n_kehadiran ?></h6>
                    </div>
                    <hr class="dark horizontal my-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-normal mb-0 pb-0">Inisiatif dan kreatifitas</h6>
                        <h6 class="mb-0 pb-0"><?= $nilai->n_kreatifitas ?></h6>
                    </div>
                    <hr class="dark horizontal my-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-normal mb-0 pb-0">Kejujuran dan Tanggung Jawab</h6>
                        <h6 class="mb-0 pb-0"><?= $nilai->n_kejujuran ?></h6>
                    </div>
                    <hr class="dark horizontal my-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-normal mb-0 pb-0">Kesopanan dan Personalia</h6>
                        <h6 class="mb-0 pb-0"><?= $nilai->n_kesopanan ?></h6>
                    </div>
                    <hr class="dark horizontal my-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-normal mb-0 pb-0">Kerjasama</h6>
                        <h6 class="mb-0 pb-0"><?= $nilai->n_kerjasama ?></h6>
                    </div>
                    <hr class="dark horizontal my-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-normal mb-0 pb-0">Laporan</h6>
                        <h6 class="mb-0 pb-0"><?= $nilai->n_laporan ?></h6>
                    </div>
                    <?php if (isset($nilai->n_dosen) && $nilai->n_dosen !== null) : ?>
                    <hr class="dark horizontal my-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-normal mb-0 pb-0 text-info">Nilai Akademik (Dosen)</h6>
                        <h6 class="mb-0 pb-0 text-info"><?= $nilai->n_dosen ?></h6>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold text-lg mb-0 pb-0">Rata-rata</h6>
                        <h6 class="mb-0 pb-0 text-lg">
                            <?php 
                                // rata-rata nilai: Normalisasi nilai pembimbing (skala 10) ke skala 100
                                $items_pembimbing = [
                                    $nilai->n_disiplin, $nilai->n_motivasi, $nilai->n_kehadiran, 
                                    $nilai->n_kreatifitas, $nilai->n_kejujuran, $nilai->n_kesopanan, 
                                    $nilai->n_kerjasama, $nilai->n_laporan
                                ];
                                
                                $total_pembimbing = 0;
                                foreach ($items_pembimbing as $ni) {
                                    $total_pembimbing += ($ni <= 10) ? ($ni * 10) : $ni;
                                }

                                $total_nilai = $total_pembimbing;
                                $pembagi = 8;
                                
                                if (isset($nilai->n_dosen) && $nilai->n_dosen !== null) {
                                    $total_nilai += $nilai->n_dosen;
                                    $pembagi++;
                                }
                                $rata = $total_nilai / $pembagi;

                                // echo nilai
                                echo round($rata, 2);
                            ?>
                        </h6>
                    </div>

                <?php else : ?>
                    <div class="badge badge-info border border-info w-100 p-3" style="text-align: left; white-space: normal !important;">
                        <div class="font-weight-normal" style="line-height: 0.48cm; font-size: 14px; text-transform: capitalize;">
                            Capaian hasil kegiatan magang anda belum keluar, mohon tunggu beberapa hari kedepan. Jika hal ini sudah berlangsung lama, silahkan hubungi pembimbing magang anda.
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-5">
        <div class="card">
            <div class="card-header pb-0 mb-0">
                <h6>
                    Informasi Sertifikat & Nilai
                </h6>
            </div>
            <div class="card-body pt-0 mt-0">
                <?php if ($nilai && getStatusSiswa($siswa->id) == 'selesai'): ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa <?= $siswa->is_safety_ready ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' ?> me-2"></i>
                            <span class="text-sm">Konfirmasi Materi Safety & K3</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa <?= $siswa->is_laporan_approved ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' ?> me-2"></i>
                            <span class="text-sm">Persetujuan Laporan Akhir</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa fa-check-circle text-success me-2"></i>
                            <span class="text-sm">Nilai Akhir Terverifikasi</span>
                        </div>
                    </div>

                    <?php if ($siswa->is_safety_ready && $siswa->is_laporan_approved): ?>
                        <p class="text-success text-bold mb-2"><i class="fa fa-check-circle"></i> E-Sertifikat Siap Diunduh.</p>
                        <a href="/sertifikat/download/<?= $siswa->id ?>" class="btn btn-info w-100 mb-3">
                            <i class="fa fa-download me-1"></i> Download E-Sertifikat
                        </a>
                    <?php else: ?>
                        <div class="alert alert-light border border-warning p-2 mb-3">
                            <small class="text-dark">Sertifikat akan tersedia setelah seluruh persyaratan di atas terpenuhi (Centang Hijau).</small>
                        </div>
                        <button disabled class="btn btn-secondary w-100 mb-3 opacity-5">
                            <i class="fa fa-lock me-1"></i> Sertifikat Terkunci
                        </button>
                    <?php endif; ?>

                    <b>Catatan:</b> Sertifikat fisik magang dan surat hasil magang dapat diambil di Kantor TU.
                <?php else: ?>
                    <p class="text-sm"><b>Sertifikat magang</b> akan tersedia di sini apabila kegiatan magang telah selesai dan seluruh nilai serta laporan telah diverifikasi oleh pembimbing.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>


<?= $this->section('bottomsc'); ?>
<?php if (getApplicationSiswa(getSidByUid(user_id())) && !isLaporanUploaded(getSidByUid(user_id()))) : ?>
    <script src="/assets/js/plugins/sweetalert.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".falaporan").submit(function(e) {
                e.preventDefault();
                var data = new FormData(this);
                $.ajax({
                    url: "/siswa/upload/laporan",
                    type: "post",
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        response.success ? Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            window.location.reload();
                        }) : Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            })
        });
    </script>
<?php endif; ?>
<?= $this->endSection(); ?>