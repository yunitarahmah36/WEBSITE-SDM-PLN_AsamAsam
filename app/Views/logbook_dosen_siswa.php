<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12 mb-3">
        <div class="card card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <a href="/bimbingan-dosen" class="d-flex gap-2 text-dark align-items-center">
                    <i class="fa fa-arrow-left"></i>
                    <strong>Kembali ke Daftar Bimbingan</strong>
                </a>
                <div>
                    <h6 class="mb-0">Logbook: <?= $siswa->nama ?> (<?= $siswa->nim ?>)</h6>
                </div>
            </div>

            <?php if (getStatusSiswa($siswa->id) == 'selesai' && $siswa->laporan) : ?>
                <div class="card card-body mt-2 mb-3 bg-info text-white border-info">
                    Kegiatan magang mahasiswa ini sudah selesai dan laporan akhir telah diunggah. Silakan berikan nilai akademik (nilai dosen).
                    <div class="mt-2 mb-0 d-flex gap-2">
                        <?php if (nilaiAvailable($siswa->id)) : ?>
                            <?php $nilaiSiswa = getNilaiSiswa($siswa->id); ?>
                            <?php if ($nilaiSiswa->n_dosen !== null) : ?>
                                <button class="btn btn-sm bg-gradient-success mb-0" data-bs-toggle="modal" data-bs-target="#mdnilai">DETAIL & EDIT NILAI DOSEN</button>
                            <?php else : ?>
                                <button class="btn btn-sm bg-gradient-warning mb-0 text-white" data-bs-toggle="modal" data-bs-target="#manilai">INPUT NILAI DOSEN</button>
                            <?php endif ?>
                        <?php else : ?>
                            <div class="badge bg-warning">Menunggu Pembimbing Lapangan menginisiasi form nilai.</div>
                        <?php endif ?>
                        <a href="/assets/laporan/<?= $siswa->laporan ?>" target="_blank" class="btn btn-sm bg-gradient-success mb-0">UNDUH LAPORAN AKHIR</a>
                    </div>
                </div>

                <?php if (nilaiAvailable($siswa->id)) : ?>
                    <?php $nilaiSiswa = getNilaiSiswa($siswa->id) ?>
                    <!-- modal detail nilai -->
                    <div class="modal fade" id="mdnilai" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form class="formeditnilai">
                                    <div class="modal-body p-0">
                                        <div class="card card-body">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <h6 class="m-0">Nilai Dosen Pembimbing</h6>
                                            </div>
                                            <input type="hidden" name="id" value="<?= $nilaiSiswa->id ?>">
                                            <input type="hidden" name="ids" value="<?= $siswa->id ?>">
                                            <div class="input-group input-group-outline mb-3 is-filled">
                                                <label for="n_dosen" class="form-label">Skor Nilai Akademik / Laporan Khusus</label>
                                                <input type="number" class="form-control" name="n_dosen" id="n_dosen" min="0" max="100" value="<?= $nilaiSiswa->n_dosen ?>" required>
                                            </div>
                                            <div class="mt-4 d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm btn-secondary mb-0" data-bs-dismiss="modal">TUTUP</button>
                                                <button type="submit" class="btn btn-sm btn-info mb-0">UPDATE NILAI</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- modal tambah nilai -->
                    <div class="modal fade" id="manilai" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form class="formaddnilai">
                                    <div class="modal-body p-0">
                                        <div class="card card-body">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <h6 class="m-0">Input Nilai Dosen Pembimbing</h6>
                                            </div>
                                            <input type="hidden" name="id" value="<?= $nilaiSiswa->id ?>">
                                            <input type="hidden" name="ids" value="<?= $siswa->id ?>">
                                            <div class="input-group input-group-outline mb-3">
                                                <label for="n_dosen" class="form-label">Skor Nilai Akademik / Laporan Khusus</label>
                                                <input type="number" class="form-control" name="n_dosen" id="n_dosen" min="0" max="100" required>
                                            </div>
                                            <div class="mt-4 d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm btn-secondary mb-0" data-bs-dismiss="modal">BATAL</button>
                                                <button type="submit" class="btn btn-sm btn-info mb-0">SIMPAN NILAI</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
            <?php elseif ($siswa->laporan == '') : ?>
                <div class="card card-body mt-2 mb-3 bg-warning text-dark border-warning">
                    Mahasiswa ini belum mengunggah laporan akhir magang.
                </div>
            <?php endif; ?>
            
            <div class="text-dark mt-3">
                <?php $clog = count($logbooks); $i = 1; ?>
                <div class="accordion accLog" id="logAccordion">
                    <?php foreach ($logbooks as $idx => $log) : ?>
                        <div class="accordion-item mb-2 text-dark">
                            <div class="accordion-header" id="hla<?= $log->id ?>">
                                <div class="card card-body p-2 bg-gray-200 shadow-none rounded px-4 mb-2" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#la<?= $log->id ?>" aria-expanded="false" aria-controls="la<?= $log->id ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="">
                                            <div class="mb-0"><b><?= $log->tanggal ?></b></div>
                                            <div class="text-xs"><?= genBadgeKehadiran($log->keterangan) ?></div>
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            <div class="text-xs mb-1 d-flex gap-2 flex-row">
                                                <?php if ($log->telat) : ?>
                                                    <span class="badge badge-danger border border-danger">Telat diisi</span>
                                                <?php endif ?>
                                                <?= genBadgeStatusApplication($log->status) ?>
                                            </div>
                                            <div class="text-sm"><?= $log->jam_masuk ?> - <?= $log->jam_keluar ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="la<?= $log->id ?>" class="accordion-collapse collapse" aria-labelledby="hla<?= $log->id ?>" data-bs-parent="#logAccordion" style="">
                                <?php $i++ ?>
                                <div class="accordion-body card card-body py-3 bg-gray-200 shadow-none rounded px-3 text-sm opacity-8 <?= $i == $clog ? 'mb-3' : '' ?>" style="text-align: left !important;">
                                    
                                    <!-- PERBAIKAN TAMPILAN KEGIATAN -->
                                    <div class="mb-3 p-3 bg-white rounded border" style="text-align: left !important;">
                                        <strong style="text-align: left !important; display: block !important;">Kegiatan:</strong>
                                        <div style="text-align: left !important; display: block !important; width: 100%; margin-top: 8px;">
                                            <?= nl2br(htmlspecialchars($log->kegiatan ?? '-')) ?>
                                        </div>
                                    </div>
                                    <!-- END PERBAIKAN -->
                                    
                                    <div class="mb-3 p-3 bg-white rounded border" style="text-align: left !important;">
                                        <strong style="text-align: left !important; display: block !important;">Catatan Dosen Pembimbing:</strong>
                                        <div id="text-catatan-<?= $log->id ?>" style="text-align: left !important; display: block !important; width: 100%;">
                                            <?= $log->catatan_dosen ? nl2br(htmlspecialchars($log->catatan_dosen)) : '<em class="text-muted">Belum ada catatan</em>' ?>
                                        </div>
                                        <div class="mt-2" style="text-align: left !important;">
                                            <button type="button" class="badge badge-primary border border-primary shadow mb-0 btn-edit-catatan" data-id="<?= $log->id ?>" data-catatan="<?= htmlspecialchars($log->catatan_dosen ?? '') ?>">
                                                <i class="fa fa-edit"></i> <?= $log->catatan_dosen ? 'Edit Catatan' : 'Tambah Catatan' ?>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-start align-items-center gap-1">
                                        <a target="_blank" href="/assets/img/logbook/<?= $log->bukti ?>" class="badge badge-info border border-info" title="Lihat bukti kegiatan"><i class="fa fa-search m-0 p-0"></i> Bukti</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                <?php if ($clog == 0) : ?>
                    <div class="text-center py-4 text-muted border rounded bg-light">Belum ada logbook yang diisi oleh siswa.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Catatan -->
<div class="modal fade" id="modalCatatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catatan Dosen Pembimbing</h5>
            </div>
            <form id="formCatatan">
                <div class="modal-body">
                    <input type="hidden" name="id" id="catatan_log_id">
                    <div class="input-group input-group-outline mb-3 is-filled">
                        <label class="form-label">Catatan Bimbingan</label>
                        <textarea class="form-control" name="catatan_dosen" id="catatan_text" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script src="/assets/js/plugins/sweetalert.min.js"></script>
<script>
    $(document).ready(function() {
        $('.btn-edit-catatan').on('click', function() {
            const id = $(this).data('id');
            const catatan = $(this).data('catatan');
            $('#catatan_log_id').val(id);
            $('#catatan_text').val(catatan);
            $('#modalCatatan').modal('show');
        });

        $('#formCatatan').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/logbook/catatan-dosen/update',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'JSON',
                success: function(res) {
                    if (res.success) {
                        $('#modalCatatan').modal('hide');
                        Swal.fire({
                            title: "Berhasil!", text: res.message, icon: "success", timer: 1500, showConfirmButton: false,
                        }).then(() => { location.reload(); });
                    } else { Swal.fire("Gagal!", res.message, "error"); }
                },
                error: function(err) { Swal.fire("Gagal!", "Terjadi kesalahan sistem", "error"); }
            });
        });

        // Form nilai submit
        $('.formeditnilai, .formaddnilai').submit(function(e) {
            e.preventDefault();
            var data = new FormData(this);
            $.ajax({
                cache: false,
                processData: false,
                contentType: false,
                type: "POST",
                url: '/nilai/update', // We can just use update since Pembimbing creates the record
                data: data,
                dataType: "JSON",
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseText,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>