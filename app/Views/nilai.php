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

        <?php if (!$isSmk && getApplicationSiswa(getSidByUid(user_id())) && !isLaporanUploaded(getSidByUid(user_id()))) : ?>
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
        <div class="card shadow-sm border-0">
            <div class="card-header pb-0 mb-0">
                <h6 class="mb-0 font-weight-bolder">Capaian Hasil Kegiatan Magang</h6>
            </div>
            <div class="card-body pt-0 mt-2">
                <?php if ($nilai) : ?>
                    <?php if (!empty($nilai->file_penilaian)) : ?>
                        <div class="alert alert-success text-white p-3 mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2 shadow-sm" style="border-radius: 10px; background: linear-gradient(135deg, #2dce89 0%, #28a745 100%);">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa fa-file-circle-check text-2xl"></i>
                                <div>
                                    <strong class="d-block text-xs font-weight-bolder">Berkas Lembar Penilaian Fisik Terlampir</strong>
                                    <span class="text-xxs opacity-9">Pembimbing lapangan telah melampirkan lembar nilai fisik resmi bertanda tangan.</span>
                                </div>
                            </div>
                            <a href="<?= base_url('assets/file_penilaian/' . $nilai->file_penilaian) ?>" target="_blank" class="btn btn-xs bg-white text-success font-weight-bold mb-0 shadow-sm">
                                <i class="fa fa-download me-1"></i> Unduh Berkas Nilai
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($nilaiDetails)) : ?>
                        <?php 
                        $isModeBerkas = (!empty($nilai->file_penilaian) && (($nilai->metode_penilaian ?? '') === 'file' || empty($nilai->total_nilai) || (!empty($nilaiDetails) && count($nilaiDetails) === 1 && $nilaiDetails[0]->nama_atribut === 'Berkas Lembar Penilaian Resmi Pembimbing Lapangan')));
                        ?>
                        <div class="mb-2 text-xs text-muted">
                            Format penilaian dari instansi: <strong><?= esc($siswa->instansi ?? '-') ?></strong>
                        </div>
                        <?php foreach ($nilaiDetails as $idx => $dt) : ?>
                            <?php 
                            $isItemBerkas = ($isModeBerkas || $dt->nama_atribut === 'Berkas Lembar Penilaian Resmi Pembimbing Lapangan' || !empty($nilai->file_penilaian));
                            ?>
                            <?php if ($idx > 0) : ?>
                                <hr class="dark horizontal my-1">
                            <?php endif; ?>
                            <div class="d-flex justify-content-between align-items-center py-1">
                                <div>
                                    <h6 class="font-weight-normal mb-0 pb-0 text-sm"><?= esc($dt->nama_atribut) ?></h6>
                                    <?php if (!$isItemBerkas && !empty($dt->bobot) && $dt->bobot > 0 && ($nilai->metode_penilaian ?? '') === 'bobot') : ?>
                                        <span class="text-xxs text-muted d-block">Bobot: <?= $dt->bobot ?>%</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-end">
                                    <?php if ($isItemBerkas) : ?>
                                        <?php if (!empty($nilai->file_penilaian)) : ?>
                                            <a href="<?= base_url('assets/file_penilaian/' . $nilai->file_penilaian) ?>" target="_blank" class="badge bg-gradient-success text-xxs text-white shadow-sm" title="Lihat Berkas Penilaian">
                                                <i class="fa fa-file-pdf me-1"></i> Lihat Berkas Penilaian
                                            </a>
                                        <?php else : ?>
                                            <span class="badge bg-gradient-success text-xxs">Lihat Berkas Penilaian</span>
                                        <?php endif; ?>
                                    <?php elseif ($dt->nilai > 0) : ?>
                                        <h6 class="mb-0 pb-0 font-weight-bold text-sm"><?= $dt->nilai ?></h6>
                                    <?php else : ?>
                                        <h6 class="mb-0 pb-0 font-weight-bold text-sm"><?= $dt->nilai ?></h6>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (isset($nilai->n_dosen) && $nilai->n_dosen !== null) : ?>
                            <hr class="dark horizontal my-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="font-weight-normal mb-0 pb-0 text-info">Nilai Akademik (Dosen)</h6>
                                <h6 class="mb-0 pb-0 text-info font-weight-bold"><?= $nilai->n_dosen ?></h6>
                            </div>
                        <?php endif; ?>

                        <hr class="dark horizontal mt-3 my-2">
                        <div class="d-flex justify-content-between align-items-center p-2 rounded bg-gray-100">
                            <div>
                                <h6 class="font-weight-bold text-base mb-0 pb-0">Nilai Akhir</h6>
                                <span class="text-xxs text-muted">
                                    Metode: <?= $isModeBerkas ? 'Berkas Lembar Penilaian Fisik' : (($nilai->metode_penilaian ?? '') === 'bobot' ? 'Pembobotan Atribut' : 'Rata-rata Atribut') ?>
                                </span>
                            </div>
                            <h5 class="mb-0 pb-0 font-weight-bolder text-success">
                                <?php if ($isModeBerkas) : ?>
                                    <?php if (!empty($nilai->file_penilaian)) : ?>
                                        <a href="<?= base_url('assets/file_penilaian/' . $nilai->file_penilaian) ?>" target="_blank" class="badge bg-gradient-success text-xs text-white shadow-sm" title="Lihat Berkas Penilaian">
                                            <i class="fa fa-file-pdf me-1"></i> Lihat Berkas Penilaian
                                        </a>
                                    <?php else : ?>
                                        <span class="badge bg-gradient-success text-xs">Lihat Berkas Penilaian</span>
                                    <?php endif; ?>
                                <?php elseif (($nilai->total_nilai ?? 0) > 0) : ?>
                                    <?= $nilai->total_nilai ?>
                                <?php else : ?>
                                    0
                                <?php endif; ?>
                            </h5>
                        </div>

                        <?php if (!empty($nilai->catatan)) : ?>
                            <div class="mt-3 p-3 rounded bg-light border">
                                <label class="text-xs font-weight-bolder text-dark mb-1 d-block">
                                    <i class="fa fa-comment-dots text-success me-1"></i> Catatan & Komentar Evaluasi Pembimbing:
                                </label>
                                <p class="text-xs text-secondary mb-0" style="white-space: pre-line;"><?= esc($nilai->catatan) ?></p>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
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
                        <?php 
                        $includeLaporan = !$isSmk || !empty($siswa->laporan);
                        if ($includeLaporan) : 
                        ?>
                        <hr class="dark horizontal my-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="font-weight-normal mb-0 pb-0">Laporan</h6>
                            <h6 class="mb-0 pb-0"><?= $nilai->n_laporan ?></h6>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($nilai->n_dosen) && $nilai->n_dosen !== null) : ?>
                        <hr class="dark horizontal my-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="font-weight-normal mb-0 pb-0 text-info">Nilai Akademik (Dosen)</h6>
                            <h6 class="mb-0 pb-0 text-info"><?= $nilai->n_dosen ?></h6>
                        </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <h6 class="font-weight-bold text-lg mb-0 pb-0">Rata-rata</h6>
                            <h6 class="mb-0 pb-0 text-lg">
                                <?php 
                                    $items_pembimbing = [
                                        $nilai->n_disiplin, $nilai->n_motivasi, $nilai->n_kehadiran, 
                                        $nilai->n_kreatifitas, $nilai->n_kejujuran, $nilai->n_kesopanan, 
                                        $nilai->n_kerjasama
                                    ];
                                    if ($includeLaporan) {
                                        $items_pembimbing[] = $nilai->n_laporan;
                                    }
                                    
                                    $total_pembimbing = 0;
                                    foreach ($items_pembimbing as $ni) {
                                        $total_pembimbing += ($ni <= 10) ? ($ni * 10) : $ni;
                                    }

                                    $total_nilai = $total_pembimbing;
                                    $pembagi = $includeLaporan ? 8 : 7;
                                    
                                    if (isset($nilai->n_dosen) && $nilai->n_dosen !== null) {
                                        $total_nilai += $nilai->n_dosen;
                                        $pembagi++;
                                    }
                                    $rata = $total_nilai / $pembagi;

                                    echo round($rata, 2);
                                ?>
                            </h6>
                        </div>
                    <?php endif; ?>

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
                    <?php 
                    $isSyaratLengkap = $siswa->is_safety_ready && ($siswa->is_laporan_approved || ($isSmk && empty($siswa->laporan)));
                    $hasCertFile = !empty($siswa->sertifikat_file);
                    ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa <?= $siswa->is_safety_ready ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' ?> me-2"></i>
                            <span class="text-sm">Konfirmasi Materi Safety & K3</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa <?= ($siswa->is_laporan_approved || ($isSmk && empty($siswa->laporan))) ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' ?> me-2"></i>
                            <span class="text-sm">Persetujuan Laporan Akhir <?= $isSmk ? (empty($siswa->laporan) ? '(Tidak Wajib)' : '(Wajib karena mengunggah laporan)') : '' ?></span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa fa-check-circle text-success me-2"></i>
                            <span class="text-sm">Nilai Akhir Terverifikasi</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa <?= $hasCertFile ? 'fa-check-circle text-success' : ($isSyaratLengkap ? 'fa-clock text-warning' : 'fa-lock text-secondary') ?> me-2"></i>
                            <span class="text-sm font-weight-bold <?= $hasCertFile ? 'text-success' : ($isSyaratLengkap ? 'text-warning' : 'text-secondary') ?>">
                                <?= $hasCertFile ? 'Sertifikat Resmi Sudah Diupload' : 'Sertifikat Resmi Bernama' ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($hasCertFile): ?>
                        <div class="alert alert-success text-white p-3 mb-3 shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 8px;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa fa-check-circle fs-5"></i>
                                <div>
                                    <strong class="d-block text-xs">Sertifikat Resmi Tersedia</strong>
                                    <small class="text-xxs">Sertifikat magang resmi yang telah bernama dan bertanda tangan telah diunggah oleh pihak PLN.</small>
                                </div>
                            </div>
                        </div>
                        <a href="/sertifikat/download/<?= $siswa->id ?>" class="btn btn-success w-100 mb-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                            <i class="fa fa-download"></i> Unduh Sertifikat Resmi (PDF)
                        </a>
                    <?php elseif ($isSyaratLengkap): ?>
                        <div class="alert alert-light border border-warning p-3 mb-3 shadow-sm" style="border-radius: 8px; background: #fffdf5;">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fa fa-hourglass-half text-warning fs-5 mt-1"></i>
                                <div>
                                    <strong class="text-xs text-dark d-block mb-1">Menunggu Unggahan Sertifikat Resmi</strong>
                                    <p class="text-xxs text-secondary mb-0" style="line-height: 1.45;">
                                        Kegiatan magang dan nilai Anda telah selesai. Sertifikat magang resmi yang telah bernama dan bertanda tangan saat ini sedang diproses oleh Pembimbing PKL / Admin. Anda dapat mengunduh sertifikat segera setelah berkas diunggah ke sistem.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <button disabled class="btn btn-secondary w-100 mb-3 opacity-6" style="cursor: not-allowed;">
                            <i class="fa fa-clock me-1"></i> Menunggu Upload Sertifikat
                        </button>
                    <?php else: ?>
                        <div class="alert alert-light border border-secondary p-2 mb-3" style="border-radius: 8px;">
                            <small class="text-dark">Sertifikat akan diproses setelah seluruh persyaratan di atas terpenuhi (Centang Hijau).</small>
                        </div>
                        <button disabled class="btn btn-secondary w-100 mb-3 opacity-5" style="cursor: not-allowed;">
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